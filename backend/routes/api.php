<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('patients', \App\Http\Controllers\Api\PatientController::class);
    Route::post('/patients/{patient}/calculate', [\App\Http\Controllers\Api\CalculationController::class, 'calculate']);
    Route::get('/patients/{patient}/calculations', [\App\Http\Controllers\Api\CalculationController::class, 'history']);
});
