<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Masuk ke SIPENA';
    }

    public function getSubheading(): string
    {
        return 'Gunakan akun yang sudah terdaftar untuk mengelola SPJ anggaran.';
    }
}