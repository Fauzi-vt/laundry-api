<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminShuttleController extends Controller
{
    /**
     * Menampilkan jadwal penjemputan dan pengantaran laundry.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Query transaksi yang menggunakan layanan Antar-Jemput
        $query = Transaction::with(['user', 'details.service'])
            ->where(function ($q) {
                $q->where('delivery_type', 'antar_jemput')
                  ->orWhereNotNull('address');
            })
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

        // Filter status cucian
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(10)->withQueryString();

        // Statistik Antar-Jemput
        $stats = [
            'total'     => Transaction::whereNotNull('address')->count(),
            'baru'      => Transaction::whereNotNull('address')->where('status', 'baru')->count(),
            'proses'    => Transaction::whereNotNull('address')->whereIn('status', ['cuci', 'kering', 'setrika'])->count(),
            'selesai'   => Transaction::whereNotNull('address')->where('status', 'selesai')->count(),
        ];

        return view('admin.shuttles', compact('user', 'transactions', 'stats'));
    }
}
