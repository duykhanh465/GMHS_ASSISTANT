<?php

namespace App\Services\Calculators;

class FluidCalculator
{
    public function maintenanceFluid(float $weightKg): float
    {
        if ($weightKg <= 10) {
            return round($weightKg * 4, 0);
        }
        if ($weightKg <= 20) {
            return round(40 + ($weightKg - 10) * 2, 0);
        }
        return round(60 + ($weightKg - 20) * 1, 0);
    }
}
