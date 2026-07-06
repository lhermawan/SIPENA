<?php

namespace App\Filament\Resources\Master\RekeningBelanjaResource\Pages;

use App\Filament\Resources\Master\RekeningBelanjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekeningBelanjas extends ListRecords
{
    protected static string $resource = RekeningBelanjaResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
