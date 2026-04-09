<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/orders', [\App\Http\Controllers\Web\UserOrderController::class, 'store'])->name('orders.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Web\PackageController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Package Management (Could normally be protected by an admin middleware)
Route::prefix('admin/packages')->group(function () {
    Route::get('/', [PackageController::class, 'index']);
    Route::post('/', [PackageController::class, 'store']);
});
