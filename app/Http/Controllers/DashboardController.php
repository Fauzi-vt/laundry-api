<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Halaman utama dashboard setelah login.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $todayRevenue = \App\Models\Transaction::where('status', 'selesai')->whereDate('updated_at', today())->sum('total_price');
            $totalRevenue = \App\Models\Transaction::where('status', 'selesai')->sum('total_price');
            
            $transactions = \App\Models\Transaction::with(['user', 'details.service'])->orderBy('created_at', 'desc')->get();
            $services = \App\Models\Service::all();
            $customers = \App\Models\User::where('role', 'user')->get();

            return view('dashboard', compact('user', 'todayRevenue', 'totalRevenue', 'transactions', 'services', 'customers'));
        }

        // For user role
        $transactions = \App\Models\Transaction::with(['details.service'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $services = \App\Models\Service::all();
        
        $totalSpent = $transactions->whereIn('status', ['selesai', 'diambil'])->sum('total_price');
        $activeOrders = $transactions->whereIn('status', ['baru', 'cuci', 'kering', 'setrika'])->count();
        $completedOrders = $transactions->whereIn('status', ['selesai', 'diambil'])->count();
        $latestTransaction = $transactions->first();

        $initialTab          = session('tab', 'overview');
        $pendingPayments     = $transactions->where('status', 'baru');
        $activeTransactions  = $transactions->whereIn('status', ['cuci', 'kering', 'setrika']);

        return view('user_dashboard', compact(
            'user', 'transactions', 'services',
            'totalSpent', 'activeOrders', 'completedOrders', 'latestTransaction',
            'initialTab', 'pendingPayments', 'activeTransactions'
        ));
    }
}
