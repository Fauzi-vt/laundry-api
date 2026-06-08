@extends('layouts.admin')

@section('title', 'Pesanan & Transaksi')

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
            <div class="print-only p-6 border-b flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-black text-slate-900">Rumah Laundry Tasikmalaya</h1>
                    <p class="text-xs text-slate-500 mt-1">M42G+RHR, Jl. Muktamar NU. XXIX, Cipakat, Kec. Singaparna, Kabupaten Tasikmalaya, Jawa Barat 46417</p>
                </div>
                <div class="text-right text-xs text-slate-500">
                    <p class="font-bold">Nota Transaksi</p>
                    <p class="text-[10px] mt-1 text-slate-400">Dicetak: <span class="print-time"></span></p>
                </div>
            </div>

            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-brand-dark/50 text-white flex justify-between items-start no-print">
                <div>
                    <p class="text-xs text-brand-ring/80 dark:text-brand-ring/90 font-bold uppercase tracking-widest mb-1" id="modal-detail-title">Nota Transaksi</p>
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
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Metode Pengiriman</p>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold inline-flex border bg-slate-50 border-slate-200 dark:bg-slate-900/50 dark:border-slate-700 capitalize"
                              x-text="trx.delivery_type ? trx.delivery_type.replace('_', ' ') : 'Bawa Sendiri'"></span>
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
                                <td class="px-4 py-4 text-right font-black text-brand dark:text-brand text-base" x-text="'Rp ' + Number(trx.total_price).toLocaleString('id-ID')"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex gap-3 no-print">
                    <button @click="show = false"
                            class="flex-1 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-500 transition-colors focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 outline-none">
                        Tutup
                    </button>
                    <button onclick="window.print()"
                            class="flex-1 py-3.5 rounded-xl bg-brand text-white font-bold text-sm hover:bg-brand-dark transition-all shadow-lg shadow-brand/10 dark:shadow-none flex items-center justify-center gap-2 focus:ring-2 focus:ring-brand outline-none focus:ring-offset-2 dark:focus:ring-offset-slate-800">
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

