@extends('layouts.admin')

@section('title', 'Monitoring Cucian')

@section('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        body { background: white !important; color: black !important; }
    }
    .print-only { display: none; }
</style>
@endsection

@section('content')
{{-- Modal: Detail Transaksi --}}
<div x-data="detailModal()" @open-detail.window="open($event.detail)" x-cloak>
    <div x-show="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         role="dialog" aria-modal="true" aria-labelledby="modal-detail-title">

        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" aria-hidden="true" @click="show = false"></div>

        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-xl z-10 overflow-hidden border dark:border-slate-700">
            <div class="print-only p-6 border-b">
                <h1 class="text-xl font-black text-slate-900">Rumah Laundry Tasikmalaya</h1>
                <p class="text-sm text-slate-500">Jl. Laundry No.1 • 081234567890</p>
            </div>

            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-blue-900 text-white flex justify-between items-start no-print">
                <div>
                    <p class="text-xs text-blue-200 dark:text-blue-300 font-bold uppercase tracking-widest mb-1" id="modal-detail-title">Nota Transaksi</p>
                    <h2 class="text-2xl font-black" x-text="trx.invoice_code"></h2>
                </div>
                <button @click="show = false" aria-label="Tutup detail" class="text-white/70 hover:text-white transition p-1.5 rounded-lg focus:ring-2 focus:ring-white outline-none">
                    <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Pelanggan</p>
                        <p class="font-bold text-slate-900 dark:text-slate-100 text-base" x-text="trx.customer"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Tanggal Masuk</p>
                        <p class="font-bold text-slate-900 dark:text-slate-100" x-text="trx.created_at"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Status Cucian</p>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold capitalize inline-flex border"
                              :class="statusClass(trx.status)" x-text="trx.status"></span>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Pembayaran</p>
                        <span :class="trx.status === 'baru' ? 'bg-red-100 text-red-700 border-red-200 dark:bg-rose-900/50 dark:text-rose-300 dark:border-rose-700' : 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700'"
                              class="px-3 py-1.5 rounded-full text-xs font-bold inline-flex border"
                              x-text="trx.status === 'baru' ? 'Belum Bayar' : 'Lunas'"></span>
                    </div>
                </div>

                <div class="border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden mb-6">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-[11px]">Layanan</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-[11px]">Jml</th>
                                <th scope="col" class="px-4 py-3 text-right font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-[11px]">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <template x-for="d in trx.details" :key="d.id">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200" x-text="d.service?.name ?? '-'"></td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400" x-text="d.quantity + ' ' + (d.service?.unit ?? '')"></td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900 dark:text-slate-100" x-text="'Rp ' + Number(d.subtotal).toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-900/80 border-t border-slate-200 dark:border-slate-700">
                            <tr>
                                <td colspan="2" class="px-4 py-4 font-bold text-slate-900 dark:text-slate-200 text-right">TOTAL</td>
                                <td class="px-4 py-4 text-right font-black text-brand dark:text-blue-400 text-base" x-text="'Rp ' + Number(trx.total_price).toLocaleString('id-ID')"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <p class="text-center text-xs text-slate-500 dark:text-slate-400 mb-6 italic">Terima kasih telah mempercayakan cucian Anda kepada Rumah Laundry 🙏</p>

                <div class="flex gap-3 no-print">
                    <button @click="show = false"
                            class="flex-1 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-500 transition-colors focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 outline-none">
                        Tutup
                    </button>
                    <button onclick="window.print()"
                            class="flex-1 py-3.5 rounded-xl bg-brand text-white font-bold text-sm hover:bg-brand-dark transition-all shadow-lg shadow-blue-100 dark:shadow-none flex items-center justify-center gap-2 focus:ring-2 focus:ring-brand outline-none focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Tambah Transaksi --}}
