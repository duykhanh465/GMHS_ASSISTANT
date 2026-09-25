<?php

namespace App\Services\Calculators;

class VentilatorCalculator
{
    public function vtRange(float $pbw, string $scenario): array
    {
        return match ($scenario) {
            'routine' => [round($pbw * 6), round($pbw * 7)],
            'asthma_copd' => [round($pbw * 6), round($pbw * 8)],
            'ards' => [round($pbw * 4), round($pbw * 6)],
            'co2_endoscopy' => [round($pbw * 6), round($pbw * 7)],
            default => [round($pbw * 6), round($pbw * 7)],
        };
    }

    public function rr(string $scenario): int
    {
        return match ($scenario) {
            'routine' => 14,
            'asthma_copd' => 8,
            'ards' => 16,
            'co2_endoscopy' => 14,
            default => 14,
        };
    }

    public function peep(string $scenario): int
    {
        return 5;
    }

    public function fio2(string $scenario): string
    {
        return '40-60%';
    }
}
