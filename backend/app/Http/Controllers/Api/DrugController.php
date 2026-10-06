<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Drug::with(['category', 'dilutions', 'sources'])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderBy('name')->paginate(50));
    }

    public function show(Drug $drug): JsonResponse
    {
        $drug->load(['category', 'dilutions', 'sources']);
        return response()->json($drug);
    }
}
