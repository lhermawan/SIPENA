<?php

namespace App\Filament\Resources\Master\KegiatanResource\Pages;

use App\Filament\Resources\Master\KegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKegiatans extends ListRecords
{
    protected static string $resource = KegiatanResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
