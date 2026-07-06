<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Administrator', 'Bendahara', 'PPTK', 'PPK', 'PA/KPA', 'Auditor'] as $role) {
            Role::findOrCreate($role);
        }

        foreach (['Paket', 'Unit', 'Orang', 'Hari', 'Bulan', 'Lembar'] as $nama) {
            Satuan::firstOrCreate(['nama' => $nama]);
        }
    }
}
