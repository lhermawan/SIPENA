<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('sipena:about', function (): void {
    $this->info('SIPENA - Sistem Informasi Surat Pertanggungjawaban Anggaran');
})->purpose('Display SIPENA application information');
