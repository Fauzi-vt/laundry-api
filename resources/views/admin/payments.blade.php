@extends('layouts.admin')

@section('title', 'Validasi Pembayaran')

@section('content')
<div x-data="{ imageUrl: '', showImageModal: false }" class="space-y-6">
    
    {{-- Modal: Detail Bukti Transfer --}}
    <div x-show="showImageModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showImageModal = false"></div>
        <div class="relative max-w-2xl bg-white dark:bg-slate-800 p-3 rounded-2xl z-10 shadow-2xl border dark:border-slate-700">
            <button @click="showImageModal = false" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm font-bold shadow-md hover:bg-black transition cursor-pointer">✕</button>
            <img :src="imageUrl" class="max-w-full max-h-[80vh] rounded-xl object-contain">
        </div>
    </div>

    {{-- Page Header --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Admin</a></li>
                    <li><span class="mx-1 text-slate-300 dark:text-slate-600">/</span></li>
                    <li class="text-slate-700 dark:text-slate-300" aria-current="page">Pembayaran</li>
                </ol>
            </nav>
            <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Validasi Pembayaran</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Konfirmasi bukti transfer masuk dan setujui status pelunasan transaksi pelanggan.</p>
        </div>
    </header>

    {{-- Widget Ringkasan Pembayaran --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Menunggu Validasi</p>
                <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending_validation'] }}</p>
            </div>
            <div class="text-2xl">⏳</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Belum Bayar (Tunai)</p>
                <p class="text-2xl font-extrabold text-slate-700 dark:text-slate-300 mt-1">{{ $stats['unpaid_cash'] }}</p>
            </div>
            <div class="text-2xl">💵</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Lunas</p>
                <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['total_paid'] }}</p>
            </div>
            <div class="text-2xl">✅</div>
        </div>
    </section>

    {{-- Daftar Pembayaran --}}
    <section class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/40 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/40">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.payments.index') }}" 
                   class="text-xs font-bold px-3 py-1.5 rounded-lg border {{ !request('need_validation') ? 'bg-slate-100 border-slate-200 text-slate-800 dark:bg-slate-700 dark:border-slate-600 dark:text-white' : 'text-slate-500 border-transparent hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    Semua Transaksi
                </a>
                <a href="{{ route('admin.payments.index', ['need_validation' => 'yes']) }}" 
                   class="text-xs font-bold px-3 py-1.5 rounded-lg border {{ request('need_validation') === 'yes' ? 'bg-slate-100 border-slate-200 text-slate-800 dark:bg-slate-700 dark:border-slate-600 dark:text-white' : 'text-slate-500 border-transparent hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    Butuh Verifikasi ({{ $stats['pending_validation'] }})
                </a>
            </div>
            
            <form method="GET" action="{{ route('admin.payments.index') }}" class="flex items-center gap-2" role="search">
                @if(request('need_validation'))
                <input type="hidden" name="need_validation" value="yes">
                @endif
                <input type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                       class="px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-xs outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                @if(request('search'))
                <a href="{{ route('admin.payments.index', request('need_validation') ? ['need_validation' => 'yes'] : []) }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-900/50">
                    <tr class="whitespace-nowrap">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Metode Pembayaran</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Biaya</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Bukti Transfer</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tindakan Validasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-extrabold text-brand text-sm">{{ $trx->invoice_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $trx->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->user->phone ?? 'Tidak ada telepon' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $pm = strtolower($trx->payment_method ?? 'cash');
                            $pmLabel = match($pm) {
                                'cash'      => ['💵 Cash / Tunai',   'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-400'],
                                'bca'       => ['🏦 Bank BCA',       'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/30 dark:text-blue-400'],
                                'bri'       => ['🏦 Bank BRI',       'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/30 dark:text-sky-400'],
                                'mandiri'   => ['🏦 Bank Mandiri',   'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400'],
                                'bsi'       => ['🏦 Bank BSI',       'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-950/30 dark:text-teal-400'],
                                'bni'       => ['🏦 Bank BNI',       'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/30 dark:text-orange-400'],
                                'gopay'     => ['📱 GoPay',          'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-950/30 dark:text-cyan-400'],
                                'ovo'       => ['📱 OVO',            'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-950/30 dark:text-violet-400'],
                                'dana'      => ['📱 DANA',           'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/30 dark:text-blue-400'],
                                'shopeepay' => ['📱 ShopeePay',      'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/30 dark:text-rose-400'],
                                default     => ['💵 Cash / Tunai',   'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-900/50 dark:text-slate-400'],
                            };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $pmLabel[1] }}">
                                {{ $pmLabel[0] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-slate-900 dark:text-white">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($trx->payment_proof)
                            <button @click="imageUrl = '{{ asset($trx->payment_proof) }}'; showImageModal = true" 
                                    class="w-12 h-12 rounded-lg border overflow-hidden bg-slate-100 dark:bg-slate-900 inline-block hover:opacity-80 transition cursor-pointer">
                                <img src="{{ asset($trx->payment_proof) }}" class="w-full h-full object-cover">
                            </button>
                            @else
                            <span class="text-xs text-slate-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $trx->status === 'baru' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400' : 'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300' }}"
                                  x-text="{{ $trx->status === 'baru' ? '`Belum Lunas`' : '`Lunas / Terverifikasi`' }}"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($trx->status === 'baru')
                            <form method="POST" action="{{ route('admin.payments.verify', $trx->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-[11px] font-bold transition-all shadow cursor-pointer">
                                    Verifikasi Pembayaran
                                </button>
                            </form>
                            @else
                            <span class="text-[11px] font-bold text-slate-400">✓ Sudah Terverifikasi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada transaksi pembayaran masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </section>
</div>
@endsection
