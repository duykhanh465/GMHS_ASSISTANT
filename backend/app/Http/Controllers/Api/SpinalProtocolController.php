<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpinalProtocol;
use App\Support\Localize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpinalProtocolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rows = SpinalProtocol::with('sources')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $locale = $request->string('locale')->toString() ?: null;

        return response()->json(Localize::collection($rows, $locale));
    }
}
