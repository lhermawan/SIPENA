<?php

namespace App\Filament\Resources\Transaksi;

use App\Enums\SpjStatus;
use App\Filament\Resources\Concerns\TransactionResourceAccess;
use App\Filament\Resources\Transaksi\SpjResource\Pages;
use App\Models\Bidang;
use App\Models\Spj;
use App\Support\ResourceAccess;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpjResource extends Resource
{
    use TransactionResourceAccess;

    protected static ?string $model = Spj::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|null|\UnitEnum $navigationGroup = 'Transaksi';

    protected static ?string $modelLabel = 'SPJ';

    protected static ?string $pluralModelLabel = 'SPJ';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Header SPJ')
                ->columns(2)
                ->schema([
                    TextInput::make('nomor_spj')->label('Nomor SPJ')->required()->unique(ignoreRecord: true)->maxLength(255),
                    DatePicker::make('tanggal')->required()->default(now()),
                    Select::make('bidang_id')
                        ->label('Bidang')
                        ->options(fn (): array => static::bidangOptions())
                        ->default(fn (): ?int => ResourceAccess::user()?->bidangs()->value('bidangs.id'))
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('program_id')->label('Program')->relationship('program', 'nama')->required()->searchable()->preload(),
                    Select::make('kegiatan_id')->label('Kegiatan')->relationship('kegiatan', 'nama')->required()->searchable()->preload(),
                    Select::make('sub_kegiatan_id')->label('Sub Kegiatan')->relationship('subKegiatan', 'nama')->required()->searchable()->preload(),
                    Select::make('rekening_belanja_id')->label('Rekening Belanja')->relationship('rekeningBelanja', 'nama')->required()->searchable()->preload(),
                    Select::make('status')->options(collect(SpjStatus::cases())->mapWithKeys(fn (SpjStatus $status): array => [$status->value => $status->label()]))->required()->default(SpjStatus::Draft->value)->disabled(fn (): bool => ! ResourceAccess::isAdmin())->dehydrated(),
                    TextInput::make('total_belanja')->label('Total Belanja')->numeric()->prefix('Rp')->default(0),
                    Textarea::make('terbilang')->columnSpanFull(),
                ]),
            Section::make('Penandatangan')
                ->columns(2)
                ->schema([
                    Select::make('pptk_id')->label('PPTK')->relationship('pptk', 'nama')->searchable()->preload(),
                    Select::make('ppk_id')->label('PPK')->relationship('ppk', 'nama')->searchable()->preload(),
                    Select::make('bendahara_id')->label('Bendahara')->relationship('bendahara', 'nama')->searchable()->preload(),
                    Select::make('pa_kpa_id')->label('PA/KPA')->relationship('paKpa', 'nama')->searchable()->preload(),
                ]),
            Section::make('Detail Item')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Textarea::make('uraian')->required()->columnSpanFull(),
                            TextInput::make('volume')->numeric()->required()->default(1),
                            Select::make('satuan_id')->label('Satuan')->relationship('satuan', 'nama')->required()->searchable()->preload(),
                            TextInput::make('harga_satuan')->numeric()->prefix('Rp')->required()->default(0),
                            TextInput::make('total')->numeric()->prefix('Rp')->required()->default(0),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_spj')->label('Nomor SPJ')->searchable()->sortable(),
                TextColumn::make('tanggal')->date()->sortable(),
                TextColumn::make('bidang.nama')->label('Bidang')->badge()->sortable(),
                TextColumn::make('program.nama')->label('Program')->searchable(),
                TextColumn::make('subKegiatan.nama')->label('Sub Kegiatan')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('total_belanja')->label('Total')->money('IDR')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Spj $record): string => route('spjs.pdf', $record))
                    ->openUrlInNewTab(),
                Action::make('verify')
                    ->label(fn (Spj $record): string => static::verificationLabel($record))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Spj $record): bool => ResourceAccess::canVerifySpj($record))
                    ->action(function (Spj $record): void {
                        $nextStatus = ResourceAccess::nextSpjStatus($record);

                        if (! $nextStatus) {
                            return;
                        }

                        $record->update([
                            'status' => $nextStatus->value,
                            'finalized_at' => $nextStatus === SpjStatus::Final ? now() : $record->finalized_at,
                        ]);

                        activity()
                            ->causedBy(auth()->user())
                            ->performedOn($record)
                            ->event('verified')
                            ->withProperties([
                                'status' => $nextStatus->value,
                                'label' => $nextStatus->label(),
                            ])
                            ->log('Memverifikasi SPJ '.$record->nomor_spj.' menjadi '.$nextStatus->label());
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpjs::route('/'),
            'create' => Pages\CreateSpj::route('/create'),
            'edit' => Pages\EditSpj::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = ResourceAccess::user();

        if ($user?->hasRole('Bidang') && ! ResourceAccess::isAdmin($user)) {
            $query->whereIn('bidang_id', $user->bidangs()->pluck('bidangs.id'));
        }

        return $query;
    }

    private static function bidangOptions(): array
    {
        $user = ResourceAccess::user();

        if (! $user || ResourceAccess::isAdmin($user)) {
            return Bidang::query()->where('is_active', true)->orderBy('nama')->pluck('nama', 'id')->all();
        }

        return $user->bidangs()->where('is_active', true)->orderBy('nama')->pluck('nama', 'bidangs.id')->all();
    }

    private static function verificationLabel(Spj $spj): string
    {
        return match ($spj->status) {
            SpjStatus::Draft => 'Ajukan',
            SpjStatus::VerifikasiPptk => 'Verifikasi PPTK',
            SpjStatus::VerifikasiBendahara => 'Verifikasi Bendahara',
            SpjStatus::PersetujuanPaKpa => 'Setujui PA/KPA',
            SpjStatus::Final => 'Arsipkan',
            default => 'Verifikasi',
        };
    }
}
