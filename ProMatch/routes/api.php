<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PublicFieldController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\CniController;

/*
|--------------------------------------------------------------------------
| Public Routes (No login required)
|--------------------------------------------------------------------------
*/
// UC1: Auth
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// UC5: Users search for fields
Route::get('/public-fields', [PublicFieldController::class, 'index']);
Route::get('/public-fields/{id}', [PublicFieldController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Get current user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [UserController::class, 'logout']);

    // UC7: Upload CNI
    Route::post('/cni/upload', [CniController::class, 'upload']);

    // UC6: User makes a reservation
    Route::post('/reservations', [ReservationController::class, 'store']);

    // UC4: Admin validates reservation
    Route::put('/reservations/{id}/validate', [ReservationController::class, 'validateReservation']);
    
    // UC9: Staff/Admin view planning
    Route::get('/planning', [ReservationController::class, 'planning']);

    // UC2 & UC3: Admin manages fields and slots (CRUD)
    Route::apiResource('fields', FieldController::class);
    Route::post('/fields/{id}/slots', [FieldController::class, 'addSlots']);
});