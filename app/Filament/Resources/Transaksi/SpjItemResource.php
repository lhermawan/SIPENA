<?php

namespace App\Filament\Resources\Transaksi;

use App\Filament\Resources\Concerns\TransactionResourceAccess;
use App\Filament\Resources\Transaksi\SpjItemResource\Pages;
use App\Models\SpjItem;
use App\Support\ResourceAccess;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpjItemResource extends Resource
{
    use TransactionResourceAccess;

    protected static ?string $model = SpjItem::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-list-bullet';

    protected static string|null|\UnitEnum $navigationGroup = 'Transaksi';

    protected static ?string $modelLabel = 'Item SPJ';

    protected static ?string $pluralModelLabel = 'Item SPJ';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                Select::make('spj_id')->label('SPJ')->relationship('spj', 'nomor_spj')->required()->searchable()->preload(),
                Textarea::make('uraian')->required()->columnSpanFull(),
                TextInput::make('volume')->numeric()->required()->default(1),
                Select::make('satuan_id')->label('Satuan')->relationship('satuan', 'nama')->required()->searchable()->preload(),
                TextInput::make('harga_satuan')->numeric()->prefix('Rp')->required()->default(0),
                TextInput::make('total')->numeric()->prefix('Rp')->required()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('spj.nomor_spj')->label('SPJ')->searchable()->sortable(),
                TextColumn::make('uraian')->limit(40)->searchable(),
                TextColumn::make('volume')->numeric()->sortable(),
                TextColumn::make('total')->money('IDR')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Diubah')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSpjItems::route('/'),
            'create' => Pages\CreateSpjItem::route('/create'),
            'edit' => Pages\EditSpjItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = ResourceAccess::user();

        if ($user?->hasRole('Bidang') && ! ResourceAccess::isAdmin($user)) {
            $bidangIds = $user->bidangs()->pluck('bidangs.id');

            $query->whereHas('spj', fn (Builder $spjQuery): Builder => $spjQuery->whereIn('bidang_id', $bidangIds));
        }

        return $query;
    }
}
