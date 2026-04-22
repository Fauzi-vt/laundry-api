@extends('layouts.user')
@section('title', 'Beranda')

@section('content')

{{-- ══ WELCOME STRIP ══ --}}
<div class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-brand rounded-2xl px-8 py-7 mb-6 overflow-hidden text-white shadow-xl shadow-blue-900/20">
    {{-- Background blobs --}}
    <div class="absolute -right-16 -top-16 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-24 bottom-0 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>
    {{-- Grid pattern --}}
    <div class="absolute inset-0 opacity-5" style="background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M0 0h1v40H0V0zm40 0h1v40h-1V0zM0 0v1h40V0H0zm0 40v1h40v-1H0z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")"></div>

    <div class="relative z-10 flex flex-wrap items-center justify-between gap-5">
        <div>
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-widest mb-1">Selamat Datang</p>
            <h1 class="text-2xl sm:text-3xl font-bold mb-1">Halo, {{ explode(' ', trim($user->name))[0] }}! 👋</h1>
            <p class="text-blue-200/80 text-sm max-w-sm">Kelola pesanan laundry Anda dengan mudah dan cepat.</p>
            @if($user->address)
            <div class="mt-3 inline-flex items-center gap-1.5 bg-white/10 rounded-lg px-2.5 py-1 text-xs text-blue-100 border border-white/10">
                <svg class="w-3 h-3 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ Str::limit($user->address, 45) }}
            </div>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('user.order') }}"
               class="flex items-center gap-2 bg-white text-brand font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-blue-50 transition shadow-lg shadow-black/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Pesanan
            </a>
            <a href="{{ route('user.status') }}"
               class="flex items-center gap-2 bg-white/15 text-white font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-white/25 transition border border-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Status Cucian
                @if($activeOrders > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $activeOrders }}</span>
                @endif
            </a>
        </div>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php
    $stats = [
        ['label' => 'Total Pesanan',   'val' => $transactions->count(),   'sub' => 'order',   'color' => 'blue',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => 'Diproses',        'val' => $activeOrders,            'sub' => 'aktif',   'color' => 'amber',  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Selesai',         'val' => $completedOrders,         'sub' => 'selesai', 'color' => 'emerald','icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Total Pengeluaran','val' => 'Rp '.number_format($totalSpent,0,',','.'), 'sub' => '', 'color' => 'violet', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    @endphp
    @foreach($stats as $s)
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-lg bg-{{ $s['color'] }}-50 text-{{ $s['color'] }}-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
        </div>
        <p class="text-xs text-slate-400 font-medium">{{ $s['label'] }}</p>
        <p class="text-xl font-bold text-slate-900 mt-0.5 leading-tight">{{ $s['val'] }}</p>
        @if($s['sub'])<p class="text-xs text-slate-400 mt-0.5">{{ $s['sub'] }}</p>@endif
    </div>
    @endforeach
</div>

{{-- ══ LAYANAN PER KATEGORI ══ --}}
@if($services->count())
@php $grouped = $services->groupBy('category'); @endphp
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Kategori & Jenis Cucian</h2>
            <p class="text-xs text-slate-400 mt-0.5">Klik "Pesan" untuk langsung order</p>
        </div>
        <a href="{{ route('user.layanan') }}" class="text-xs font-semibold text-brand hover:underline">Lihat semua →</a>
    </div>

    @php
    $catCfg = [
        'Kiloan'          => ['accent' => '#2563eb', 'bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'icon' => '👕', 'border' => 'border-blue-100'],
        'Linen & Selimut' => ['accent' => '#7c3aed', 'bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'icon' => '🛏️', 'border' => 'border-violet-100'],
        'Sepatu & Tas'    => ['accent' => '#ea580c', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'icon' => '👟', 'border' => 'border-orange-100'],
        'Setrika'         => ['accent' => '#059669', 'bg' => 'bg-emerald-50','text' => 'text-emerald-600','icon' => '👔', 'border' => 'border-emerald-100'],
    ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($grouped as $catName => $items)
        @php $c = $catCfg[$catName] ?? ['accent'=>'#64748b','bg'=>'bg-slate-50','text'=>'text-slate-600','icon'=>'🧺','border'=>'border-slate-100']; @endphp
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Category header --}}
            <div class="flex items-center gap-3 px-5 py-3.5 border-b {{ $c['border'] }} {{ $c['bg'] }}">
                <span class="text-xl">{{ $c['icon'] }}</span>
                <div class="flex-1">
                    <p class="text-sm font-bold {{ $c['text'] }}">{{ $catName }}</p>
                    <p class="text-[11px] text-slate-400">{{ $items->count() }} layanan</p>
                </div>
                <a href="{{ route('user.order') }}"
                   class="text-[11px] font-semibold {{ $c['text'] }} px-2.5 py-1 rounded-lg {{ $c['bg'] }} border {{ $c['border'] }} hover:opacity-80 transition">
                    Pesan →
                </a>
            </div>
            {{-- Items --}}
            <div class="divide-y divide-slate-50">
                @foreach($items as $svc)
                <div class="flex items-center gap-3 px-5 py-3 group hover:bg-slate-50 transition">
                    <span class="text-base flex-shrink-0">{{ $svc->icon }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 leading-snug">{{ $svc->name }}</p>
                        @if($svc->description)
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $svc->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-900">Rp {{ number_format($svc->price,0,',','.') }}</p>
                            <p class="text-[11px] text-slate-400">/ {{ $svc->unit }}</p>
                        </div>
                        <a href="{{ route('user.order', ['service_id' => $svc->id]) }}"
                           class="hidden sm:flex w-8 h-8 items-center justify-center {{ $c['bg'] }} {{ $c['text'] }} rounded-lg border {{ $c['border'] }} hover:opacity-70 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ PESANAN AKTIF ══ --}}
@if($activeOrders > 0)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
            <h3 class="text-sm font-bold text-slate-900">Pesanan Sedang Diproses</h3>
            <span class="text-[11px] font-semibold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ $activeOrders }}</span>
        </div>
        <a href="{{ route('user.status') }}" class="text-xs font-semibold text-brand hover:underline">Detail Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nota</th>
                    <th class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tanggal</th>
                    <th class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Layanan</th>
                    <th class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($activeTransactions as $aTrx)
                @php
                $stClass = match($aTrx->status) {
                    'cuci'    => 'badge-blue',
                    'kering'  => 'bg-cyan-100 text-cyan-700',
                    'setrika' => 'badge-orange',
                    default   => 'badge-gray'
                };
                $stLabel = match($aTrx->status) {
                    'cuci'    => '🫧 Dicuci',
                    'kering'  => '💨 Kering',
                    'setrika' => '👔 Setrika',
                    default   => $aTrx->status
                };
                @endphp
                <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('user.show', $aTrx->id) }}'">
                    <td class="px-5 py-3.5 font-semibold text-brand text-sm">{{ $aTrx->invoice_code }}</td>
                    <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">{{ $aTrx->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        @foreach($aTrx->details->take(2) as $d)
                        <span class="inline-block text-[11px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full mr-1">{{ $d->service->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $stClass }}">{{ $stLabel }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold text-slate-900">Rp {{ number_format($aTrx->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ══ PESANAN TERKINI ══ --}}
@if($latestTransaction)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="flex flex-wrap items-start justify-between gap-3 mb-5 pb-5 border-b border-slate-100">
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Pesanan Terakhir</p>
            <h3 class="text-base font-bold text-slate-900">
                <a href="{{ route('user.show', $latestTransaction->id) }}" class="hover:text-brand transition">{{ $latestTransaction->invoice_code }}</a>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ $latestTransaction->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        @php
        $stColors = [
            'baru'    => 'badge-yellow',
            'cuci'    => 'badge-blue',
            'kering'  => 'bg-cyan-100 text-cyan-700',
            'setrika' => 'badge-orange',
            'selesai' => 'badge-green',
            'diambil' => 'badge-green',
        ];
        @endphp
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase {{ $stColors[$latestTransaction->status] ?? 'badge-gray' }}">
            {{ $latestTransaction->status }}
        </span>
    </div>

    {{-- Progress tracker --}}
    @php
        $steps = [
            ['key'=>'baru',   'label'=>'Diterima',    'icon'=>'✅'],
            ['key'=>'cuci',   'label'=>'Dicuci',      'icon'=>'🫧'],
            ['key'=>'kering', 'label'=>'Dikeringkan', 'icon'=>'💨'],
            ['key'=>'setrika','label'=>'Disetrika',   'icon'=>'👔'],
            ['key'=>'selesai','label'=>'Selesai',     'icon'=>'🎉'],
        ];
        $sOrd = ['baru'=>0,'cuci'=>1,'kering'=>2,'setrika'=>3,'selesai'=>4,'diambil'=>4];
        $curr = $sOrd[$latestTransaction->status] ?? 0;
    @endphp
    <div class="relative flex justify-between items-start">
        {{-- Track --}}
        <div class="absolute top-4 left-4 right-4 h-0.5 bg-slate-100 z-0">
            <div class="h-full bg-brand rounded-full transition-all duration-700" style="width:{{ ($curr/4)*100 }}%"></div>
        </div>
        @foreach($steps as $i => $step)
        <div class="flex flex-col items-center gap-2 z-10 flex-1">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-sm
                        {{ $i <= $curr ? 'bg-brand text-white shadow-blue-200' : 'bg-slate-100 text-slate-400' }} transition-all">
                {{ $step['icon'] }}
            </div>
            <span class="text-[10px] font-semibold hidden sm:block text-center {{ $i <= $curr ? 'text-brand' : 'text-slate-400' }}">{{ $step['label'] }}</span>
        </div>
        @endforeach
    </div>

    <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-3 gap-4 text-sm">
        <div><p class="text-xs text-slate-400">Masuk</p><p class="font-semibold text-slate-800 mt-0.5">{{ $latestTransaction->created_at->format('d M Y') }}</p></div>
        <div><p class="text-xs text-slate-400">Est. Selesai</p><p class="font-semibold text-slate-800 mt-0.5">{{ $latestTransaction->created_at->addDays(2)->format('d M Y') }}</p></div>
        <div><p class="text-xs text-slate-400">Total Tagihan</p><p class="font-bold text-brand text-base mt-0.5">Rp {{ number_format($latestTransaction->total_price,0,',','.') }}</p></div>
    </div>
</div>
@else
<div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
    <div class="text-5xl mb-4">🧺</div>
    <h3 class="text-base font-bold text-slate-700">Belum Ada Pesanan</h3>
    <p class="text-sm text-slate-400 mt-1 mb-5">Yuk mulai pesan laundry pertama Anda!</p>
    <a href="{{ route('user.order') }}" class="inline-flex items-center gap-2 bg-brand text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-brand-dark transition text-sm shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Pesanan Sekarang
    </a>
</div>
@endif

@endsection
