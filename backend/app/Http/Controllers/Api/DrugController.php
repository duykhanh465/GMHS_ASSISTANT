<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Support\Localize;
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

        $locale = $request->string('locale')->toString() ?: null;
        $page = $query->orderBy('name')->paginate(50);
        $page->getCollection()->transform(fn (Drug $d) => $this->localize($d, $locale));

        return response()->json($page);
    }

    public function show(Request $request, Drug $drug): JsonResponse
    {
        $drug->load(['category', 'dilutions', 'sources']);
        $locale = $request->string('locale')->toString() ?: null;

        return response()->json($this->localize($drug, $locale));
    }

    private function localize(Drug $drug, ?string $locale): array
    {
        $row = Localize::apply($drug, $locale);
        if ($drug->relationLoaded('category') && $drug->category) {
            $row['category'] = Localize::apply($drug->category, $locale);
        }

        return $row;
    }
}
