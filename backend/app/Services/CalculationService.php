<?php

namespace App\Services;

use App\Models\Patient;
use App\Services\Calculators\FluidCalculator;
use App\Services\Calculators\VentilatorCalculator;
use App\Services\Calculators\WeightCalculator;

class CalculationService
{
    public const ENGINE_VERSION = '1.0.0';

    public function calculate(Patient $patient): array
    {
        // 1. Gọi 3 máy Phase 3
        $weight = new WeightCalculator();
        $fluid = new FluidCalculator();
        $vent = new VentilatorCalculator();

        $bmi   = $weight->bmi((float) $patient->weight_kg, (float) $patient->height_cm);
        $pbw   = $weight->pbw($patient->gender, (float) $patient->height_cm);
        $ibw   = $weight->ibw($patient->gender, (float) $patient->height_cm);
        $adjbw = $weight->adjbw($pbw, (float) $patient->weight_kg);
        $dosingWeight = $adjbw; // MVP: dùng AdjBW làm cân tính liều
        $maintenanceFluid = $fluid->maintenanceFluid((float) $patient->weight_kg);

        [$vtMin, $vtMax] = $vent->vtRange($pbw, $patient->respiratory_condition);
        $rr   = $vent->rr($patient->respiratory_condition);
        $peep = $vent->peep($patient->respiratory_condition);
        $fio2 = $vent->fio2($patient->respiratory_condition);

        // 2. Chụp snapshot lúc tính (để sau này sửa bệnh nhân không mất)
        $inputSnapshot = [
            'code' => $patient->code,
            'age' => $patient->age,
            'gender' => $patient->gender,
            'height_cm' => (float) $patient->height_cm,
            'weight_kg' => (float) $patient->weight_kg,
            'asa_class' => $patient->asa_class,
            'respiratory_condition' => $patient->respiratory_condition,
        ];

        // 3. Gom kết quả máy thở
        $ventilatorResult = [
            'vt_min' => $vtMin,
            'vt_max' => $vtMax,
            'rr' => $rr,
            'peep' => $peep,
            'fio2' => $fio2,
        ];

        // 4. Trả về đủ để lưu vào patient_calculations
        return [
            'input_snapshot' => $inputSnapshot,
            'bmi' => $bmi,
            'ibw' => $ibw,
            'pbw' => $pbw,
            'adjbw' => $adjbw,
            'dosing_weight' => $dosingWeight,
            'maintenance_fluid' => $maintenanceFluid,
            'ventilator_result' => $ventilatorResult,
            'drug_result' => null,        // Phase sau: tính liều thuốc
            'vasopressor_result' => null, // Phase sau: tính vận mạch
            'engine_version' => self::ENGINE_VERSION,
        ];
    }
}
