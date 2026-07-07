<?php

namespace App\Filament\Resources\Concerns;

use App\Support\ResourceAccess;
use Illuminate\Database\Eloquent\Model;

trait TransactionResourceAccess
{
    public static function canViewAny(): bool
    {
        return ResourceAccess::canViewTransactions();
    }

    public static function canCreate(): bool
    {
        return ResourceAccess::canCreateTransactions();
    }

    public static function canEdit(Model $record): bool
    {
        return ResourceAccess::canCreateTransactions();
    }

    public static function canDelete(Model $record): bool
    {
        return ResourceAccess::canCreateTransactions();
    }
}
