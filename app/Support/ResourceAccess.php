<?php

namespace App\Support;

use App\Enums\SpjStatus;
use App\Models\Spj;
use App\Models\User;

class ResourceAccess
{
    public static function user(): ?User
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user;
    }

    public static function isAdmin(?User $user = null): bool
    {
        return ($user ?? self::user())?->hasRole('Administrator') ?? false;
    }

    public static function canManageMaster(?User $user = null): bool
    {
        return self::isAdmin($user);
    }

    public static function canManageUsers(?User $user = null): bool
    {
        return self::isAdmin($user);
    }

    public static function canViewTransactions(?User $user = null): bool
    {
        return ($user ?? self::user())?->hasAnyRole([
            'Administrator',
            'Bidang',
            'PPTK',
            'PPK',
            'Bendahara',
            'PA/KPA',
            'Auditor',
        ]) ?? false;
    }

    public static function canCreateTransactions(?User $user = null): bool
    {
        return ($user ?? self::user())?->hasAnyRole(['Administrator', 'Bidang']) ?? false;
    }

    public static function canVerifySpj(Spj $spj, ?User $user = null): bool
    {
        $user ??= self::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Administrator')) {
            return $spj->status !== SpjStatus::Arsip;
        }

        return match ($spj->status) {
            SpjStatus::Draft => $user->hasRole('Bidang'),
            SpjStatus::VerifikasiPptk => $user->hasRole('PPTK'),
            SpjStatus::VerifikasiBendahara => $user->hasRole('Bendahara'),
            SpjStatus::PersetujuanPaKpa => $user->hasRole('PA/KPA'),
            default => false,
        };
    }

    public static function canViewSpj(Spj $spj, ?User $user = null): bool
    {
        $user ??= self::user();

        if (! $user) {
            return false;
        }

        if ($user->hasAnyRole(['Administrator', 'PPTK', 'PPK', 'Bendahara', 'PA/KPA', 'Auditor'])) {
            return true;
        }

        return $user->hasRole('Bidang') && $user->bidangs()->whereKey($spj->bidang_id)->exists();
    }

    public static function nextSpjStatus(Spj $spj): ?SpjStatus
    {
        return match ($spj->status) {
            SpjStatus::Draft => SpjStatus::VerifikasiPptk,
            SpjStatus::VerifikasiPptk => SpjStatus::VerifikasiBendahara,
            SpjStatus::VerifikasiBendahara => SpjStatus::PersetujuanPaKpa,
            SpjStatus::PersetujuanPaKpa => SpjStatus::Final,
            SpjStatus::Final => SpjStatus::Arsip,
            default => null,
        };
    }
}
