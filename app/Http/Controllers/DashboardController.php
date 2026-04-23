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
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        // Arahkan user biasa ke halaman user
        if ($user->role !== 'admin') {
            return redirect()->route('user.dashboard');
        }

        // ── Metrics ────────────────────────────────────────────────────────
        $doneStatuses   = ['selesai', 'diambil'];
        $activeStatuses = ['baru', 'cuci', 'kering', 'setrika'];

        $todayRevenue = \App\Models\Transaction::whereIn('status', $doneStatuses)
            ->whereDate('updated_at', today())
            ->sum('total_price');

        $totalRevenue = \App\Models\Transaction::whereIn('status', $doneStatuses)->sum('total_price');
        $totalDone    = \App\Models\Transaction::whereIn('status', $doneStatuses)->count();
        $totalActive  = \App\Models\Transaction::whereIn('status', $activeStatuses)->count();

        // ── Query dengan search + filter ───────────────────────────────────
        $request = request();
        $query = \App\Models\Transaction::with(['user', 'details.service'])
            ->orderBy('created_at', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($statusFilter = $request->get('status_filter')) {
            $query->where('status', $statusFilter);
        }

        $transactions = $query->paginate(10)->withQueryString();
        $services     = \App\Models\Service::all();
        $customers    = \App\Models\User::where('role', 'user')->count();

        return view('admin.monitoring', compact(
            'user', 'todayRevenue', 'totalRevenue',
            'totalDone', 'totalActive', 'transactions',
            'services', 'customers'
        ));
    }

    public function customers(): View
    {
        $user = Auth::user();
        
        // ── Stats ──
        $totalCustomers = \App\Models\User::where('role', 'user')->count();
        $newCustomersThisMonth = \App\Models\User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ── Query ──
        $sort  = request('sort', 'name');
        $order = request('order', 'asc');
        
        $custQuery = \App\Models\User::where('role', 'user')->withCount('transactions');

        // Search
        if ($search = request('cust_search')) {
            $custQuery->where(fn($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"));
        }

        // Filter Status (Aktif jika ada transaksi di bulan ini)
        if (request('status') === 'active') {
            $custQuery->whereHas('transactions', function($q) {
                $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            });
        } elseif (request('status') === 'inactive') {
            $custQuery->whereDoesntHave('transactions', function($q) {
                $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            });
        }

        // Sort
        if ($sort === 'transactions_count') {
            $custQuery->orderBy('transactions_count', $order);
        } else {
            $custQuery->orderBy($sort, $order);
        }
        
        $customers = $custQuery->paginate(15)->withQueryString();
        
        return view('admin.customers', compact('user', 'customers', 'totalCustomers', 'newCustomersThisMonth'));
    }

    public function services(): View
    {
        $user = Auth::user();
        $services = \App\Models\Service::orderBy('category')->orderBy('name')->get();
        
        return view('admin.services', compact('user', 'services'));
    }

    public function reports(): View
    {
        $user = Auth::user();
        
        // ── Laporan Keuangan ──
        $doneStatuses = ['selesai', 'diambil'];
        $monthlyRevenue = \App\Models\Transaction::whereIn('status', $doneStatuses)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_price');

        $yearlyRevenue = \App\Models\Transaction::whereIn('status', $doneStatuses)
            ->whereYear('updated_at', now()->year)
            ->sum('total_price');

        $latestIncomes = \App\Models\Transaction::with('user')
            ->whereIn('status', $doneStatuses)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports', compact('user', 'monthlyRevenue', 'yearlyRevenue', 'latestIncomes'));
    }
}
