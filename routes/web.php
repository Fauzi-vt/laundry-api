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
    Route::get('/dashboard', function() {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.dashboard');
    })->name('dashboard');

    // ── Halaman User (tiap menu halaman terpisah) ────────────────────────────
    Route::middleware('role:user')->prefix('user')->group(function () {
        Route::get('/',           [\App\Http\Controllers\Web\UserDashboardController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/layanan',    [\App\Http\Controllers\Web\UserDashboardController::class, 'layanan'])->name('user.layanan');
        Route::get('/order',      [\App\Http\Controllers\Web\UserDashboardController::class, 'order'])->name('user.order');
        Route::get('/pembayaran', [\App\Http\Controllers\Web\UserDashboardController::class, 'pembayaran'])->name('user.pembayaran');
        Route::get('/status',     [\App\Http\Controllers\Web\UserDashboardController::class, 'status'])->name('user.status');
        Route::get('/order/{id}', [\App\Http\Controllers\Web\UserDashboardController::class, 'show'])->name('user.show');
    });

    // Hanya pelanggan (user) yang bisa membuat order
    Route::middleware('role:user')->group(function () {
        Route::post('/orders', [\App\Http\Controllers\Web\UserOrderController::class, 'store'])->name('orders.store');
    });

    // Hanya admin yang bisa update status transaksi & buat transaksi baru
    Route::middleware('role:admin')->group(function () {
        Route::patch('/transactions/{id}/status', [\App\Http\Controllers\Web\TransactionStatusController::class, 'update'])->name('transactions.status.update');
        Route::post('/orders/admin', [\App\Http\Controllers\Web\AdminOrderController::class, 'store'])->name('orders.admin.store');

        // 1. Dashboard (Ganti nama 'Monitoring' menjadi Dashboard)
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // 2. Pelanggan (CRUD Pelanggan)
        Route::get('/admin/customers',  [DashboardController::class, 'customers'])->name('admin.customers.index');
        Route::post  ('/admin/customers',           [\App\Http\Controllers\Web\CustomerController::class, 'store'])   ->name('admin.customers.store');
        Route::put   ('/admin/customers/{id}',      [\App\Http\Controllers\Web\CustomerController::class, 'update'])  ->name('admin.customers.update');
        Route::delete('/admin/customers/bulk',      [\App\Http\Controllers\Web\CustomerController::class, 'bulkDestroy'])->name('admin.customers.bulkDestroy');
        Route::delete('/admin/customers/{id}',      [\App\Http\Controllers\Web\CustomerController::class, 'destroy']) ->name('admin.customers.destroy');
        Route::get   ('/admin/customers/{id}/trx',  [\App\Http\Controllers\Web\CustomerController::class, 'transactions'])->name('admin.customers.trx');

        // 3. Pesanan & Transaksi (Menu baru)
        Route::get('/admin/orders', [\App\Http\Controllers\Web\AdminTransactionController::class, 'index'])->name('admin.orders.index');
        Route::post('/admin/orders/{id}/accept', [\App\Http\Controllers\Web\AdminTransactionController::class, 'accept'])->name('admin.orders.accept');

        // 4. Layanan & Harga (Gabungkan 'Layanan' dan 'Kategori')
        Route::get('/admin/services',   [DashboardController::class, 'services'])->name('admin.services.index');
        Route::post  ('/admin/services',            [\App\Http\Controllers\Web\ServiceController::class, 'store'])    ->name('admin.services.store');
        Route::put   ('/admin/services/{id}',       [\App\Http\Controllers\Web\ServiceController::class, 'update'])   ->name('admin.services.update');
        Route::delete('/admin/services/{id}',       [\App\Http\Controllers\Web\ServiceController::class, 'destroy'])  ->name('admin.services.destroy');
        
        // CRUD Kategori
        Route::get   ('/admin/categories',          [\App\Http\Controllers\Web\CategoryController::class, 'index'])   ->name('admin.categories.index');
        Route::post  ('/admin/categories',          [\App\Http\Controllers\Web\CategoryController::class, 'store'])   ->name('admin.categories.store');
        Route::put   ('/admin/categories/{id}',      [\App\Http\Controllers\Web\CategoryController::class, 'update'])  ->name('admin.categories.update');
        Route::delete('/admin/categories/{id}',      [\App\Http\Controllers\Web\CategoryController::class, 'destroy']) ->name('admin.categories.destroy');

        // CRUD Akun Pembayaran
        Route::get   ('/admin/payment-accounts',          [\App\Http\Controllers\Web\PaymentAccountController::class, 'index'])   ->name('admin.payment-accounts.index');
        Route::post  ('/admin/payment-accounts',          [\App\Http\Controllers\Web\PaymentAccountController::class, 'store'])   ->name('admin.payment-accounts.store');
        Route::put   ('/admin/payment-accounts/{id}',      [\App\Http\Controllers\Web\PaymentAccountController::class, 'update'])  ->name('admin.payment-accounts.update');
        Route::delete('/admin/payment-accounts/{id}',      [\App\Http\Controllers\Web\PaymentAccountController::class, 'destroy']) ->name('admin.payment-accounts.destroy');

        // 5. Antar-Jemput (Menu baru)
        Route::get('/admin/shuttles', [\App\Http\Controllers\Web\AdminShuttleController::class, 'index'])->name('admin.shuttles.index');

        // 6. Pembayaran (Menu baru)
        Route::get('/admin/payments', [\App\Http\Controllers\Web\AdminPaymentController::class, 'index'])->name('admin.payments.index');
        Route::post('/admin/payments/{id}/verify', [\App\Http\Controllers\Web\AdminPaymentController::class, 'verify'])->name('admin.payments.verify');

        // 7. Laporan
        Route::get('/admin/reports',    [DashboardController::class, 'reports'])->name('admin.reports.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
