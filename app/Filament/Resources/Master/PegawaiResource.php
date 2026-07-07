<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Concerns\AdminOnlyResourceAccess;
use App\Filament\Resources\Master\PegawaiResource\Pages;
use App\Models\Pegawai;
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

class PegawaiResource extends Resource
{
    use AdminOnlyResourceAccess;

    protected static ?string $model = Pegawai::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-users';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Pegawai';

    protected static ?string $pluralModelLabel = 'Pegawai';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                TextInput::make('nama')->required()->maxLength(255),
                TextInput::make('nip')->label('NIP')->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('pangkat')->maxLength(255),
                TextInput::make('golongan')->maxLength(255),
                TextInput::make('jabatan')->maxLength(255),
                TextInput::make('unit_kerja')->label('Unit Kerja')->maxLength(255),
                Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('nip')->label('NIP')->searchable(),
                TextColumn::make('jabatan')->searchable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
