<?php

namespace App\Filament\Resources\Transaksi;

use App\Enums\SpjStatus;
use App\Filament\Resources\Transaksi\SpjResource\Pages;
use App\Models\Spj;
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

class SpjResource extends Resource
{
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
                    Select::make('program_id')->label('Program')->relationship('program', 'nama')->required()->searchable()->preload(),
                    Select::make('kegiatan_id')->label('Kegiatan')->relationship('kegiatan', 'nama')->required()->searchable()->preload(),
                    Select::make('sub_kegiatan_id')->label('Sub Kegiatan')->relationship('subKegiatan', 'nama')->required()->searchable()->preload(),
                    Select::make('rekening_belanja_id')->label('Rekening Belanja')->relationship('rekeningBelanja', 'nama')->required()->searchable()->preload(),
                    Select::make('status')->options(collect(SpjStatus::cases())->mapWithKeys(fn (SpjStatus $status): array => [$status->value => $status->label()]))->required()->default(SpjStatus::Draft->value),
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
                TextColumn::make('program.nama')->label('Program')->searchable(),
                TextColumn::make('subKegiatan.nama')->label('Sub Kegiatan')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('total_belanja')->label('Total')->money('IDR')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
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
}
