<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rumah Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#1e40af', secondary: '#0ea5e9' }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
        }
        .print-only { display: none; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen">

    {{-- ════════════════════════════════════════
         MODAL: DETAIL TRANSAKSI
    ════════════════════════════════════════ --}}
    <div x-data="detailModal()" @open-detail.window="open($event.detail)" x-cloak>
        <div x-show="show" class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>

            {{-- Panel --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl z-10 overflow-hidden">

                {{-- Print Header (hanya tampil saat print) --}}
                <div class="print-only p-6 border-b">
                    <h1 class="text-xl font-black">Rumah Laundry Tasikmalaya</h1>
                    <p class="text-sm text-gray-500">Jl. Laundry No.1 • 081234567890</p>
                </div>

                {{-- Header Modal --}}
                <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-start no-print">
                    <div>
                        <p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Nota Transaksi</p>
                        <p class="text-xl font-black" x-text="trx.invoice_code"></p>
                    </div>
                    <button @click="show = false" class="text-white/70 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    {{-- Info utama --}}
                    <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
                        <div>
                            <p class="text-slate-400 font-medium text-xs uppercase tracking-wide mb-1">Pelanggan</p>
                            <p class="font-bold text-slate-800" x-text="trx.customer"></p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-medium text-xs uppercase tracking-wide mb-1">Tanggal Masuk</p>
                            <p class="font-bold text-slate-800" x-text="trx.created_at"></p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-medium text-xs uppercase tracking-wide mb-1">Status Cucian</p>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize"
                                  :class="statusClass(trx.status)" x-text="trx.status"></span>
                        </div>
                        <div>
                            <p class="text-slate-400 font-medium text-xs uppercase tracking-wide mb-1">Status Bayar</p>
                            <span :class="trx.status === 'baru' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                  class="px-2.5 py-1 rounded-full text-xs font-bold"
                                  x-text="trx.status === 'baru' ? 'Belum Bayar' : 'Lunas'"></span>
                        </div>
                    </div>

                    {{-- Rincian Item --}}
                    <div class="border border-slate-200 rounded-xl overflow-hidden mb-5">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-bold text-slate-500 uppercase">Layanan</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-bold text-slate-500 uppercase">Jml</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-bold text-slate-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="d in trx.details" :key="d.id">
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-700" x-text="d.service?.name ?? '-'"></td>
                                        <td class="px-4 py-3 text-center text-slate-500" x-text="d.quantity + ' ' + (d.service?.unit ?? '')"></td>
                                        <td class="px-4 py-3 text-right font-bold" x-text="'Rp ' + Number(d.subtotal).toLocaleString('id-ID')"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 font-black text-slate-900">TOTAL</td>
                                    <td class="px-4 py-3 text-right font-black text-primary text-base" x-text="'Rp ' + Number(trx.total_price).toLocaleString('id-ID')"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Footer nota --}}
                    <p class="text-center text-xs text-slate-400 mb-5">Terima kasih telah mempercayakan cucian Anda kepada Rumah Laundry Tasikmalaya 🙏</p>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 no-print">
                        <button @click="show = false"
                                class="flex-1 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">
                            Tutup
                        </button>
                        <button onclick="window.print()"
                                class="flex-1 py-2.5 rounded-xl bg-primary text-white font-bold text-sm hover:bg-blue-800 transition flex items-center justify-center gap-2">
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

    {{-- ════════════════════════════════════════
         MODAL: TAMBAH TRANSAKSI
    ════════════════════════════════════════ --}}
    <div x-data="{ showAddModal: false }" @keydown.escape.window="showAddModal = false">
        {{-- Trigger dari tombol luar --}}
        <span x-on:open-add-modal.window="showAddModal = true"></span>

        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddModal = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-center">
                    <div>
                        <p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Admin</p>
                        <p class="text-xl font-black">Tambah Transaksi Baru</p>
                    </div>
                    <button @click="showAddModal = false" class="text-white/70 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('orders.admin.store') }}" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Pelanggan</label>
                        <select name="user_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none bg-white">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach(\App\Models\User::where('role','user')->orderBy('name')->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? $c->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="adminOrderItems" class="space-y-3">
                        <div class="admin-order-item grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Layanan</label>
                                <select name="items[0][service_id]" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary outline-none">
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach($services as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} — Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah</label>
                                <input type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="2.5" required
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addAdminItem()" class="w-full py-2.5 border-2 border-dashed border-slate-300 rounded-xl text-sm font-semibold text-slate-400 hover:border-primary hover:text-primary transition">
                        + Tambah Layanan
                    </button>
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg">
                        Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         NAVBAR
    ════════════════════════════════════════ --}}
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-40 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">L</div>
                    <span class="font-bold text-xl text-slate-900 tracking-tight">Laundry Admin</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">Halo, {{ $user->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'monitoring' }">

        {{-- Flash success --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-5 py-3.5 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="mb-8 bg-white border border-slate-200 rounded-2xl p-1.5 shadow-sm no-print">
            <nav class="flex gap-1">
                @php
                $tabs = [
                    ['id'=>'monitoring', 'label'=>'Monitoring Cucian', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['id'=>'master',     'label'=>'Data Master',      'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['id'=>'laporan',    'label'=>'Laporan Keuangan', 'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
                @endphp
                @foreach($tabs as $t)
                <button @click="tab = '{{ $t['id'] }}'"
                    :class="tab === '{{ $t['id'] }}' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $t['icon'] }}"/>
                    </svg>
                    {{ $t['label'] }}
                </button>
                @endforeach
            </nav>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             TAB 1: MONITORING CUCIAN
        ═══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'monitoring'" x-transition>

            {{-- ── Stats Cards ── --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @php
                $cards = [
                    ['label'=>'Pendapatan Hari Ini',       'val'=>'Rp '.number_format($todayRevenue,0,',','.'),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'blue'],
                    ['label'=>'Total Selesai',              'val'=>$totalDone.' Order',   'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'green'],
                    ['label'=>'Sedang Diproses',            'val'=>$totalActive.' Cucian','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'orange'],
                    ['label'=>'Total Pelanggan',            'val'=>$customers.' Orang',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'purple'],
                ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition group">
                    <div class="w-12 h-12 rounded-xl bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $card['label'] }}</p>
                        <p class="text-xl font-black text-slate-900 mt-0.5">{{ $card['val'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ── Toolbar: Search + Filter + Tambah ── --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap gap-3 items-center justify-between bg-slate-50/60">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        Monitoring Status Cucian
                    </h3>
                    <div class="flex flex-wrap gap-2 items-center">
                        {{-- Search --}}
                        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2" id="filterForm">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                                       class="pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none w-52">
                            </div>
                            {{-- Filter Status --}}
                            <select name="status_filter" onchange="document.getElementById('filterForm').submit()"
                                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary outline-none">
                                <option value="">Semua Status</option>
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}" {{ request('status_filter') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-2 bg-slate-700 text-white rounded-lg text-sm font-semibold hover:bg-slate-900 transition">Cari</button>
                            @if(request('search') || request('status_filter'))
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">Reset</a>
                            @endif
                        </form>
                        {{-- Tambah Transaksi --}}
                        <button @click="$dispatch('open-add-modal')"
                                class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-blue-800 transition shadow flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Transaksi Baru
                        </button>
                    </div>
                </div>

                {{-- ── Tabel ── --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status Cucian</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pembayaran</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Update Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($transactions as $trx)
                            @php
                                $isPaid   = in_array($trx->status, ['cuci','kering','setrika','selesai','diambil']);
                                $isActive = in_array($trx->status, ['cuci','kering','setrika']);
                                // Estimasi selesai: +2 hari dari tanggal masuk
                                $estimate = $trx->created_at->addDays(2);
                            @endphp
                            <tr class="hover:bg-slate-50 transition" id="trx-{{ $trx->id }}"
                                x-data="{ currentStatus: '{{ $trx->status }}', loading: false }">

                                {{-- Invoice --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-bold text-primary text-sm">{{ $trx->invoice_code }}</span>
                                </td>

                                {{-- Pelanggan --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                                    {{ $trx->user->name ?? '-' }}
                                </td>

                                {{-- Tanggal Masuk + Est. Selesai --}}
                                <td class="px-4 py-4 whitespace-nowrap text-xs">
                                    <p class="font-semibold text-slate-700">{{ $trx->created_at->format('d M Y') }}</p>
                                    <p class="text-slate-400 mt-0.5">Est: {{ $estimate->format('d M Y') }}</p>
                                </td>

                                {{-- Total --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-slate-800">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </td>

                                {{-- Status Cucian --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize"
                                          :class="{
                                              'bg-yellow-100 text-yellow-700': currentStatus === 'baru',
                                              'bg-blue-100 text-blue-700 animate-pulse': ['cuci','kering','setrika'].includes(currentStatus),
                                              'bg-green-100 text-green-700': ['selesai','diambil'].includes(currentStatus)
                                          }"
                                          x-text="currentStatus"></span>
                                </td>

                                {{-- Status Pembayaran --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span :class="currentStatus === 'baru' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'"
                                          class="px-2.5 py-1 rounded-full text-xs font-bold"
                                          x-text="currentStatus === 'baru' ? 'Belum Bayar' : 'Lunas'"></span>
                                </td>

                                {{-- Dropdown Update Status --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <select x-model="currentStatus"
                                            @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                            :disabled="loading"
                                            class="py-1.5 px-2 border border-slate-300 bg-white rounded-lg text-xs font-semibold focus:ring-2 focus:ring-primary outline-none disabled:opacity-50 disabled:cursor-wait">
                                        @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                        <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- Tombol Detail / Nota --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <button onclick="openDetail({{ $trx->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-primary hover:text-white text-slate-600 rounded-lg text-xs font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Nota
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="text-4xl mb-3">🔍</div>
                                    <p class="font-bold text-slate-600">Tidak ada transaksi ditemukan</p>
                                    <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── Pagination ── --}}
                @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/40">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span class="font-bold">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}</span>
                        dari <span class="font-bold">{{ $transactions->total() }}</span> transaksi
                    </p>
                    <div class="flex gap-1">
                        {{-- Previous --}}
                        @if($transactions->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 cursor-not-allowed">‹ Prev</span>
                        @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition">‹ Prev</a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if($page == $transactions->currentPage())
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-primary text-white">{{ $page }}</span>
                        @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition">{{ $page }}</a>
                        @endif
                        @endforeach

                        {{-- Next --}}
                        @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition">Next ›</a>
                        @else
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 cursor-not-allowed">Next ›</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>{{-- end tab monitoring --}}

        {{-- ═══════════════════════════════════════════════════════════
             TAB 2: DATA MASTER
        ═══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'master'" x-transition x-cloak
             x-data="{
                /* ── Pelanggan ── */
                showAddCustomer: false,
                showEditCustomer: false,
                showDeleteCustomer: false,
                showCustomerTrx: false,
                editCustomer: { id:'', name:'', email:'', phone:'', address:'' },
                deleteCustomer: { id:'', name:'' },
                customerTrx: { customer:{}, transactions:[] },
                customerTrxLoading: false,
                customerSearch: '{{ request('cust_search') }}',

                openEditCustomer(c) { this.editCustomer = {...c}; this.showEditCustomer = true; },
                openDeleteCustomer(id, name) { this.deleteCustomer = {id, name}; this.showDeleteCustomer = true; },
                async openCustomerTrx(id) {
                    this.showCustomerTrx = true;
                    this.customerTrxLoading = true;
                    this.customerTrx = { customer:{}, transactions:[] };
                    const r = await fetch(`/admin/customers/${id}/trx`, { headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' } });
                    const d = await r.json();
                    this.customerTrx = d;
                    this.customerTrxLoading = false;
                },

                /* ── Layanan ── */
                showAddService: false,
                showEditService: false,
                showDeleteService: false,
                editService: { id:'', name:'', price:'', unit:'' },
                deleteService: { id:'', name:'' },

                openEditService(s) { this.editService = {...s}; this.showEditService = true; },
                openDeleteService(id, name) { this.deleteService = {id, name}; this.showDeleteService = true; },
             }"
             @keydown.escape.window="showAddCustomer=showEditCustomer=showDeleteCustomer=showCustomerTrx=showAddService=showEditService=showDeleteService=false">

            {{-- ════════════════════ MODALS PELANGGAN ════════════════════ --}}

            {{-- Modal: Tambah Pelanggan --}}
            <div x-show="showAddCustomer" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddCustomer = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-center">
                        <div><p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Data Master</p><p class="text-lg font-black">Tambah Pelanggan</p></div>
                        <button @click="showAddCustomer = false" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form method="POST" action="{{ route('admin.customers.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Nama Lengkap *</label>
                                <input type="text" name="name" required placeholder="Budi Santoso" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Email *</label>
                                <input type="email" name="email" required placeholder="budi@email.com" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">No. HP</label>
                                <input type="text" name="phone" placeholder="08123456789" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Password *</label>
                                <input type="password" name="password" required placeholder="Min 8 karakter" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Alamat</label>
                                <textarea name="address" rows="2" placeholder="Jl. Contoh No. 1, Kota..." class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-blue-800 transition shadow">Simpan Pelanggan</button>
                    </form>
                </div>
            </div>

            {{-- Modal: Edit Pelanggan --}}
            <div x-show="showEditCustomer" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEditCustomer = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-600 to-amber-500 text-white flex justify-between items-center">
                        <div><p class="text-xs text-amber-100 font-bold uppercase tracking-widest mb-1">Edit Data</p><p class="text-lg font-black">Edit Pelanggan</p></div>
                        <button @click="showEditCustomer = false" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form method="POST" :action="`/admin/customers/${editCustomer.id}`" class="p-6 space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Nama Lengkap *</label>
                                <input type="text" name="name" required x-model="editCustomer.name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Email *</label>
                                <input type="email" name="email" required x-model="editCustomer.email" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">No. HP</label>
                                <input type="text" name="phone" x-model="editCustomer.phone" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Alamat</label>
                                <textarea name="address" rows="2" x-model="editCustomer.address" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none resize-none"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            {{-- Modal: Hapus Pelanggan --}}
            <div x-show="showDeleteCustomer" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteCustomer = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 text-center">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-1">Hapus Pelanggan</h3>
                    <p class="text-sm text-slate-500 mb-6">Yakin ingin menghapus <strong x-text="deleteCustomer.name"></strong>? Semua data transaksinya juga bisa terpengaruh.</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteCustomer = false" class="flex-1 py-2.5 border border-slate-300 rounded-xl text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">Batal</button>
                        <form method="POST" :action="`/admin/customers/${deleteCustomer.id}`" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-2.5 bg-red-500 text-white font-bold rounded-xl hover:bg-red-700 transition text-sm">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal: Riwayat Transaksi Pelanggan --}}
            <div x-show="showCustomerTrx" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showCustomerTrx = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden max-h-[85vh] flex flex-col">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-center flex-shrink-0">
                        <div>
                            <p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Riwayat Transaksi</p>
                            <p class="text-lg font-black" x-text="customerTrx.customer?.name ?? 'Pelanggan'"></p>
                            <p class="text-xs text-blue-200 mt-0.5" x-text="(customerTrx.customer?.phone ?? '') + ' • ' + (customerTrx.customer?.email ?? '')"></p>
                        </div>
                        <button @click="showCustomerTrx = false" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <div class="overflow-y-auto flex-1 p-5">
                        <div x-show="customerTrxLoading" class="py-12 text-center text-slate-400">
                            <svg class="animate-spin w-8 h-8 mx-auto mb-3 text-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Memuat data...
                        </div>
                        <template x-if="!customerTrxLoading && customerTrx.transactions?.length === 0">
                            <div class="py-12 text-center text-slate-400"><div class="text-4xl mb-3">📭</div><p class="font-bold">Belum ada transaksi</p></div>
                        </template>
                        <template x-if="!customerTrxLoading && customerTrx.transactions?.length > 0">
                            <div class="space-y-3">
                                <template x-for="trx in customerTrx.transactions" :key="trx.id">
                                    <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                                        <div class="flex justify-between items-center px-4 py-3 border-b border-slate-100">
                                            <div>
                                                <p class="font-bold text-primary text-sm" x-text="trx.invoice_code"></p>
                                                <p class="text-xs text-slate-400" x-text="trx.created_at"></p>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize"
                                                      :class="trx.status==='baru'?'bg-yellow-100 text-yellow-700':['cuci','kering','setrika'].includes(trx.status)?'bg-blue-100 text-blue-700':'bg-green-100 text-green-700'"
                                                      x-text="trx.status"></span>
                                                <p class="font-black text-sm text-slate-800 mt-1" x-text="'Rp '+Number(trx.total_price).toLocaleString('id-ID')"></p>
                                            </div>
                                        </div>
                                        <ul class="px-4 py-2 space-y-1">
                                            <template x-for="d in trx.details" :key="d.service">
                                                <li class="flex justify-between text-xs text-slate-600">
                                                    <span x-text="d.service + ' (' + d.qty + ' ' + d.unit + ')'"></span>
                                                    <span class="font-bold" x-text="'Rp '+Number(d.subtotal).toLocaleString('id-ID')"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ════════════════════ MODALS LAYANAN ════════════════════ --}}

            {{-- Modal: Tambah Layanan --}}
            <div x-show="showAddService" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddService = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-center">
                        <div><p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Data Master</p><p class="text-lg font-black">Tambah Layanan</p></div>
                        <button @click="showAddService = false" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form method="POST" action="{{ route('admin.services.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Nama Layanan *</label>
                            <input type="text" name="name" required placeholder="Cuci Kiloan Ekspres" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Harga (Rp) *</label>
                                <input type="number" name="price" required min="0" placeholder="6000" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Satuan *</label>
                                <select name="unit" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none bg-white">
                                    <option value="kg">kg</option>
                                    <option value="pcs">pcs</option>
                                    <option value="pasang">pasang</option>
                                    <option value="item">item</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-blue-800 transition shadow">Tambah Layanan</button>
                    </form>
                </div>
            </div>

            {{-- Modal: Edit Layanan --}}
            <div x-show="showEditService" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEditService = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-600 to-amber-500 text-white flex justify-between items-center">
                        <div><p class="text-xs text-amber-100 font-bold uppercase tracking-widest mb-1">Edit Data</p><p class="text-lg font-black">Edit Layanan</p></div>
                        <button @click="showEditService = false" class="text-white/70 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form method="POST" :action="`/admin/services/${editService.id}`" class="p-6 space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Nama Layanan *</label>
                            <input type="text" name="name" required x-model="editService.name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Harga (Rp) *</label>
                                <input type="number" name="price" required min="0" x-model="editService.price" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Satuan *</label>
                                <select name="unit" required x-model="editService.unit" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-white">
                                    <option value="kg">kg</option>
                                    <option value="pcs">pcs</option>
                                    <option value="pasang">pasang</option>
                                    <option value="item">item</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            {{-- Modal: Hapus Layanan --}}
            <div x-show="showDeleteService" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteService = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 text-center">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-1">Hapus Layanan</h3>
                    <p class="text-sm text-slate-500 mb-6">Yakin menghapus layanan <strong x-text="deleteService.name"></strong>?</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteService = false" class="flex-1 py-2.5 border border-slate-300 rounded-xl text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">Batal</button>
                        <form method="POST" :action="`/admin/services/${deleteService.id}`" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-2.5 bg-red-500 text-white font-bold rounded-xl hover:bg-red-700 transition text-sm">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ════════════════════ KONTEN DATA MASTER ════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ───── Card: Data Pelanggan ───── --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/60 flex justify-between items-center">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Data Pelanggan</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Klik nama untuk lihat riwayat</p>
                        </div>
                        <button @click="showAddCustomer = true"
                                class="flex items-center gap-1.5 px-3 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-blue-800 transition shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah
                        </button>
                    </div>
                    {{-- Search --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="px-5 py-3 border-b border-slate-100 flex gap-2">
                        <input type="hidden" name="tab_master" value="1">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('status_filter')) <input type="hidden" name="status_filter" value="{{ request('status_filter') }}"> @endif
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="cust_search" placeholder="Cari nama atau email..." value="{{ request('cust_search') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                        </div>
                        <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition">Cari</button>
                        @if(request('cust_search'))
                        <a href="{{ route('dashboard', array_filter(['search'=>request('search'),'status_filter'=>request('status_filter'),'tab_master'=>'1'])) }}" class="px-3 py-2 bg-red-50 text-red-500 rounded-lg text-xs font-bold hover:bg-red-100 transition">✕</a>
                        @endif
                    </form>
                    {{-- List --}}
                    <ul class="divide-y divide-slate-100 overflow-y-auto flex-1" style="max-height: 400px">
                        @php
                            $custQuery = \App\Models\User::where('role','user')->withCount('transactions')->orderBy('name');
                            if(request('cust_search')) {
                                $s = request('cust_search');
                                $custQuery->where(fn($q)=>$q->where('name','like',"%$s%")->orWhere('email','like',"%$s%"));
                            }
                            $customers_list = $custQuery->get();
                        @endphp
                        @forelse($customers_list as $c)
                        <li class="px-5 py-3.5 hover:bg-slate-50 transition">
                            <div class="flex justify-between items-start">
                                {{-- Info klik untuk detail --}}
                                <button @click="openCustomerTrx({{ $c->id }})" class="text-left flex-1 group">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition">{{ $c->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $c->email }}</p>
                                    <div class="flex gap-3 mt-1 text-xs text-slate-400">
                                        <span>📞 {{ $c->phone ?? '—' }}</span>
                                        <span>📍 {{ Str::limit($c->address ?? '—', 30) }}</span>
                                    </div>
                                    <span class="inline-block mt-1.5 px-2 py-0.5 bg-blue-50 text-primary text-xs font-bold rounded-full">
                                        {{ $c->transactions_count }} transaksi →
                                    </span>
                                </button>
                                {{-- Aksi Edit & Hapus --}}
                                <div class="flex gap-1 ml-3 flex-shrink-0">
                                    <button @click="openEditCustomer({ id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', email: '{{ addslashes($c->email) }}', phone: '{{ addslashes($c->phone ?? '') }}', address: '{{ addslashes($c->address ?? '') }}' })"
                                            class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteCustomer({{ $c->id }}, '{{ addslashes($c->name) }}')"
                                            class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-12 text-center text-sm text-slate-400">
                            <div class="text-3xl mb-2">👤</div>
                            {{ request('cust_search') ? 'Pelanggan tidak ditemukan.' : 'Belum ada pelanggan terdaftar.' }}
                        </li>
                        @endforelse
                    </ul>
                    <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400 bg-slate-50/40">
                        Total: <strong>{{ $customers_list->count() }}</strong> pelanggan
                    </div>
                </div>

                {{-- ───── Card: Data Layanan ───── --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/60 flex justify-between items-center">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Data Layanan (Jasa)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Kelola harga & jenis layanan</p>
                        </div>
                        <button @click="showAddService = true"
                                class="flex items-center gap-1.5 px-3 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-blue-800 transition shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah
                        </button>
                    </div>
                    {{-- List --}}
                    <ul class="divide-y divide-slate-100 overflow-y-auto flex-1" style="max-height: 460px">
                        @forelse($services as $s)
                        <li class="px-5 py-4 hover:bg-slate-50 transition flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-primary flex items-center justify-center text-sm flex-shrink-0">
                                    @if(str_contains(strtolower($s->name),'sepatu')) 👟
                                    @elseif(str_contains(strtolower($s->name),'selimut')||str_contains(strtolower($s->name),'bedcover')) 🛏️
                                    @elseif(str_contains(strtolower($s->name),'kilat')) ⚡
                                    @else 👕
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $s->name }}</p>
                                    <p class="text-xs text-slate-400">per {{ $s->unit }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-primary">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                                {{-- Aksi Edit & Hapus --}}
                                <div class="flex gap-1">
                                    <button @click="openEditService({ id: {{ $s->id }}, name: '{{ addslashes($s->name) }}', price: {{ $s->price }}, unit: '{{ $s->unit }}' })"
                                            class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteService({{ $s->id }}, '{{ addslashes($s->name) }}')"
                                            class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-12 text-center text-sm text-slate-400">
                            <div class="text-3xl mb-2">🏷️</div>
                            Belum ada layanan. Tambahkan sekarang!
                        </li>
                        @endforelse
                    </ul>
                    <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400 bg-slate-50/40">
                        Total: <strong>{{ $services->count() }}</strong> jenis layanan
                    </div>
                </div>

            </div>{{-- end grid --}}

            {{-- Auto-open tab jika redirect ke tab_master --}}
            @if(request('tab_master'))
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => {
                        const el = document.querySelector('[x-data*="tab:"]');
                        if(el && el._x_dataStack) el._x_dataStack[0].tab = 'master';
                    }, 50);
                });
            </script>
            @endif

        </div>{{-- end tab master --}}

        {{-- ═══════════════════════════════════════════════════════════
             TAB 3: LAPORAN KEUANGAN
        ═══════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'laporan'" x-transition x-cloak>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Laporan Keuangan</h3>
                    <span class="text-xs text-slate-400 font-medium">Akumulasi dari semua transaksi selesai</span>
                </div>
                <div class="p-8 text-center border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Akumulasi Pendapatan</p>
                    <p class="text-5xl font-black text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-sm text-slate-400 mt-2">Dari transaksi berstatus <strong>Selesai</strong> dan <strong>Diambil</strong></p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Rincian</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $laporanTrx = \App\Models\Transaction::with(['user','details.service'])
                                    ->whereIn('status', ['selesai','diambil'])
                                    ->orderBy('updated_at','desc')->get();
                            @endphp
                            @forelse($laporanTrx as $trx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $trx->updated_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-primary">{{ $trx->invoice_code }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $trx->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    <ul class="list-disc pl-4 space-y-0.5">
                                        @foreach($trx->details as $d)
                                        <li>{{ $d->service->name }} ({{ $d->quantity }} {{ $d->service->unit }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-slate-900">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada transaksi selesai.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- end container --}}

    {{-- ═══════════ DATA TRANSAKSI (untuk JS modal detail) ═══════════ --}}
    @php
        $trxJson = \App\Models\Transaction::with(['user','details.service'])
            ->orderBy('created_at','desc')
            ->get()
            ->map(function($t) {
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
            })->values();

    $servicesJson = $services->map(function($s) {
        return ['id' => $s->id, 'name' => $s->name, 'price' => $s->price, 'unit' => $s->unit];
    })->values();
    @endphp

    <script>
    // ── Data transaksi lengkap untuk modal ──────────────────────────
    const allTransactions = @json($trxJson);

    // ── Alpine: Detail Modal ──────────────────────────────────────
    function detailModal() {
        return {
            show: false,
            trx: { invoice_code:'', customer:'', status:'', total_price:0, created_at:'', details:[] },
            open(data) {
                this.trx = data;
                this.show = true;
            },
            statusClass(s) {
                if (s === 'baru') return 'bg-yellow-100 text-yellow-700';
                if (['cuci','kering','setrika'].includes(s)) return 'bg-blue-100 text-blue-700';
                return 'bg-green-100 text-green-700';
            }
        };
    }

    // ── Buka modal detail ─────────────────────────────────────────
    function openDetail(id) {
        const trx = allTransactions.find(t => t.id === id);
        if (!trx) return;
        window.dispatchEvent(new CustomEvent('open-detail', { detail: trx }));
    }

    // ── Update status via PATCH web route ─────────────────────────
    async function updateStatus(id, newStatus, selectEl) {
        const row = document.getElementById(`trx-${id}`);
        if (row) {
            const comp = row._x_dataStack?.[0];
            if (comp) comp.loading = true;
        }
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
                // Perbarui data lokal supaya modal detail ikut sinkron
                const trx = allTransactions.find(t => t.id === id);
                if (trx) trx.status = newStatus;

                // Flash hijau di baris
                if (row) {
                    row.style.transition = 'background 0.4s';
                    row.style.background = '#dcfce7';
                    setTimeout(() => row.style.background = '', 1800);
                }
            } else {
                const err = await res.json().catch(() => ({}));
                alert('Gagal update: ' + (err.message ?? 'Unknown error'));
                // Kembalikan dropdown ke status lama
                if (selectEl) selectEl.value = allTransactions.find(t => t.id === id)?.status ?? newStatus;
            }
        } catch (e) {
            console.error(e);
            alert('Kesalahan jaringan saat update status.');
        } finally {
            if (row) {
                const comp = row._x_dataStack?.[0];
                if (comp) comp.loading = false;
            }
        }
    }

    // ── Tambah item di modal tambah transaksi ─────────────────────
    let adminItemCount = 1;
    const adminServices = @json($servicesJson);

    function addAdminItem() {
        const idx = adminItemCount++;
        const opts = adminServices.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name} — Rp ${s.price.toLocaleString('id-ID')}/${s.unit}</option>`).join('');
        const div = document.createElement('div');
        div.className = 'admin-order-item grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200 relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-700">✕</button>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Layanan</label>
                <select name="items[${idx}][service_id]" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Pilih --</option>${opts}
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah</label>
                <input type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" placeholder="2.5" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>`;
        document.getElementById('adminOrderItems').appendChild(div);
    }
    </script>

</body>
</html>
