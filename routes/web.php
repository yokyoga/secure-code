<?php

use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Arrivals Management API',
    ]);
});

// Route::post('/login', [AuthController::class, 'login']);

// Route::prefix('/arrivals')->group(function () {
//     Route::post('/', [ArrivalController::class, 'store']);
//     Route::get('/', [ArrivalController::class, 'index']);
//     Route::get('/{id}', [ArrivalController::class, 'show']);
//     Route::patch('/{id}/verify', [ArrivalController::class, 'verify']);
//     Route::patch('/{id}/reject', [ArrivalController::class, 'reject']);
// });
