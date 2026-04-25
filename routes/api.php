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

// ── Authenticated routes ───────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // User info & profile
    Route::get('/user',    fn(Request $r) => $r->user());
    Route::put('/profile', [ProfileController::class, 'update']);

    // Layanan — semua user login bisa lihat
    Route::get('/services', [ServiceController::class, 'index']);

    // Transaksi — user lihat milik sendiri
    Route::get('/transactions', [TransactionController::class, 'index']);

    // Order baru — hanya user biasa
    Route::middleware('role:user')->group(function () {
        Route::post('/orders', [UserOrderController::class, 'store']);
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
