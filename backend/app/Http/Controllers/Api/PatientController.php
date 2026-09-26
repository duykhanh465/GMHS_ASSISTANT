<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $query = $user->isAdmin()
            ? Patient::query()
            : Patient::where('user_id', $user->id);

        return response()->json($query->latest()->paginate(20));
    }

    public function store(PatientRequest $request): JsonResponse
    {
        $patient = Patient::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return response()->json($patient, 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        $this->authorize('view', $patient);
        return response()->json($patient);
    }

    public function update(PatientRequest $request, Patient $patient): JsonResponse
    {
        $this->authorize('update', $patient);
        $patient->update($request->validated());
        return response()->json($patient);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $this->authorize('delete', $patient);
        $patient->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
