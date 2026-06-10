@extends('layouts.admin')

@section('title', 'Dashboard')

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

        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-xl z-10 overflow-hidden border dark:border-slate-700 flex flex-col max-h-[90vh]">
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

            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-brand-dark/50 text-white flex justify-between items-start no-print flex-shrink-0">
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

            <div class="p-6 overflow-y-auto">
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
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Metode Bayar</p>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold capitalize inline-flex border bg-slate-50 border-slate-200 dark:bg-slate-900/50 dark:border-slate-700"
                              x-text="paymentLabel(trx.payment_method)"></span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Status Pembayaran</p>
                        <span :class="trx.status === 'baru' ? 'bg-red-100 text-red-700 border-red-200 dark:bg-rose-900/50 dark:text-rose-300 dark:border-rose-700' : 'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80'"
                              class="px-3 py-1.5 rounded-full text-xs font-bold inline-flex border"
                              x-text="trx.status === 'baru' ? 'Belum Lunas' : 'Lunas'"></span>
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
                            <tr class="border-t border-slate-100 dark:border-slate-700">
                                <td colspan="2" class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400 text-right text-xs">TOTAL BIAYA</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900 dark:text-slate-100 text-sm" x-text="'Rp ' + Number(trx.total_price).toLocaleString('id-ID')"></td>
                            </tr>
                            <template x-if="trx.down_payment > 0">
                                <tr class="border-t border-slate-100 dark:border-slate-700">
                                    <td colspan="2" class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400 text-right text-xs">UANG MUKA (DP)</td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400 text-sm" x-text="'- Rp ' + Number(trx.down_payment).toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                            <template x-if="trx.down_payment > 0 && trx.status === 'baru'">
                                <tr class="border-t border-slate-200 dark:border-slate-700 bg-rose-50/30 dark:bg-rose-950/20">
                                    <td colspan="2" class="px-4 py-3 font-extrabold text-rose-600 dark:text-rose-400 text-right text-xs">SISA HARUS DIBAYAR</td>
                                    <td class="px-4 py-3 text-right font-black text-rose-600 dark:text-rose-400 text-sm" x-text="'Rp ' + Number(trx.total_price - trx.down_payment).toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                            <template x-if="trx.down_payment > 0 && trx.status !== 'baru'">
                                <tr class="border-t border-slate-200 dark:border-slate-700 bg-emerald-50/30 dark:bg-emerald-950/20">
                                    <td colspan="2" class="px-4 py-3 font-extrabold text-emerald-600 dark:text-emerald-400 text-right text-xs">STATUS</td>
                                    <td class="px-4 py-3 text-right font-black text-emerald-600 dark:text-emerald-400 text-sm">LUNAS</td>
                                </tr>
                            </template>
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
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg z-10 overflow-hidden border dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand dark:from-slate-950 dark:to-brand-dark/50 text-white flex justify-between items-center flex-shrink-0">
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
            <form method="POST" action="{{ route('orders.admin.store') }}" class="p-6 space-y-5 overflow-y-auto">
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
                <div>
                    <label for="down_payment" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Uang Muka / DP (Opsional)</label>
                    <input id="down_payment" type="number" name="down_payment" placeholder="Contoh: 5000" min="0"
                           class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 transition-all hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500">
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2.5 uppercase tracking-wide">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-2" id="paymentMethodAdmin">
                        @php
                        $payMethods = [
                            ['val'=>'cash',      'label'=>'Cash / Tunai',   'icon'=>'💵', 'color'=>'emerald'],
                            ['val'=>'bca',       'label'=>'Bank BCA',       'icon'=>'🏦', 'color'=>'blue'],
                            ['val'=>'bri',       'label'=>'Bank BRI',       'icon'=>'🏦', 'color'=>'sky'],
                            ['val'=>'mandiri',   'label'=>'Bank Mandiri',   'icon'=>'🏦', 'color'=>'amber'],
                            ['val'=>'bsi',       'label'=>'Bank BSI',       'icon'=>'🏦', 'color'=>'teal'],
                            ['val'=>'bni',       'label'=>'Bank BNI',       'icon'=>'🏦', 'color'=>'orange'],
                            ['val'=>'gopay',     'label'=>'GoPay',          'icon'=>'📱', 'color'=>'cyan'],
                            ['val'=>'ovo',       'label'=>'OVO',            'icon'=>'📱', 'color'=>'violet'],
                            ['val'=>'dana',      'label'=>'DANA',           'icon'=>'📱', 'color'=>'blue'],
                            ['val'=>'shopeepay', 'label'=>'ShopeePay',      'icon'=>'📱', 'color'=>'rose'],
                        ];
                        @endphp
                        @foreach($payMethods as $pm)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $pm['val'] }}" class="peer sr-only" {{ $pm['val'] === 'cash' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5 px-3.5 py-2.5 border-2 border-slate-200 dark:border-slate-600 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900 peer-checked:border-brand peer-checked:bg-brand-light dark:peer-checked:bg-brand/10 dark:peer-checked:border-brand peer-checked:text-brand transition-all cursor-pointer hover:border-slate-300 dark:hover:border-slate-500">
                                <span class="text-base leading-none">{{ $pm['icon'] }}</span>
                                <span class="truncate">{{ $pm['label'] }}</span>
                                <svg class="ml-auto w-3.5 h-3.5 text-brand opacity-0 peer-checked:opacity-100 flex-shrink-0 hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    {{-- Show bank account info when bank method selected --}}
                    <div id="adminBankInfo" class="hidden mt-3 p-3.5 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/40 rounded-xl">
                        <p class="text-[11px] font-bold text-blue-700 dark:text-blue-400 mb-1.5">📋 Info Rekening / Nomor Tujuan</p>
                        <div id="adminBankDetail" class="text-xs text-blue-800 dark:text-blue-300 space-y-0.5 font-medium"></div>
                    </div>
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
                <button type="button" onclick="addAdminItem()" class="w-full py-3.5 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:border-brand hover:text-brand dark:hover:border-brand dark:hover:border-brand hover:bg-brand-light dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-brand outline-none">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Item Layanan
                </button>
                <button type="submit" class="w-full py-4 bg-brand text-white font-black text-base rounded-2xl hover:bg-brand-dark transition-all shadow-xl shadow-brand/10 dark:shadow-none focus:ring-2 focus:ring-brand outline-none focus:ring-offset-2 dark:focus:ring-offset-slate-800 transform active:scale-95">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="space-y-6">
    {{-- Page Header: Hierarki Visual Utama --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Admin</a></li>
                    <li><span class="mx-1 text-slate-300 dark:text-slate-600">/</span></li>
                    <li class="text-slate-700 dark:text-slate-300" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard Operasional</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Live
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ringkasan operasional, pantau status cucian, kelola pesanan secara real-time.</p>
        </div>
        <div class="flex-shrink-0">
            <button @click="$dispatch('open-add-modal')"
                    class="w-full sm:w-auto px-4.5 py-2.5 bg-brand text-white rounded-lg text-xs font-bold hover:bg-brand-dark transition-all shadow-md shadow-brand/10 dark:shadow-none flex items-center justify-center gap-2 transform active:scale-95 focus:ring-2 focus:ring-brand/20 outline-none">
                <svg class="w-4.5 h-4.5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Transaksi Baru
            </button>
        </div>
    </header>

    {{-- Section Statistik --}}
    <section aria-label="Statistik Ringkasan" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $cards = [
            ['label'=>'Pendapatan Hari Ini',       'val'=>'Rp '.number_format($todayRevenue,0,',','.'),'icon'=>'M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3','color'=>'brand'],
            ['label'=>'Total Selesai',              'val'=>$totalDone.' Order',   'icon'=>'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
            ['label'=>'Sedang Diproses',            'val'=>$totalActive.' Cucian','icon'=>'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'cyan'],
            ['label'=>'Total Pelanggan',            'val'=>$customers.' Orang',   'icon'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20c-2.213 0-4.3-.632-6.09-1.735a4.125 4.125 0 010-7.03 11.414 11.414 0 0111.083 0 4.125 4.125 0 013.918 3.52M8 7a3 3 0 11-6 0 3 3 0 016 0zm14 0a3 3 0 11-6 0 3 3 0 016 0z','color'=>'indigo'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm p-6 flex items-center gap-4.5 hover:shadow-md dark:hover:border-slate-600/60 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-900/30 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[10px] font-black text-slate-400 dark:text-brand/70 uppercase tracking-wider">{{ $card['label'] }}</h2>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-0.5 tracking-tight">{{ $card['val'] }}</p>
            </div>
        </div>
        @endforeach
    </section>

    {{-- Section Tabel Monitoring --}}
    <section aria-label="Daftar Transaksi Aktif" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/40 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/40">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                </span>
                Pesanan Berlangsung
            </h2>
            
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap lg:flex-nowrap items-center gap-2" role="search">
                <div class="relative">
                    <label for="searchInput" class="sr-only">Cari invoice, nama, nomor telepon</label>
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z"/>
                    </svg>
                    <input id="searchInput" type="text" name="search" placeholder="Cari invoice / nama / telepon..." value="{{ request('search') }}"
                           class="w-full sm:w-52 pl-10 pr-4 py-2 border-[0.5px] border-slate-200 dark:border-slate-700 rounded-lg text-xs focus:ring-1 focus:ring-brand focus:border-brand outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 transition-all">
                </div>

                <div class="flex items-center gap-1.5">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" title="Tanggal Mulai"
                           class="px-2 py-1.5 border-[0.5px] border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-brand outline-none cursor-pointer">
                    <span class="text-slate-400 text-xs">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" title="Tanggal Akhir"
                           class="px-2 py-1.5 border-[0.5px] border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-brand outline-none cursor-pointer">
                </div>
                
                <div class="relative">
                    <label for="statusFilter" class="sr-only">Filter Status</label>
                    <select id="statusFilter" name="status_filter" onchange="this.form.submit()"
                            class="px-3 py-2 border-[0.5px] border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-brand focus:border-brand outline-none hover:border-slate-300 dark:hover:border-slate-600 transition-colors cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                        <option value="{{ $st }}" {{ request('status_filter') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if(request('search') || request('status_filter') || request('start_date') || request('end_date'))
                <a href="{{ route('admin.dashboard') }}" aria-label="Reset" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors inline-flex items-center justify-center">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <div class="w-full overflow-x-auto scrollbar-hide rounded-lg">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-900/50">
                    <tr class="whitespace-nowrap">
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Bayar</th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Progress</th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status Uang</th>
                        <th scope="col" class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Perbarui Progress</th>
                        <th scope="col" class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><span class="sr-only">Aksi</span>Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800">
                    @forelse($transactions as $trx)
                    <tr class="border-b border-slate-100 dark:border-slate-700/60 hover:bg-slate-50/50 dark:hover:bg-brand/5 hover:shadow-sm transition-all duration-200 group cursor-pointer" id="trx-{{ $trx->id }}"
                        x-data="{ currentStatus: '{{ $trx->status }}', loading: false }"
                        @click="if (!$event.target.closest('select') && !$event.target.closest('button')) openDetail({{ $trx->id }})">
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span class="font-extrabold text-brand dark:text-brand text-sm tracking-tight">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">{{ $trx->user->name ?? '-' }}</p>
                            <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500 mt-1">{{ $trx->user->phone ?? 'Tidak ada nomor' }}</p>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500 mt-1">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span class="text-sm font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</span>
                            @if($trx->down_payment > 0 && $trx->status === 'baru')
                                <div class="text-[10px] font-bold text-rose-500 mt-1">Sisa: Rp {{ number_format($trx->total_price - $trx->down_payment, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold capitalize inline-flex border"
                                  :class="{
                                      'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50': currentStatus === 'baru',
                                      'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-800/50': ['cuci','kering','setrika'].includes(currentStatus),
                                      'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80': ['selesai','diambil'].includes(currentStatus)
                                  }"
                                  x-text="currentStatus">
                            </span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span :class="currentStatus === 'baru' ? 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/30 dark:text-rose-400 dark:border-rose-800/50' : 'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80'"
                                  class="px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                  x-text="currentStatus === 'baru' ? 'BELUM LUNAS' : 'LUNAS'"></span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-center">
                            <label :for="'update-status-' + {{ $trx->id }}" class="sr-only">Perbarui Status {{ $trx->invoice_code }}</label>
                            <select :id="'update-status-' + {{ $trx->id }}" x-model="currentStatus"
                                    @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                    :disabled="loading"
                                    class="py-1.5 px-3.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-lg text-[11px] font-bold focus:ring-1 focus:ring-brand outline-none disabled:opacity-50 cursor-pointer hover:border-brand dark:hover:border-brand transition-colors text-slate-700 dark:text-slate-300">
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-center">
                            <button onclick="openDetail({{ $trx->id }})"
                                    aria-label="Lihat detail pesanan {{ $trx->invoice_code }}"
                                    title="Lihat Detail"
                                    class="w-8 h-8 inline-flex items-center justify-center bg-transparent hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-400 hover:text-brand dark:hover:text-brand rounded-lg transition-all focus:ring-1 focus:ring-brand focus:outline-none">
                                <svg class="w-4.5 h-4.5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
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
                            <a href="{{ route('admin.dashboard') }}" class="inline-block mt-4 text-brand dark:text-brand font-bold text-sm hover:underline focus:ring-2 focus:ring-brand outline-none rounded p-1">Reset Filter</a>
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
            'id'             => $t->id,
            'invoice_code'   => $t->invoice_code,
            'customer'       => $t->user->name ?? '-',
            'status'         => $t->status,
            'total_price'    => $t->total_price,
            'down_payment'   => $t->down_payment,
            'payment_method' => $t->payment_method ?? 'cash',
            'created_at'     => $t->created_at->format('d M Y, H:i'),
            'details'        => $t->details->map(function($d) {
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
    const adminServices = @json($servicesJson);

    function detailModal() {
        return {
            show: false,
            trx: { invoice_code:'', customer:'', status:'', total_price:0, down_payment:0, payment_method:'cash', created_at:'', details:[] },
            open(data) {
                this.trx = data;
                this.show = true;
            },
            statusClass(s) {
                if (s === 'baru') return 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700/50';
                if (['cuci','kering','setrika'].includes(s)) return 'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-800/50';
                return 'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80';
            },
            paymentLabel(m) {
                const map = {
                    cash: '💵 Cash / Tunai', bca: '🏦 Bank BCA', bri: '🏦 Bank BRI',
                    mandiri: '🏦 Bank Mandiri', bsi: '🏦 Bank BSI', bni: '🏦 Bank BNI',
                    gopay: '📱 GoPay', ovo: '📱 OVO', dana: '📱 DANA', shopeepay: '📱 ShopeePay'
                };
                return map[m] || '💵 Cash / Tunai';
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
                const isDark = localStorage.getItem('darkMode') === 'true';
                Swal.fire({
                    title: 'Gagal!',
                    text: (err.message ?? 'Unknown error'),
                    icon: 'error',
                    confirmButtonColor: '#c5a373',
                    background: isDark ? '#1a1c24' : '#ffffff',
                    color: isDark ? '#f8fafc' : '#1e293b',
                    customClass: { popup: 'rounded-2xl border border-slate-100 dark:border-slate-700' }
                });
                if (selectEl) selectEl.value = allTransactions.find(t => t.id === id)?.status ?? newStatus;
            }
        } catch (e) {
            const isDark = localStorage.getItem('darkMode') === 'true';
            Swal.fire({
                title: 'Error!',
                text: 'Kesalahan jaringan.',
                icon: 'error',
                confirmButtonColor: '#c5a373',
                background: isDark ? '#1a1c24' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-2xl border border-slate-100 dark:border-slate-700' }
            });
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

    // ── Payment method info (admin modal) ────────────────────────────────────
    const adminBankAccounts = {
        cash:      null,
        bca:       'BCA — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>1234567890</strong>',
        bri:       'BRI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>0987654321</strong>',
        mandiri:   'Mandiri — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>1122334455</strong>',
        bsi:       'BSI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>7081234567</strong>',
        bni:       'BNI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>0123456789</strong>',
        gopay:     'GoPay — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
        ovo:       'OVO — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
        dana:      'DANA — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
        shopeepay: 'ShopeePay — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
    };

    document.getElementById('paymentMethodAdmin')?.addEventListener('change', function(e) {
        const val = e.target.value;
        const infoBox = document.getElementById('adminBankInfo');
        const detailEl = document.getElementById('adminBankDetail');
        if (adminBankAccounts[val]) {
            detailEl.innerHTML = adminBankAccounts[val];
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    });
</script>
@endsection
