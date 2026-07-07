<?php

namespace App\Filament\Resources\Concerns;

use App\Support\ResourceAccess;
use Illuminate\Database\Eloquent\Model;

trait AdminOnlyResourceAccess
{
    public static function canViewAny(): bool
    {
        return ResourceAccess::canManageMaster();
    }

    public static function canCreate(): bool
    {
        return ResourceAccess::canManageMaster();
    }

    public static function canEdit(Model $record): bool
    {
        return ResourceAccess::canManageMaster();
    }

    public static function canDelete(Model $record): bool
    {
        return ResourceAccess::canManageMaster();
    }
}
