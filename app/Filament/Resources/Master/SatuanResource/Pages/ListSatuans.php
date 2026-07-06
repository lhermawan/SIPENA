<?php

namespace App\Filament\Resources\Master\SatuanResource\Pages;

use App\Filament\Resources\Master\SatuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSatuans extends ListRecords
{
    protected static string $resource = SatuanResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
