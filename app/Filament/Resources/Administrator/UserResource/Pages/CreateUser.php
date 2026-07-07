<?php

namespace App\Filament\Resources\Administrator\UserResource\Pages;

use App\Filament\Resources\Administrator\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
