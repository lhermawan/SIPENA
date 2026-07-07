<?php

use App\Models\Spj;
use App\Support\ResourceAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/spjs/{spj}/pdf', function (Spj $spj) {
    abort_unless(auth()->check() && ResourceAccess::canViewSpj($spj), 403);

    $spj->load([
        'bidang',
        'program',
        'kegiatan',
        'subKegiatan',
        'rekeningBelanja',
        'pptk',
        'ppk',
        'bendahara',
        'paKpa',
        'items.satuan',
        'buktiTransaksis.rekanan',
    ]);

    activity()
        ->causedBy(auth()->user())
        ->performedOn($spj)
        ->event('exported')
        ->log('Mengunduh PDF SPJ '.$spj->nomor_spj);

    return Pdf::loadView('pdf.spj', ['spj' => $spj])
        ->setPaper('a4')
        ->download('SPJ-'.str_replace(['/', '\\'], '-', $spj->nomor_spj).'.pdf');
})->middleware('auth')->name('spjs.pdf');
