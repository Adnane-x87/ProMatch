<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PublicFieldController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\CniController;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/public-fields', [PublicFieldController::class, 'index']);
Route::get('/public-fields/{id}', [PublicFieldController::class, 'show']);

// UC6: Guest reservation (no auth required)
Route::post('/reservations', [ReservationController::class, 'store']);
Route::get('/available-slots', [ReservationController::class, 'availableSlots']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/cni/upload', [CniController::class, 'upload']);
    Route::put('/reservations/{id}/validate', [ReservationController::class, 'validateReservation']);
    Route::get('/planning', [ReservationController::class, 'planning']);
    Route::apiResource('fields', FieldController::class);
    Route::post('/fields/{id}/slots', [FieldController::class, 'addSlots']);
});