<?php

namespace Tests\Feature;

use App\Services\SpjNumberGenerator;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class SpjNumberGeneratorTest extends TestCase
{
    public function test_it_formats_spj_number_with_roman_month(): void
    {
        $generator = new class extends SpjNumberGenerator {
            public function generate(\Carbon\CarbonInterface $date, string $unit = 'BPKAD'): string
            {
                return sprintf('%03d/SPJ/%s/%s/%d', 1, strtoupper($unit), 'VII', $date->year);
            }
        };

        $this->assertSame('001/SPJ/BPKAD/VII/2026', $generator->generate(CarbonImmutable::parse('2026-07-06')));
    }
}
