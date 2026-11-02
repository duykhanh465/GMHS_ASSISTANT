<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrugController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Drug::with('category')->orderBy('name')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $drug = Drug::create($this->rules($request));

        return response()->json($drug->load('category'), 201);
    }

    public function update(Request $request, Drug $drug): JsonResponse
    {
        $drug->update($this->rules($request, true));

        return response()->json($drug->fresh('category'));
    }

    public function destroy(Drug $drug): JsonResponse
    {
        $drug->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function rules(Request $request, bool $update = false): array
    {
        $req = $update ? 'sometimes' : 'required';

        return $request->validate([
            'drug_category_id' => [$req, 'exists:drug_categories,id'],
            'name' => [$req, 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'dose_text' => ['nullable', 'string', 'max:255'],
            'dose_min_per_kg' => ['nullable', 'numeric'],
            'dose_max_per_kg' => ['nullable', 'numeric'],
            'weight_basis' => ['nullable', Rule::in(['actual', 'pbw', 'ibw', 'adjbw', 'fixed'])],
            'preparation' => ['nullable', 'string', 'max:255'],
            'indication' => ['nullable', 'string', 'max:255'],
            'timing' => ['nullable', 'string', 'max:255'],
            'monitoring' => ['nullable', 'string', 'max:255'],
            'warning' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
