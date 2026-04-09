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

    // Hanya pelanggan (user) yang bisa membuat order
    Route::middleware('role:user')->group(function () {
        Route::post('/orders', [\App\Http\Controllers\Web\UserOrderController::class, 'store'])->name('orders.store');
    });

    // Hanya admin yang bisa update status transaksi & buat transaksi baru
    Route::middleware('role:admin')->group(function () {
        Route::patch('/transactions/{id}/status', [\App\Http\Controllers\Web\TransactionStatusController::class, 'update'])->name('transactions.status.update');
        Route::post('/orders/admin', [\App\Http\Controllers\Web\AdminOrderController::class, 'store'])->name('orders.admin.store');

        // CRUD Pelanggan
        Route::post  ('/admin/customers',           [\App\Http\Controllers\Web\CustomerController::class, 'store'])   ->name('admin.customers.store');
        Route::put   ('/admin/customers/{id}',      [\App\Http\Controllers\Web\CustomerController::class, 'update'])  ->name('admin.customers.update');
        Route::delete('/admin/customers/{id}',      [\App\Http\Controllers\Web\CustomerController::class, 'destroy']) ->name('admin.customers.destroy');
        Route::get   ('/admin/customers/{id}/trx',  [\App\Http\Controllers\Web\CustomerController::class, 'transactions'])->name('admin.customers.trx');

        // CRUD Layanan
        Route::post  ('/admin/services',            [\App\Http\Controllers\Web\ServiceController::class, 'store'])    ->name('admin.services.store');
        Route::put   ('/admin/services/{id}',       [\App\Http\Controllers\Web\ServiceController::class, 'update'])   ->name('admin.services.update');
        Route::delete('/admin/services/{id}',       [\App\Http\Controllers\Web\ServiceController::class, 'destroy'])  ->name('admin.services.destroy');
    });

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

// Admin Package Management — diproteksi oleh auth + role:admin
Route::middleware(['auth', 'role:admin'])->prefix('admin/packages')->group(function () {
    Route::get('/', [PackageController::class, 'index']);
    Route::post('/', [PackageController::class, 'store']);
});
