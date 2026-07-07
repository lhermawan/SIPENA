<?php

namespace App\Filament\Resources\Administrator\UserResource\Pages;

use App\Filament\Resources\Administrator\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
