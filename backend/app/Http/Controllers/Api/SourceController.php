<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Support\Localize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rows = Source::orderBy('priority')->orderBy('name')->get();
        $locale = $request->string('locale')->toString() ?: null;

        return response()->json(Localize::collection($rows, $locale));
    }
}