{{-- Modal: Buat Pesanan Baru --}}
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
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-brand-dark/50 text-white flex justify-between items-center">
                <div>
                    <p class="text-xs text-brand-ring/80 dark:text-brand-ring/90 font-bold uppercase tracking-widest mb-1">Transaksi Baru</p>
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
                                @foreach(\App\Models\Service::all() as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} — Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="qty_0" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Jumlah</label>
                            <input id="qty_0" type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="2.5" required
                                   class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-brand outline-none transition-colors hover:border-slate-400 dark:hover:border-slate-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addAdminItem()" class="w-full py-3 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:border-brand hover:text-brand hover:bg-brand-light dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 outline-none">
                    + Tambah Item Layanan
                </button>
                <button type="submit" class="w-full py-4 bg-brand text-white font-black text-base rounded-2xl hover:bg-brand-dark transition-all shadow-xl">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="space-y-6 no-print">
    {{-- Page Header --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Admin</a></li>
                    <li><span class="mx-1 text-slate-300 dark:text-slate-600">/</span></li>
                    <li class="text-slate-700 dark:text-slate-300" aria-current="page">Pesanan & Transaksi</li>
                </ol>
            </nav>
            <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Pesanan & Transaksi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar lengkap pesanan masuk, pemrosesan cucian, dan status pembayaran pelanggan.</p>
        </div>
        <div class="flex-shrink-0">
            <button @click="$dispatch('open-add-modal')"
                    class="px-4.5 py-2.5 bg-brand text-white rounded-lg text-xs font-bold hover:bg-brand-dark transition-all shadow-md flex items-center justify-center gap-2 transform active:scale-95 focus:ring-2 focus:ring-brand/20 outline-none">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Buat Pesanan
            </button>
        </div>
    </header>

    {{-- Widget Ringkasan --}}
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Antrean Baru</p>
            <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stats['baru'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sedang Diproses</p>
            <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['proses'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['selesai'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sudah Diambil</p>
            <p class="text-2xl font-extrabold text-slate-500 mt-1">{{ $stats['diambil'] }}</p>
        </div>
    </section>

    {{-- Tabel Utama Pesanan --}}
    <section class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/40 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/40">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Daftar Pesanan Laundry
            </h2>
            
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2" role="search">
                <input type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                       class="w-full sm:w-52 px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-xs outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                
                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-xs bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300">
                    <option value="">Semua Status</option>
                    @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                
                @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
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
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Perbarui Status</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors" id="trx-{{ $trx->id }}"
                        x-data="{ currentStatus: '{{ $trx->status }}', loading: false }">
                        <td class="px-6 py-4 whitespace-nowrap font-extrabold text-brand text-sm">{{ $trx->invoice_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $trx->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->user->phone ?? 'Tidak ada telepon' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 capitalize">{{ $trx->payment_method ?? 'Cash' }}</p>
                            <p class="text-[9px] text-slate-400 mt-0.5 uppercase tracking-wider">{{ $trx->delivery_type ? str_replace('_', ' ', $trx->delivery_type) : 'Ambil Sendiri' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-slate-900 dark:text-white">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold capitalize inline-flex border"
                                  :class="{
                                      'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50': currentStatus === 'baru',
                                      'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-800/50': ['cuci','kering','setrika'].includes(currentStatus),
                                      'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80': ['selesai','diambil'].includes(currentStatus)
                                  }"
                                  x-text="currentStatus">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <select x-model="currentStatus"
                                    @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                    :disabled="loading"
                                    class="py-1 px-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded text-[11px] font-bold focus:ring-1 focus:ring-brand outline-none transition-colors text-slate-700 dark:text-slate-300 cursor-pointer">
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center flex items-center justify-center gap-2">
                            @if($trx->status === 'baru')
                            <form method="POST" action="{{ route('admin.orders.accept', $trx->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 bg-brand text-white rounded text-[10px] font-black hover:bg-brand-dark transition shadow-sm">
                                    Terima Pesanan
                                </button>
                            </form>
                            @endif
                            <button onclick="openDetail({{ $trx->id }})" class="p-1.5 text-slate-400 hover:text-brand transition-colors outline-none flex items-center gap-1 text-xs font-bold">
                                👁️ Nota
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">Belum ada data pesanan terdaftar.</td>
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

@php
    $trxJson = $transactions->map(function($t) {
        return [
            'id'           => $t->id,
            'invoice_code' => $t->invoice_code,
            'customer'     => $t->user->name ?? '-',
            'status'       => $t->status,
            'total_price'  => $t->total_price,
            'delivery_type'=> $t->delivery_type,
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
    })->values();
@endphp
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updatePrintTime() {
            const timeElements = document.querySelectorAll('.print-time');
            const now = new Date();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const dateStr = String(now.getDate()).padStart(2, '0') + ' ' + 
                            months[now.getMonth()] + ' ' + 
                            now.getFullYear() + ', ' + 
                            String(now.getHours()).padStart(2, '0') + ':' + 
                            String(now.getMinutes()).padStart(2, '0');
            timeElements.forEach(el => {
                el.textContent = dateStr + ' WIB';
            });
        }
        window.addEventListener('beforeprint', updatePrintTime);
        updatePrintTime();
    });

    const allTransactions = @json($trxJson);

    function detailModal() {
        return {
            show: false,
            trx: { invoice_code:'', customer:'', status:'', total_price:0, created_at:'', details:[], delivery_type: '' },
            open(data) {
                this.trx = data;
                this.show = true;
            },
            statusClass(s) {
                if (s === 'baru') return 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700/50';
                if (['cuci','kering','setrika'].includes(s)) return 'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-800/50';
                return 'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80';
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
                    row.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20');
                    setTimeout(() => row.classList.remove('bg-emerald-50', 'dark:bg-emerald-900/20'), 1500);
                }
            } else {
                alert('Gagal memperbarui status');
            }
        } catch (e) {
            alert('Kesalahan jaringan');
        } finally {
            if (comp) comp.loading = false;
        }
    }

    let adminItemCount = 1;
    function addAdminItem() {
        const idx = adminItemCount++;
        const opts = `@foreach(\App\Models\Service::all() as $s)<option value="{{ $s->id }}">{{ $s->name }} — Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>@endforeach`;
        const div = document.createElement('div');
        div.className = 'admin-order-item grid grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 relative mt-3';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2.5 -right-2.5 w-7 h-7 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600 shadow-sm focus:ring-2 focus:ring-red-300 outline-none">✕</button>
            <div class="col-span-2 sm:col-span-1">
                <label for="service_${idx}" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Layanan</label>
                <select id="service_${idx}" name="items[${idx}][service_id]" required class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 outline-none">
                    <option value="">-- Pilih Layanan --</option>${opts}
                </select>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="qty_${idx}" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase">Jumlah</label>
                <input id="qty_${idx}" type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" required
                       class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 outline-none">
            </div>`;
        document.getElementById('adminOrderItems').appendChild(div);
    }
</script>
@endsection
