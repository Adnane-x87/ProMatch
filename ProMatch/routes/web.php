<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// GET Routes for pages
Route::get('/booking', function () {
    return view('booking');
})->name('booking');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Authentication Routes
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
Route::post('/booking', [ReservationController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::post('/contact', function (Request $request) {
    // Simple placeholder for contact form submission
    return back()->with('success', 'Votre message a été envoyé avec succès !');
});

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
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::get('/reservations', function () {
        return view('admin.reservations');
    });
    Route::get('/validations', function () {
        return view('admin.validations');
    });
    Route::get('/clients', function () {
        return view('admin.clients');
    });

    // Dashboard Data APIs (Session Authenticated)
    Route::get('/api/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    Route::get('/api/planning', [App\Http\Controllers\Api\ReservationController::class, 'planning']);
});