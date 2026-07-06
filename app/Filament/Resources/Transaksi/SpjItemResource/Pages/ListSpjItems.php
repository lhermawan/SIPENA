<?php

namespace App\Filament\Resources\Transaksi\SpjItemResource\Pages;

use App\Filament\Resources\Transaksi\SpjItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpjItems extends ListRecords
{
    protected static string $resource = SpjItemResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
