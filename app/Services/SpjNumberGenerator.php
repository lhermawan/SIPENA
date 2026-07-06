<?php

namespace App\Services;

use App\Models\Spj;
use Carbon\CarbonInterface;

class SpjNumberGenerator
{
    /** Generate nomor seperti 001/SPJ/BPKAD/VII/2026. */
    public function generate(CarbonInterface $date, string $unit = 'BPKAD'): string
    {
        $sequence = Spj::query()->whereYear('tanggal', $date->year)->count() + 1;

        return sprintf('%03d/SPJ/%s/%s/%d', $sequence, strtoupper($unit), $this->roman($date->month), $date->year);
    }

    private function roman(int $month): string
    {
        return [1=>'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$month];
    }
}
