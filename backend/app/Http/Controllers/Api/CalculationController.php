<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\CalculationService;
use Illuminate\Http\JsonResponse;

class CalculationController extends Controller
{
    public function calculate(Patient $patient, CalculationService $service): JsonResponse
    {
        // Kiểm tra quyền: chỉ chủ case hoặc admin mới được tính
        $this->authorize('view', $patient);

        $data = $service->calculate($patient);

        $calculation = $patient->calculations()->create([
            'user_id' => auth()->id(),
            'input_snapshot' => $data['input_snapshot'],
            'bmi' => $data['bmi'],
            'ibw' => $data['ibw'],
            'pbw' => $data['pbw'],
            'adjbw' => $data['adjbw'],
            'dosing_weight' => $data['dosing_weight'],
            'maintenance_fluid' => $data['maintenance_fluid'],
            'ventilator_result' => $data['ventilator_result'],
            'drug_result' => $data['drug_result'],
            'vasopressor_result' => $data['vasopressor_result'],
            'engine_version' => $data['engine_version'],
            'calculated_at' => now(),
        ]);

        return response()->json($calculation, 201);
    }

    public function history(Patient $patient): JsonResponse
    {
        $this->authorize('view', $patient);

        $history = $patient->calculations()
            ->latest('calculated_at')
            ->paginate(20);

        return response()->json($history);
    }
}
