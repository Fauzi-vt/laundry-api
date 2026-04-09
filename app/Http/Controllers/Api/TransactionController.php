<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            $transactions = Transaction::with(['user', 'details.service'])->get();
        } else {
            $transactions = Transaction::with(['user', 'details.service'])->where('user_id', $user->id)->get();
        }

        return response()->json([
            'message' => 'success',
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'details' => 'required|array',
            'details.*.service_id' => 'required|exists:services,id',
            'details.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $totalPrice = 0;
        $transaction = \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$totalPrice) {
            $trx = Transaction::create([
                'user_id' => $request->user_id,
                'invoice_code' => 'INV-' . strtoupper(uniqid()),
                'total_price' => 0,
                'status' => 'baru',
            ]);

            foreach ($request->details as $item) {
                $service = \App\Models\Service::find($item['service_id']);
                $subtotal = $service->price * $item['quantity'];
                
                \App\Models\TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'service_id' => $service->id,
                    'quantity' => $item['quantity'],
                    'price' => $service->price,
                    'subtotal' => $subtotal,
                ]);

                $totalPrice += $subtotal;
            }

            $trx->update(['total_price' => $totalPrice]);
            return $trx->load('details.service');
        });

        return response()->json([
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:baru,cuci,kering,setrika,selesai,diambil'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);

        // "memicu notifikasi otomatis bagi pelanggan" - here we could fire an event
        // event(new TransactionStatusUpdated($transaction));

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $transaction
        ]);
    }
    
    public function track($invoice_code)
    {
        $transaction = Transaction::with(['user:id,name', 'details.service'])->where('invoice_code', $invoice_code)->first();
        if(!$transaction) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        
        return response()->json([
            'message' => 'Success',
            'data' => $transaction
        ]);
    }
}
