<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/track/{invoice_code}', [TransactionController::class, 'track']);

Route::middleware('auth:sanctum')->group(function () {
    // Endpoint untuk semua user yang sudah login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Transaksi milik sendiri (user melihat transaksinya sendiri, admin lihat semua)
    Route::get('/transactions', [TransactionController::class, 'index']);

    // Hanya admin yang bisa membuat transaksi, update status, dan melihat laporan
    Route::middleware('role:admin')->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);

        Route::get('/report', function (Request $request) {
            $totalRevenue = \App\Models\Transaction::where('status', 'selesai')->sum('total_price');
            $transactions = \App\Models\Transaction::with(['user', 'details.service'])
                ->where('status', 'selesai')
                ->get();

            return response()->json([
                'message' => 'Success',
                'data'    => [
                    'total_revenue' => $totalRevenue,
                    'transactions'  => $transactions,
                ],
            ]);
        });
    });
});
