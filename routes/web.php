<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Guest Routes (Only accessible if NOT logged in)
Route::middleware('guest')->group(function () {

    // REGISTER
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // LOGIN
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Authenticated Routes (Only accessible if logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & App Logic
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('shipments', ShipmentController::class);

    Route::patch('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])
        ->name('shipments.updateStatus');
});
