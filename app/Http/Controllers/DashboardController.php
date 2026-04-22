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

        return view('dashboard', compact(
            'user', 'todayRevenue', 'totalRevenue',
            'totalDone', 'totalActive', 'transactions',
            'services', 'customers'
        ));
    }
}
