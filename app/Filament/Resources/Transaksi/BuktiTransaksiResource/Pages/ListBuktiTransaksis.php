<?php

namespace App\Filament\Resources\Transaksi\BuktiTransaksiResource\Pages;

use App\Filament\Resources\Transaksi\BuktiTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuktiTransaksis extends ListRecords
{
    protected static string $resource = BuktiTransaksiResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
