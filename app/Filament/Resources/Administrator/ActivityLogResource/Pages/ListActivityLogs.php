<?php

namespace App\Filament\Resources\Administrator\ActivityLogResource\Pages;

use App\Filament\Resources\Administrator\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;
}
