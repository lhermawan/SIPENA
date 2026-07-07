<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Concerns\AdminOnlyResourceAccess;
use App\Filament\Resources\Master\SatuanResource\Pages;
use App\Models\Satuan;
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

class SatuanResource extends Resource
{
    use AdminOnlyResourceAccess;

    protected static ?string $model = Satuan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-tag';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Satuan';

    protected static ?string $pluralModelLabel = 'Satuan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                TextInput::make('nama')->required()->unique(ignoreRecord: true)->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')->searchable()->sortable(),
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
            'index' => Pages\ListSatuans::route('/'),
            'create' => Pages\CreateSatuan::route('/create'),
            'edit' => Pages\EditSatuan::route('/{record}/edit'),
        ];
    }
}
