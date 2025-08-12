<?php

use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['jwt', 'throttle:60,1'])->prefix('/arrival')->group(function () {
    Route::get('/', [ArrivalController::class, 'index']);
    Route::post('/', [ArrivalController::class, 'store']);
    Route::get('/{arrival}', [ArrivalController::class, 'show']);
    Route::patch('/{arrival}/approve', [ArrivalController::class, 'approve'])->middleware('approver');
    Route::patch('/{arrival}/reject', [ArrivalController::class, 'reject'])->middleware('approver');
});