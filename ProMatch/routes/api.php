<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PublicFieldController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\CniController;
use App\Http\Controllers\Api\DashboardController;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/public-fields', [PublicFieldController::class, 'index']);
Route::get('/public-fields/{id}', [PublicFieldController::class, 'show']);

// UC6: Guest reservation (no auth required)
Route::post('/reservations', [ReservationController::class, 'store']);
Route::get('/available-slots', [ReservationController::class, 'availableSlots']);

// Public Dashboard Access (for development/ProMatch-App)
Route::get('/planning', [ReservationController::class, 'planning']);
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::put('/reservations/{id}/validate', [ReservationController::class, 'validateReservation']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/cni/upload', [CniController::class, 'upload']);

    Route::apiResource('fields', FieldController::class);
    Route::post('/fields/{id}/slots', [FieldController::class, 'addSlots']);
    
    // Dashboard Routes (Write operations remain protected)
    Route::get('/dashboard/slots', [DashboardController::class, 'getSlots']);
    Route::post('/dashboard/slots', [DashboardController::class, 'storeSlot']);
    Route::put('/dashboard/slots/{id}', [DashboardController::class, 'updateSlot']);
    Route::delete('/dashboard/slots/{id}', [DashboardController::class, 'destroySlot']);
});