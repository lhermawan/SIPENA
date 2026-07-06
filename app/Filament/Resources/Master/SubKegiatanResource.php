<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\SubKegiatanResource\Pages;
use App\Models\SubKegiatan;
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

class SubKegiatanResource extends Resource
{
    protected static ?string $model = SubKegiatan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-queue-list';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Sub Kegiatan';

    protected static ?string $pluralModelLabel = 'Sub Kegiatan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                Select::make('kegiatan_id')->label('Kegiatan')->relationship('kegiatan', 'nama')->required()->searchable()->preload(),
                TextInput::make('kode')->required()->maxLength(255),
                TextInput::make('nama')->required()->maxLength(255)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kegiatan.nama')->label('Kegiatan')->searchable()->sortable(),
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
            'index' => Pages\ListSubKegiatans::route('/'),
            'create' => Pages\CreateSubKegiatan::route('/create'),
            'edit' => Pages\EditSubKegiatan::route('/{record}/edit'),
        ];
    }
}
