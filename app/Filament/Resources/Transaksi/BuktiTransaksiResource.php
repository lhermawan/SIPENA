<?php

namespace App\Filament\Resources\Transaksi;

use App\Filament\Resources\Transaksi\BuktiTransaksiResource\Pages;
use App\Models\BuktiTransaksi;
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

class BuktiTransaksiResource extends Resource
{
    protected static ?string $model = BuktiTransaksi::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\UnitEnum $navigationGroup = 'Transaksi';

    protected static ?string $modelLabel = 'Bukti Transaksi';

    protected static ?string $pluralModelLabel = 'Bukti Transaksi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

                Select::make('spj_id')->label('SPJ')->relationship('spj', 'nomor_spj')->required()->searchable()->preload(),
                Select::make('rekanan_id')->label('Rekanan')->relationship('rekanan', 'nama')->searchable()->preload(),
                TextInput::make('jenis')->required()->maxLength(255),
                TextInput::make('nomor')->maxLength(255),
                DatePicker::make('tanggal'),
                TextInput::make('nominal')->numeric()->prefix('Rp')->default(0),
                Textarea::make('keterangan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('spj.nomor_spj')->label('SPJ')->searchable()->sortable(),
                TextColumn::make('jenis')->searchable()->sortable(),
                TextColumn::make('rekanan.nama')->label('Rekanan')->searchable(),
                TextColumn::make('nominal')->money('IDR')->sortable(),
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
            'index' => Pages\ListBuktiTransaksis::route('/'),
            'create' => Pages\CreateBuktiTransaksi::route('/create'),
            'edit' => Pages\EditBuktiTransaksi::route('/{record}/edit'),
        ];
    }
}
