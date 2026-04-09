<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Admin membuat transaksi baru atas nama pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'items'               => 'required|array|min:1',
            'items.*.service_id'  => 'required|exists:services,id',
            'items.*.quantity'    => 'required|numeric|min:0.1',
        ], [
            'user_id.required'             => 'Pilih pelanggan terlebih dahulu.',
            'items.required'               => 'Pilih minimal satu layanan.',
            'items.*.service_id.required'  => 'Layanan tidak valid.',
            'items.*.quantity.min'         => 'Jumlah minimal 0.1.',
        ]);

        $totalPrice = 0;

        DB::transaction(function () use ($request, &$totalPrice) {
            $trx = Transaction::create([
                'user_id'      => $request->user_id,
                'invoice_code' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
                'total_price'  => 0,
                'status'       => 'baru',
            ]);

            foreach ($request->items as $item) {
                $service  = Service::findOrFail($item['service_id']);
                $subtotal = $service->price * $item['quantity'];
                $totalPrice += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'service_id'     => $service->id,
                    'quantity'       => $item['quantity'],
                    'price'          => $service->price,
                    'subtotal'       => $subtotal,
                ]);
            }

            $trx->update(['total_price' => $totalPrice]);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Transaksi baru berhasil dibuat!');
    }
}
