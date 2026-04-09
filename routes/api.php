<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/track/{invoice_code}', [TransactionController::class, 'track']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    
    // Simple report endpoint
    Route::get('/report', function(Request $request) {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $totalRevenue = \App\Models\Transaction::where('status', 'selesai')->sum('total_price');
        $transactions = \App\Models\Transaction::with(['user', 'details.service'])->where('status', 'selesai')->get();
        
        return response()->json([
            'message' => 'Success',
            'data' => [
                'total_revenue' => $totalRevenue,
                'transactions' => $transactions
            ]
        ]);
    });
});
