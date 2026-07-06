<?php

namespace App\Filament\Resources\Transaksi\SpjResource\Pages;

use App\Filament\Resources\Transaksi\SpjResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpjs extends ListRecords
{
    protected static string $resource = SpjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
