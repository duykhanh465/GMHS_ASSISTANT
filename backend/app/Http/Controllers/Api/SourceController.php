<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\JsonResponse;

class SourceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Source::orderBy('priority')->orderBy('name')->get());
    }
}
