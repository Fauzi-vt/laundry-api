<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserOrderController;
use App\Http\Controllers\Api\ProfileController;

// ── Public routes ──────────────────────────────────────────────────────────
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/track/{invoice_code}', [TransactionController::class, 'track']);

// ── Payment Accounts (Public — untuk ditampilkan di mobile app) ────────────
Route::get('/payment-accounts', function () {
    $accounts = \App\Models\PaymentAccount::where('is_active', true)
        ->orderByRaw("FIELD(type, 'cash', 'bank', 'ewallet')")
        ->orderBy('provider_name')
        ->get(['id', 'type', 'provider_name', 'provider_code', 'account_number', 'account_name']);
    return response()->json(['data' => $accounts]);
});

Route::get('/services/image/{filename}', function ($filename) {
    $path = public_path('images/services/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

// ── Authenticated routes ───────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // User info & profile
    Route::get('/user',    fn(Request $r) => $r->user());
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/user/fcm-token', [ProfileController::class, 'updateFcmToken']);

    // Layanan — semua user login bisa lihat
    Route::get('/services', [ServiceController::class, 'index']);

    // Transaksi — user lihat milik sendiri
    Route::get('/transactions', [TransactionController::class, 'index']);

    // Order baru — hanya user biasa
    Route::middleware('role:user')->group(function () {
        Route::post('/orders', [UserOrderController::class, 'store']);
        Route::post('/transactions/{id}/payment-proof', [UserOrderController::class, 'uploadPaymentProof']);
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::post('/transactions',            [TransactionController::class, 'store']);
        Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);

        Route::get('/report', function () {
            $totalRevenue = \App\Models\Transaction::where('status', 'selesai')->sum('total_price');
            $transactions = \App\Models\Transaction::with(['user', 'details.service'])
                ->where('status', 'selesai')->get();

            return response()->json([
                'message' => 'Success',
                'data'    => ['total_revenue' => $totalRevenue, 'transactions' => $transactions],
            ]);
        });
    });
});
