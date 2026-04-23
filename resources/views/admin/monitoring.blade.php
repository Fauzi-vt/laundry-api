@extends('layouts.admin')

@section('title', 'Monitoring Cucian')

@section('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        body { background: white; }
    }
    .print-only { display: none; }
</style>
@endsection

@section('content')
<div x-data="detailModal()" @open-detail.window="open($event.detail)" x-cloak>
    <div x-show="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="show = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl z-10 overflow-hidden">
            <div class="print-only p-6 border-b">
                <h1 class="text-xl font-black text-slate-900">Rumah Laundry Tasikmalaya</h1>
                <p class="text-sm text-slate-500">Jl. Laundry No.1 • 081234567890</p>
            </div>

            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand text-white flex justify-between items-start no-print">
                <div>
                    <p class="text-xs text-blue-200 font-bold uppercase tracking-widest mb-1">Nota Transaksi</p>
                    <p class="text-xl font-black" x-text="trx.invoice_code"></p>
                </div>
                <button @click="show = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div>
                        <p class="text-slate-400 font-medium text-[10px] uppercase tracking-wider mb-1">Pelanggan</p>
                        <p class="font-bold text-slate-800" x-text="trx.customer"></p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-medium text-[10px] uppercase tracking-wider mb-1">Tanggal Masuk</p>
                        <p class="font-bold text-slate-800" x-text="trx.created_at"></p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-medium text-[10px] uppercase tracking-wider mb-1">Status Cucian</p>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold capitalize"
                              :class="statusClass(trx.status)" x-text="trx.status"></span>
                    </div>
                    <div>
                        <p class="text-slate-400 font-medium text-[10px] uppercase tracking-wider mb-1">Pembayaran</p>
                        <span :class="trx.status === 'baru' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'"
                              class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                              x-text="trx.status === 'baru' ? 'Belum Bayar' : 'Lunas'"></span>
                    </div>
                </div>

                <div class="border border-slate-100 rounded-2xl overflow-hidden mb-6">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">Layanan</th>
                                <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase tracking-wider">Jml</th>
                                <th class="px-4 py-3 text-right font-bold text-slate-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="d in trx.details" :key="d.id">
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-700" x-text="d.service?.name ?? '-'"></td>
                                    <td class="px-4 py-3 text-center text-slate-500" x-text="d.quantity + ' ' + (d.service?.unit ?? '')"></td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900" x-text="'Rp ' + Number(d.subtotal).toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50/50 border-t border-slate-100">
                            <tr>
                                <td colspan="2" class="px-4 py-4 font-bold text-slate-900">TOTAL</td>
                                <td class="px-4 py-4 text-right font-black text-brand text-sm" x-text="'Rp ' + Number(trx.total_price).toLocaleString('id-ID')"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <p class="text-center text-[10px] text-slate-400 mb-6 italic">Terima kasih telah mempercayakan cucian Anda kepada Rumah Laundry 🙏</p>

                <div class="flex gap-3 no-print">
                    <button @click="show = false"
                            class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">
                        Tutup
                    </button>
                    <button onclick="window.print()"
                            class="flex-1 py-3 rounded-xl bg-brand text-white font-bold text-sm hover:bg-brand-dark transition shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-data="{ showAddModal: false }" @keydown.escape.window="showAddModal = false">
    <span x-on:open-add-modal.window="showAddModal = true"></span>
    <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand text-white flex justify-between items-center">
                <div>
                    <p class="text-xs text-blue-200 font-bold uppercase tracking-widest mb-1">Transaksi</p>
                    <p class="text-lg font-black">Tambah Transaksi Baru</p>
                </div>
                <button @click="showAddModal = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('orders.admin.store') }}" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Pelanggan</label>
                    <select name="user_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand outline-none bg-slate-50">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach(\App\Models\User::where('role','user')->orderBy('name')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? $c->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div id="adminOrderItems" class="space-y-3">
                    <div class="admin-order-item grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Layanan</label>
                            <select name="items[0][service_id]" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs bg-white focus:ring-2 focus:ring-brand outline-none">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} — Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Jumlah</label>
                            <input type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="2.5" required
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand outline-none">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addAdminItem()" class="w-full py-3 border-2 border-dashed border-slate-200 rounded-2xl text-xs font-bold text-slate-400 hover:border-brand hover:text-brand transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Item Layanan
                </button>
                <button type="submit" class="w-full py-3.5 bg-brand text-white font-bold rounded-2xl hover:bg-brand-dark transition-all shadow-xl shadow-blue-100">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<div class="space-y-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $cards = [
            ['label'=>'Pendapatan Hari Ini',       'val'=>'Rp '.number_format($todayRevenue,0,',','.'),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'blue'],
            ['label'=>'Total Selesai',              'val'=>$totalDone.' Order',   'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
            ['label'=>'Sedang Diproses',            'val'=>$totalActive.' Cucian','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'amber'],
            ['label'=>'Total Pelanggan',            'val'=>$customers.' Orang',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'indigo'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-all group">
            <div class="w-11 h-11 rounded-2xl bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                <p class="text-lg font-extrabold text-slate-900 mt-0.5">{{ $card['val'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex flex-wrap gap-4 items-center justify-between bg-slate-50/30">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2 h-2 bg-brand rounded-full animate-ping"></span>
                Monitoring Cucian
            </h3>
            <div class="flex flex-wrap gap-2 items-center">
                <form method="GET" action="{{ route('admin.monitoring') }}" class="flex gap-2" id="filterForm">
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                               class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand outline-none w-48 bg-white transition-all focus:w-60">
                    </div>
                    <select name="status_filter" onchange="document.getElementById('filterForm').submit()"
                            class="px-3 py-2.5 border border-slate-200 rounded-xl text-xs bg-white focus:ring-2 focus:ring-brand outline-none">
                        <option value="">Semua Status</option>
                        @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                        <option value="{{ $st }}" {{ request('status_filter') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    @if(request('search') || request('status_filter'))
                    <a href="{{ route('admin.monitoring') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
                    @endif
                </form>
                <button @click="$dispatch('open-add-modal')"
                        class="px-5 py-2.5 bg-brand text-white rounded-xl text-xs font-bold hover:bg-brand-dark transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Transaksi Baru
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Invoice</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bayar</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Update</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/80 transition" id="trx-{{ $trx->id }}"
                        x-data="{ currentStatus: '{{ $trx->status }}', loading: false }">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="font-extrabold text-brand text-sm tracking-tight">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 italic">{{ $trx->user->phone ?? '' }}</p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-xs font-semibold text-slate-700">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-black text-slate-800">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold capitalize"
                                  :class="{
                                      'bg-amber-100 text-amber-700': currentStatus === 'baru',
                                      'bg-blue-100 text-blue-700 animate-pulse': ['cuci','kering','setrika'].includes(currentStatus),
                                      'bg-emerald-100 text-emerald-700': ['selesai','diambil'].includes(currentStatus)
                                  }"
                                  x-text="currentStatus"></span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span :class="currentStatus === 'baru' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600'"
                                  class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold"
                                  x-text="currentStatus === 'baru' ? 'BELUM' : 'LUNAS'"></span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <select x-model="currentStatus"
                                    @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                    :disabled="loading"
                                    class="py-1.5 px-2 border border-slate-200 bg-white rounded-xl text-[10px] font-bold focus:ring-2 focus:ring-brand outline-none disabled:opacity-50">
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <button onclick="openDetail({{ $trx->id }})"
                                    class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-brand hover:text-white text-slate-400 rounded-xl transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-20 text-center">
                            <div class="text-5xl mb-4">🧺</div>
                            <p class="font-bold text-slate-800">Tidak ada transaksi ditemukan</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci lain atau ubah filter status.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-5 border-t border-slate-50 flex flex-wrap items-center justify-between gap-4 bg-slate-50/20">
            <p class="text-xs text-slate-400 font-medium">
                Menampilkan <span class="font-bold text-slate-700">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}</span>
                dari <span class="font-bold text-slate-700">{{ $transactions->total() }}</span> transaksi
            </p>
            <div class="flex gap-2">
                {{ $transactions->links() }}
            </div>
        </div>
        @endif
    </div>
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
                if (s === 'baru') return 'bg-amber-100 text-amber-700';
                if (['cuci','kering','setrika'].includes(s)) return 'bg-blue-100 text-blue-700';
                return 'bg-emerald-100 text-emerald-700';
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
                    row.classList.add('bg-emerald-50');
                    setTimeout(() => row.classList.remove('bg-emerald-50'), 2000);
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
        div.className = 'admin-order-item grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100 relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-700 shadow-sm transition-colors">✕</button>
            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Layanan</label>
                <select name="items[${idx}][service_id]" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs bg-white focus:ring-2 focus:ring-brand outline-none">
                    <option value="">-- Pilih --</option>${opts}
                </select>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Jumlah</label>
                <input type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" placeholder="2.5" required
                       class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand outline-none">
            </div>`;
        document.getElementById('adminOrderItems').appendChild(div);
    }
</script>
@endsection
