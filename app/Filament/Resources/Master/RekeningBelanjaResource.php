<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\RekeningBelanjaResource\Pages;
use App\Models\RekeningBelanja;
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

class RekeningBelanjaResource extends Resource
{
    protected static ?string $model = RekeningBelanja::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-banknotes';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Rekening Belanja';

    protected static ?string $pluralModelLabel = 'Rekening Belanja';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                TextInput::make('kode')->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('nama')->required()->maxLength(255)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kode')->searchable()->sortable(),
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
            'index' => Pages\ListRekeningBelanjas::route('/'),
            'create' => Pages\CreateRekeningBelanja::route('/create'),
            'edit' => Pages\EditRekeningBelanja::route('/{record}/edit'),
        ];
    }
}
