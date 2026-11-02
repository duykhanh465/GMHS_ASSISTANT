<?php

namespace App\Services\Calculators;

class WeightCalculator
{
    public function bmi(float $weightKg, float $heightCm): float
    {
        $heightM = $heightCm / 100;
        return round($weightKg / ($heightM * $heightM), 1);
    }

    public function pbw(string $gender, float $heightCm): float
    {
        if ($gender === 'male') {
            return round(50 + 0.91 * ($heightCm - 152.4), 1);
        }
        return round(45.5 + 0.91 * ($heightCm - 152.4), 1);
    }

    public function ibw(string $gender, float $heightCm): float
    {
        return $this->pbw($gender, $heightCm);
    }

    public function adjbw(float $pbw, float $actualWeight): float
    {
        if ($actualWeight <= $pbw * 1.2) {
            return round($actualWeight, 1);
        }
        return round($pbw + 0.4 * ($actualWeight - $pbw), 1);
    }
}