<div x-data="{ showAddModal: false }" @keydown.escape.window="showAddModal = false">
    <span x-on:open-add-modal.window="showAddModal = true"></span>
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         role="dialog" aria-modal="true" aria-labelledby="modal-add-title"
         x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" aria-hidden="true" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-blue-900 text-white flex justify-between items-center">
                <div>
                    <p class="text-xs text-blue-200 dark:text-blue-300 font-bold uppercase tracking-widest mb-1">Transaksi Baru</p>
                    <h2 id="modal-add-title" class="text-xl font-black">Buat Pesanan</h2>
                </div>
                <button @click="showAddModal = false" aria-label="Tutup form tambah" class="text-white/70 hover:text-white transition p-1.5 rounded-lg focus:ring-2 focus:ring-white outline-none">
                    <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('orders.admin.store') }}" class="p-6 space-y-5">
                @csrf
                <div>
                    <label for="user_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Pilih Pelanggan</label>
                    <select id="user_id" name="user_id" required class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand outline-none bg-slate-50 dark:bg-slate-900 transition-colors hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer text-slate-800 dark:text-slate-200">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach(\App\Models\User::where('role','user')->orderBy('name')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? $c->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div id="adminOrderItems" class="space-y-3">
                    <div class="admin-order-item grid grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                        <div class="col-span-2 sm:col-span-1">
                            <label for="service_0" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Layanan</label>
                            <select id="service_0" name="items[0][service_id]" required class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 focus:ring-2 focus:ring-brand outline-none transition-colors hover:border-slate-400 dark:hover:border-slate-500 text-slate-800 dark:text-slate-200">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} — Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="qty_0" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Jumlah</label>
                            <input id="qty_0" type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="Misal: 2.5" required
                                   class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-brand outline-none transition-colors hover:border-slate-400 dark:hover:border-slate-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addAdminItem()" class="w-full py-3.5 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:border-brand hover:text-brand dark:hover:border-blue-400 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-brand outline-none">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Item Layanan
                </button>
                <button type="submit" class="w-full py-4 bg-brand text-white font-black text-base rounded-2xl hover:bg-brand-dark transition-all shadow-xl shadow-blue-100 dark:shadow-none focus:ring-2 focus:ring-brand outline-none focus:ring-offset-2 dark:focus:ring-offset-slate-800 transform active:scale-95">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="space-y-8">
    {{-- Page Header: Hierarki Visual Utama --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div>
            <nav aria-label="Breadcrumb" class="mb-3">
                <ol class="flex items-center space-x-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('admin.monitoring') }}" class="text-slate-600 dark:text-slate-400 hover:text-brand dark:hover:text-blue-400 transition-colors">Dashboard</a></li>
                    <li><span class="mx-1 text-slate-300 dark:text-slate-600">/</span></li>
                    <li class="text-slate-800 dark:text-slate-200" aria-current="page">Monitoring</li>
                </ol>
            </nav>
            <h1 class="text-xl md:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Monitoring Cucian</h1>
            <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 font-medium mt-2">Pantau status, kelola pesanan, dan perbarui transaksi pelanggan secara real-time.</p>
        </div>
        <div class="flex-shrink-0">
            <button @click="$dispatch('open-add-modal')"
                    class="w-full sm:w-auto px-6 py-3.5 bg-brand text-white rounded-2xl text-sm font-bold hover:bg-brand-dark transition-all shadow-lg shadow-blue-200 dark:shadow-none flex items-center justify-center gap-2 transform active:scale-95 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900 outline-none">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Transaksi Baru
            </button>
        </div>
    </header>

    {{-- Section Statistik --}}
    <section aria-label="Statistik Ringkasan" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @php
        $cards = [
            ['label'=>'Pendapatan Hari Ini',       'val'=>'Rp '.number_format($todayRevenue,0,',','.'),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'blue'],
            ['label'=>'Total Selesai',              'val'=>$totalDone.' Order',   'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
            ['label'=>'Sedang Diproses',            'val'=>$totalActive.' Cucian','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'amber'],
            ['label'=>'Total Pelanggan',            'val'=>$customers.' Orang',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'indigo'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-md dark:shadow-none p-6 flex items-center gap-5 hover:shadow-lg dark:hover:border-slate-600 transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-900/30 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" aria-hidden="true">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</h2>
                <p class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $card['val'] }}</p>
            </div>
        </div>
        @endforeach
    </section>

    {{-- Section Tabel Monitoring --}}
    <section aria-label="Daftar Transaksi Aktif" class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-6 py-6 border-b border-slate-100 dark:border-slate-700 flex flex-col lg:flex-row lg:items-center justify-between gap-5 bg-slate-50/50 dark:bg-slate-800/80">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-2.5 h-2.5 bg-brand dark:bg-blue-400 rounded-full animate-ping relative" aria-hidden="true">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-brand dark:bg-blue-400 opacity-75"></span>
                </span>
                Pesanan Berlangsung
            </h2>
            <div class="flex flex-wrap gap-3 items-center">
                <form method="GET" action="{{ route('admin.monitoring') }}" class="flex flex-wrap sm:flex-nowrap gap-3 w-full sm:w-auto" role="search">
                    <div class="relative flex-grow sm:flex-grow-0">
                        <label for="searchInput" class="sr-only">Cari invoice atau nama pelanggan</label>
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="searchInput" type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                               class="w-full sm:w-56 pl-11 pr-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-white dark:bg-slate-800 transition-all focus:w-full sm:focus:w-64 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500">
                    </div>
                    
                    <label for="statusFilter" class="sr-only">Filter Status</label>
                    <select id="statusFilter" name="status_filter" onchange="this.form.submit()"
                            class="flex-grow sm:flex-grow-0 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-brand outline-none hover:border-slate-300 dark:hover:border-slate-600 transition-colors cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                        <option value="{{ $st }}" {{ request('status_filter') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    
                    @if(request('search') || request('status_filter'))
                    <a href="{{ route('admin.monitoring') }}" aria-label="Reset pencarian dan filter" class="px-5 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors focus:ring-2 focus:ring-slate-400 dark:focus:ring-slate-500 outline-none inline-flex items-center justify-center">
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="w-full overflow-x-auto scrollbar-hide rounded-lg">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-900/50">
                    <tr class="whitespace-nowrap">
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Invoice</th>
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Pelanggan</th>
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Tanggal</th>
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Total Bayar</th>
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Progress</th>
                        <th scope="col" class="px-4 py-4 text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Status Uang</th>
                        <th scope="col" class="px-4 py-4 text-center text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest">Perbarui Progress</th>
                        <th scope="col" class="px-4 py-4 text-center text-[11px] font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-widest"><span class="sr-only">Aksi</span>Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800">
                    @forelse($transactions as $trx)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition-colors group" id="trx-{{ $trx->id }}"
                        x-data="{ currentStatus: '{{ $trx->status }}', loading: false }">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-extrabold text-brand dark:text-blue-400 text-sm tracking-tight">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $trx->user->name ?? '-' }}</p>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">{{ $trx->user->phone ?? 'Tidak ada nomor' }}</p>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-black text-slate-800 dark:text-slate-200">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold capitalize inline-flex border"
                                  :class="{
                                      'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/50 dark:text-amber-400 dark:border-amber-800': currentStatus === 'baru',
                                      'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:border-blue-800': ['cuci','kering','setrika'].includes(currentStatus),
                                      'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-400 dark:border-emerald-800': ['selesai','diambil'].includes(currentStatus)
                                  }"
                                  x-text="currentStatus">
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span :class="currentStatus === 'baru' ? 'bg-red-100 text-red-800 border-red-200 dark:bg-rose-900/50 dark:text-rose-400 dark:border-rose-800' : 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-400 dark:border-emerald-800'"
                                  class="px-3 py-1.5 rounded-full text-[11px] font-bold border"
                                  x-text="currentStatus === 'baru' ? 'BELUM LUNAS' : 'LUNAS'"></span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <label :for="'update-status-' + {{ $trx->id }}" class="sr-only">Perbarui Status {{ $trx->invoice_code }}</label>
                            <select :id="'update-status-' + {{ $trx->id }}" x-model="currentStatus"
                                    @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                    :disabled="loading"
                                    class="py-2 px-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand outline-none disabled:opacity-50 cursor-pointer hover:border-brand dark:hover:border-blue-400 transition-colors text-slate-700 dark:text-slate-200">
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <button onclick="openDetail({{ $trx->id }})"
                                    aria-label="Lihat detail pesanan {{ $trx->invoice_code }}"
                                    title="Lihat Detail"
                                    class="w-10 h-10 inline-flex items-center justify-center bg-transparent hover:bg-blue-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-400 hover:text-brand dark:hover:text-blue-400 rounded-xl transition-all focus:ring-2 focus:ring-brand focus:outline-none">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-24 text-center">
                            <div class="text-6xl mb-5" aria-hidden="true">🧺</div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200 mb-2">Tidak ada transaksi ditemukan</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Gunakan kata kunci pencarian yang lain atau ubah filter status untuk menemukan pesanan.</p>
                            @if(request('search') || request('status_filter'))
                            <a href="{{ route('admin.monitoring') }}" class="inline-block mt-4 text-brand dark:text-blue-400 font-bold text-sm hover:underline focus:ring-2 focus:ring-brand outline-none rounded p-1">Reset Filter</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/80">
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                Menampilkan <span class="font-bold text-slate-900 dark:text-slate-100">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}</span>
                dari <span class="font-bold text-slate-900 dark:text-slate-100">{{ $transactions->total() }}</span> pesanan
            </p>
            <nav aria-label="Navigasi Halaman" class="flex gap-2">
                {{ $transactions->links() }}
            </nav>
        </div>
        @endif
    </section>
</div>

@php
    $trxJson = $transactions->getCollection()->map(function($t) {
        return [
            'id'           => $t->id,
            'invoice_code' => $t->invoice_code,
            'customer'     => $t->user->name ?? '-',
            'status'       => $t->status,
            'total_price'  => $t->total_price,
            'created_at'   => $t->created_at->format('d M Y, H:i'),
            'details'      => $t->details->map(function($d) {
                return [
                    'id'       => $d->id,
                    'quantity' => $d->quantity,
                    'subtotal' => $d->subtotal,
                    'service'  => $d->service ? ['name' => $d->service->name, 'unit' => $d->service->unit] : null,
                ];
            })->values(),
        ];
    });

    $servicesJson = $services->map(function($s) {
        return ['id' => $s->id, 'name' => $s->name, 'price' => $s->price, 'unit' => $s->unit];
    })->values();
@endphp
@endsection

@section('scripts')
<script>
    const allTransactions = @json($trxJson);
    const adminServices = @json($servicesJson);

    function detailModal() {
        return {
            show: false,
            trx: { invoice_code:'', customer:'', status:'', total_price:0, created_at:'', details:[] },
            open(data) {
                this.trx = data;
                this.show = true;
            },
            statusClass(s) {
                if (s === 'baru') return 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700/50';
                if (['cuci','kering','setrika'].includes(s)) return 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700/50';
                return 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700/50';
            }
        };
    }

    function openDetail(id) {
        const trx = allTransactions.find(t => t.id === id);
        if (trx) window.dispatchEvent(new CustomEvent('open-detail', { detail: trx }));
    }

    async function updateStatus(id, newStatus, selectEl) {
        const row = document.getElementById(`trx-${id}`);
        const comp = row?._x_dataStack?.[0];
        if (comp) comp.loading = true;

        try {
            const res = await fetch(`/transactions/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (res.ok) {
                const trx = allTransactions.find(t => t.id === id);
                if (trx) trx.status = newStatus;
                if (row) {
                    row.classList.add('bg-emerald-50', 'dark:bg-emerald-900/30', 'transition-colors', 'duration-500');
                    setTimeout(() => row.classList.remove('bg-emerald-50', 'dark:bg-emerald-900/30'), 2000);
                }
            } else {
                const err = await res.json().catch(() => ({}));
                alert('Gagal: ' + (err.message ?? 'Unknown error'));
                if (selectEl) selectEl.value = allTransactions.find(t => t.id === id)?.status ?? newStatus;
            }
        } catch (e) {
            alert('Kesalahan jaringan.');
        } finally {
            if (comp) comp.loading = false;
        }
    }

    let adminItemCount = 1;
    function addAdminItem() {
        const idx = adminItemCount++;
        const opts = adminServices.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name} — Rp ${s.price.toLocaleString('id-ID')}/${s.unit}</option>`).join('');
        const div = document.createElement('div');
        div.className = 'admin-order-item grid grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 relative mt-3';
        div.innerHTML = `
            <button type="button" aria-label="Hapus layanan ini" onclick="this.parentElement.remove()" class="absolute -top-2.5 -right-2.5 w-7 h-7 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600 shadow-sm transition-colors focus:ring-2 focus:ring-red-300 outline-none">✕</button>
            <div class="col-span-2 sm:col-span-1">
                <label for="service_${idx}" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Layanan</label>
                <select id="service_${idx}" name="items[${idx}][service_id]" required class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 focus:ring-2 focus:ring-brand outline-none transition-colors hover:border-slate-400 dark:hover:border-slate-500 text-slate-800 dark:text-slate-200">
                    <option value="">-- Pilih --</option>${opts}
                </select>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="qty_${idx}" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Jumlah</label>
                <input id="qty_${idx}" type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" placeholder="Misal: 2.5" required
                       class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-brand outline-none transition-colors hover:border-slate-400 dark:hover:border-slate-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500">
            </div>`;
        document.getElementById('adminOrderItems').appendChild(div);
    }
</script>
@endsection
