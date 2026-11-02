<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VentilatorPreset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VentilatorPresetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = VentilatorPreset::with('sources')->where('is_active', true);

        if ($request->filled('scenario')) {
            $query->where('clinical_scenario', $request->string('scenario'));
        }

        return response()->json($query->orderBy('sort_order')->get());
    }
}
