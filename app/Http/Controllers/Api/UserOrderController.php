<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    /**
     * User membuat pesanan laundry baru.
     * Body: { items: [{ service_id, quantity }] }
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity'   => 'required|numeric|min:0.1',
            'address'            => 'required|string',
            'phone'              => 'required|string',
            'payment_method'     => 'required|string',
            'delivery_type'      => 'required|string',
        ], [
            'items.required'              => 'Pilih minimal satu layanan.',
            'items.*.service_id.required' => 'Layanan tidak valid.',
            'items.*.quantity.min'        => 'Jumlah minimal 0.1.',
            'address.required'            => 'Alamat harus diisi.',
            'phone.required'              => 'Nomor telepon harus diisi.',
            'payment_method.required'     => 'Metode pembayaran harus dipilih.',
            'delivery_type.required'      => 'Metode antar jemput harus dipilih.',
        ]);

        $user = $request->user();

        $transaction = DB::transaction(function () use ($request, $user) {
            $totalPrice = 0;

            $trx = Transaction::create([
                'user_id'      => $user->id,
                'invoice_code'   => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
                'total_price'    => 0,
                'status'         => 'baru',
                'address'        => $request->address,
                'phone'          => $request->phone,
                'payment_method' => $request->payment_method,
                'delivery_type'  => $request->delivery_type,
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
            return $trx->load('details.service');
        });

        return response()->json([
            'message' => 'Pesanan berhasil dibuat! Silakan lakukan pembayaran di kasir.',
            'data'    => $transaction,
        ], 201);
    }
}
