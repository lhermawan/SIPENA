<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Concerns\AdminOnlyResourceAccess;
use App\Filament\Resources\Master\PenandatanganResource\Pages;
use App\Models\Penandatangan;
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

class PenandatanganResource extends Resource
{
    use AdminOnlyResourceAccess;

    protected static ?string $model = Penandatangan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-pencil-square';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Penandatangan';

    protected static ?string $pluralModelLabel = 'Penandatangan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                Select::make('pegawai_id')->label('Pegawai')->relationship('pegawai', 'nama')->searchable()->preload(),
                TextInput::make('nama')->required()->maxLength(255),
                TextInput::make('nip')->label('NIP')->maxLength(255),
                TextInput::make('jabatan')->required()->maxLength(255),
                Select::make('peran')->required()->options(['PA/KPA' => 'PA/KPA', 'PPK' => 'PPK', 'PPTK' => 'PPTK', 'Bendahara' => 'Bendahara', 'Pengguna Anggaran' => 'Pengguna Anggaran'])->searchable(),
                Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('peran')->badge()->searchable()->sortable(),
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
            'index' => Pages\ListPenandatangans::route('/'),
            'create' => Pages\CreatePenandatangan::route('/create'),
            'edit' => Pages\EditPenandatangan::route('/{record}/edit'),
        ];
    }
}
