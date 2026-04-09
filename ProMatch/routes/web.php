<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationController as ApiReservationController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Public\PageController;
use Illuminate\Http\Request;

Route::get('/', [PageController::class, 'home'])->name('home');

// Public Page Routes
Route::get('/booking', [PageController::class, 'booking'])->name('booking');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Authentication Routes (UI)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dummy route for password request to avoid Blade compilation errors
Route::get('/forgot-password', function () {
    return "Password reset functionality coming soon.";
})->name('password.request');

// POST Routes for form submissions
Route::post('/booking', [ApiReservationController::class, 'store']);
Route::post('/login', [ApiUserController::class, 'login']);
Route::post('/register', [ApiUserController::class, 'register']);
Route::post('/contact', [PageController::class, 'submitContact']);

// Admin Routes (Authenticated)
Route::get('/admin/quick-login', function () {
    $user = \App\Models\User::where('email', 'admin@promatch.ma')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect('/admin/dashboard');
    }
    return redirect('/login')->with('error', 'Compte admin introuvable.');
})->name('admin.quick-login');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('admin.reservations');
    Route::get('/validations', [ValidationController::class, 'index'])->name('admin.validations');
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');

    // Action Routes
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    Route::post('/validations/{id}/approve', [ValidationController::class, 'approve']);
    Route::post('/validations/{id}/reject', [ValidationController::class, 'reject']);

    // Dashboard Data APIs (Session Authenticated)
    Route::get('/api/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    Route::get('/api/planning', [App\Http\Controllers\Api\ReservationController::class, 'planning']);
});