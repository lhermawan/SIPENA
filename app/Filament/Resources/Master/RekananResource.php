<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Concerns\AdminOnlyResourceAccess;
use App\Filament\Resources\Master\RekananResource\Pages;
use App\Models\Rekanan;
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

class RekananResource extends Resource
{
    use AdminOnlyResourceAccess;

    protected static ?string $model = Rekanan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-building-storefront';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Rekanan';

    protected static ?string $pluralModelLabel = 'Rekanan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                TextInput::make('nama')->required()->maxLength(255),
                TextInput::make('npwp')->label('NPWP')->maxLength(255),
                Textarea::make('alamat')->columnSpanFull(),
                TextInput::make('nomor_rekening')->maxLength(255),
                TextInput::make('nama_bank')->maxLength(255),
                TextInput::make('kontak')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('npwp')->label('NPWP')->searchable(),
                TextColumn::make('nama_bank')->searchable(),
                TextColumn::make('kontak')->searchable(),
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
            'index' => Pages\ListRekanans::route('/'),
            'create' => Pages\CreateRekanan::route('/create'),
            'edit' => Pages\EditRekanan::route('/{record}/edit'),
        ];
    }
}
