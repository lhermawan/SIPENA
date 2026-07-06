<?php

namespace App\Enums;

enum SpjStatus: string
{
    case Draft = 'draft';
    case VerifikasiPptk = 'verifikasi_pptk';
    case VerifikasiBendahara = 'verifikasi_bendahara';
    case PersetujuanPaKpa = 'persetujuan_pa_kpa';
    case Final = 'final';
    case Arsip = 'arsip';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::VerifikasiPptk => 'Verifikasi PPTK',
            self::VerifikasiBendahara => 'Verifikasi Bendahara',
            self::PersetujuanPaKpa => 'Persetujuan PA/KPA',
            self::Final => 'Final',
            self::Arsip => 'Arsip',
        };
    }
}
