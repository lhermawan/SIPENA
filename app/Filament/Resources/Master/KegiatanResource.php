<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Concerns\AdminOnlyResourceAccess;
use App\Filament\Resources\Master\KegiatanResource\Pages;
use App\Models\Kegiatan;
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

class KegiatanResource extends Resource
{
    use AdminOnlyResourceAccess;

    protected static ?string $model = Kegiatan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|null|\UnitEnum $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Kegiatan';

    protected static ?string $pluralModelLabel = 'Kegiatan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                Select::make('program_id')->label('Program')->relationship('program', 'nama')->required()->searchable()->preload(),
                TextInput::make('kode')->required()->maxLength(255),
                TextInput::make('nama')->required()->maxLength(255)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('program.nama')->label('Program')->searchable()->sortable(),
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
            'index' => Pages\ListKegiatans::route('/'),
            'create' => Pages\CreateKegiatan::route('/create'),
            'edit' => Pages\EditKegiatan::route('/{record}/edit'),
        ];
    }
}
