<?php

namespace App\Filament\Resources\Master\SubKegiatanResource\Pages;

use App\Filament\Resources\Master\SubKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubKegiatans extends ListRecords
{
    protected static string $resource = SubKegiatanResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
