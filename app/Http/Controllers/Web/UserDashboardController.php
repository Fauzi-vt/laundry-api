<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Data umum yang dibagikan ke semua halaman user.
     */
    private function sharedData(): array
    {
        $user = Auth::user();
        $transactions = Transaction::with(['details.service'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrders       = $transactions->whereIn('status', ['baru', 'cuci', 'kering', 'setrika'])->count();
        $activeTransactions = $transactions->whereIn('status', ['cuci', 'kering', 'setrika']);
        $pendingCount       = $transactions->where('status', 'baru')->count();

        return compact('user', 'transactions', 'activeOrders', 'activeTransactions', 'pendingCount');
    }

    /**
     * Halaman: Ikhtisar (Home)
     */
    public function dashboard()
    {
        $data = $this->sharedData();
        $data['services']         = Service::all();
        $data['totalSpent']       = $data['transactions']->whereIn('status', ['selesai', 'diambil'])->sum('total_price');
        $data['completedOrders']  = $data['transactions']->whereIn('status', ['selesai', 'diambil'])->count();
        $data['latestTransaction']= $data['transactions']->first();
        $data['pendingPayments']  = $data['transactions']->where('status', 'baru');

        return view('user.dashboard', $data);
    }

    /**
     * Halaman: Lihat Layanan
     */
    public function layanan()
    {
        $data = $this->sharedData();
        $data['services'] = Service::all();

        return view('user.layanan', $data);
    }

    /**
     * Halaman: Order Laundry
     */
    public function order()
    {
        $data = $this->sharedData();
        $data['services'] = Service::all();

        return view('user.order', $data);
    }

    /**
     * Halaman: Pembayaran
     */
    public function pembayaran()
    {
        $data = $this->sharedData();
        $data['pendingPayments'] = $data['transactions']->where('status', 'baru');

        return view('user.pembayaran', $data);
    }

    /**
     * Halaman: Status Laundry
     */
    public function status()
    {
        $data = $this->sharedData();

        return view('user.status', $data);
    }

    /**
     * Halaman: Detail Transaksi
     */
    public function show($id)
    {
        $data = $this->sharedData();
        $transaction = Transaction::with(['user', 'details.service'])
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $data['trx'] = $transaction;

        return view('user.show', $data);
    }
}
