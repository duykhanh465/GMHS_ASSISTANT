<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SourceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Source::orderBy('priority')->orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $source = Source::create($this->rules($request));

        return response()->json($source, 201);
    }

    public function update(Request $request, Source $source): JsonResponse
    {
        $source->update($this->rules($request, true));

        return response()->json($source->fresh());
    }

    public function destroy(Source $source): JsonResponse
    {
        $source->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function rules(Request $request, bool $update = false): array
    {
        $req = $update ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$req, 'string', 'max:255'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'priority' => ['nullable', Rule::in(['A', 'B', 'C'])],
            'notes' => ['nullable', 'string'],
            'source_updated_at' => ['nullable', 'date'],
        ]);
    }
}
