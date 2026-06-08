<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
    /**
     * Menampilkan daftar pesanan & transaksi untuk admin.
     * Mengambil data secara real-time dari database local Laragon (MySQL via Eloquent).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Query transaksi beserta relasi User (Pelanggan) dan detail item cucian (Service)
        $query = Transaction::with(['user', 'details.service'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan kode invoice atau nama pelanggan
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status cucian ('baru', 'cuci', 'kering', 'setrika', 'selesai', 'diambil')
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Pagination 10 data per halaman
        $transactions = $query->paginate(10)->withQueryString();

        // Menghitung statistik pesanan untuk widget ringkasan
        $stats = [
            'baru'    => Transaction::where('status', 'baru')->count(),
            'proses'  => Transaction::whereIn('status', ['cuci', 'kering', 'setrika'])->count(),
            'selesai' => Transaction::where('status', 'selesai')->count(),
            'diambil' => Transaction::where('status', 'diambil')->count(),
        ];

        return view('admin.orders', compact('user', 'transactions', 'stats'));
    }

    /**
     * Menerima pesanan pelanggan.
     * Mengubah status pesanan dan mengalihkan admin ke halaman pembayaran untuk verifikasi.
     */
    public function accept($id): \Illuminate\Http\RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->status === 'baru') {
            $transaction->update(['status' => 'baru']);
        }

        return redirect()->route('admin.payments.index', ['search' => $transaction->invoice_code])
            ->with('success', 'Pesanan diterima, silakan verifikasi pembayaran.');
    }
}
