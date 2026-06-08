<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran masuk yang perlu divalidasi.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Query transaksi yang memiliki bukti transfer atau pembayaran non-tunai
        $query = Transaction::with(['user'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan invoice atau nama pelanggan
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan ketersediaan bukti transfer (menampilkan semua atau hanya yang butuh validasi)
        if ($request->get('need_validation') === 'yes') {
            $query->whereNotNull('payment_proof')->where('status', 'baru');
        }

        $transactions = $query->paginate(10)->withQueryString();

        // Statistik Pembayaran
        $stats = [
            'pending_validation' => Transaction::whereNotNull('payment_proof')->where('status', 'baru')->count(),
            'unpaid_cash'        => Transaction::whereNull('payment_proof')->where('status', 'baru')->count(),
            'total_paid'         => Transaction::where('status', '!=', 'baru')->count(),
        ];

        return view('admin.payments', compact('user', 'transactions', 'stats'));
    }

    /**
     * Memverifikasi pembayaran pelanggan.
     * Mengubah status pesanan dari 'baru' (Belum Lunas) ke 'cuci' (Lunas & Diproses).
     */
    public function verify($id): RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->status !== 'baru') {
            return redirect()->back()->withErrors(['Transaksi sudah lunas atau sedang diproses.']);
        }

        // Mengubah status ke 'cuci' untuk menandai lunas & memulai proses laundry
        $transaction->update(['status' => 'cuci']);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pembayaran lunas, pakaian siap diproses.');
    }
}
