@extends('layouts.user')
@section('title', 'Beranda')

@section('styles')
<style>
    /* ── Stat Card shimmer on hover ── */
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0) 40%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0) 60%);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }
    .stat-card:hover::before { transform: translateX(100%); }
    .stat-card:hover { transform: translateY(-2px); }

    /* ── Hero Banner animated blobs ── */
    @keyframes blob-pulse {
        0%, 100% { transform: scale(1) translate(0, 0); opacity: .12; }
        50%       { transform: scale(1.15) translate(10px, -8px); opacity: .18; }
    }
    .hero-blob  { animation: blob-pulse 7s ease-in-out infinite; }
    .hero-blob-2{ animation: blob-pulse 9s ease-in-out infinite reverse; }

    /* ── Service row hover ── */
    .svc-row { transition: background 0.15s ease; }
    .svc-row:hover { background: #f8fafc; }

    /* ── Counter: scale pop on click ── */
    .counter-btn { transition: all 0.13s cubic-bezier(0.4, 0, 0.2, 1); }
    .counter-btn:active { transform: scale(0.82); }

    /*
     * Counter toggle:
     * The entire counter group transitions width so the
     * [ - ] N [ + ]  widget expands/collapses smoothly
     * without layout shift on the rest of the row.
     */
    .qty-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        /* Reserve space for the widest state: [−][00][+] */
        min-width: 28px;
        transition: min-width 0.2s ease;
    }
    .qty-wrap.expanded { min-width: 88px; }

    /* Number digit always the same width so it doesn't jitter */
    .qty-number {
        width: 20px;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    /* ── Progress stepper dot ── */
    .step-dot-active { box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); }

    /* ── Sticky cart slide-up transition ── */
    .sticky-cart-enter {
        animation: cartSlideUp 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
    .sticky-cart-leave {
        animation: cartSlideDown 0.2s ease-in both;
    }
    @keyframes cartSlideUp {
        from { transform: translateY(100%); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }
    @keyframes cartSlideDown {
        from { transform: translateY(0);    opacity: 1; }
        to   { transform: translateY(100%); opacity: 0; }
    }

    /* ── Cart item list inside sticky bar (desktop only) ── */
    .cart-item-pill {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }
</style>
@endsection

@section('content')

{{-- ════════════════════════════════════════════
     1. HERO BANNER — Sapaan & CTA
════════════════════════════════════════════ --}}
<section aria-label="Sapaan Pelanggan"
         class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-700
                rounded-2xl px-6 sm:px-10 py-8 mb-6 overflow-hidden text-white
                shadow-2xl shadow-blue-900/30">

    {{-- Decorative blobs --}}
    <div class="hero-blob absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="hero-blob-2 absolute right-32 bottom-0 w-52 h-52 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

    {{-- Subtle grid overlay --}}
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1' fill-rule='evenodd'%3E%3Cpath d='M0 0h1v40H0V0zm40 0h1v40h-1V0zM0 0v1h40V0H0zm0 40v1h40v-1H0z'/%3E%3C/g%3E%3C/svg%3E\")">
    </div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">

        {{-- Left: Greeting --}}
        <div>
            <p class="text-blue-300 text-[11px] font-bold uppercase tracking-widest mb-1.5">
                Selamat Datang
            </p>
            <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight mb-2">
                Halo, {{ explode(' ', trim($user->name))[0] }}! 👋
            </h1>
            <p class="text-blue-200/80 text-sm max-w-xs leading-relaxed">
                Kelola pesanan laundry Anda dengan mudah dan cepat.
            </p>

            {{-- Address badge --}}
            @if($user->address)
            <div class="mt-3 inline-flex items-center gap-1.5 bg-white/10 border border-white/15 rounded-lg px-3 py-1.5 text-xs text-blue-100">
                <svg class="w-3.5 h-3.5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ Str::limit($user->address, 48) }}</span>
            </div>
            @endif
        </div>

        {{-- Right: CTA Buttons --}}
        <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
            {{-- Primary CTA: Buat Pesanan (high-contrast orange/amber) --}}
            <a href="{{ route('user.order') }}"
               id="btn-buat-pesanan"
               class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900
                      font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/30
                      transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Pesanan
            </a>

            {{-- Secondary CTA: Status Cucian (semi-transparent outline) --}}
            <a href="{{ route('user.status') }}"
               id="btn-status-cucian"
               class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/25
                      text-white font-semibold text-sm px-5 py-2.5 rounded-xl backdrop-blur-sm
                      transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Status Cucian
                @if($activeOrders > 0)
                <span class="inline-flex items-center justify-center w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full ml-0.5">
                    {{ $activeOrders }}
                </span>
                @endif
            </a>
        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════
     2. STATISTIK — 4 Kartu Ringkasan
════════════════════════════════════════════ --}}
<section aria-label="Statistik Pesanan" class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-7">
    @php
    $stats = [
        [
            'label' => 'Total Pesanan',
            'val'   => $transactions->count(),
            'sub'   => 'order',
            'color' => 'blue',
            'bg'    => 'bg-blue-600',
            'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ],
        [
            'label' => 'Diproses',
            'val'   => $activeOrders,
            'sub'   => 'aktif',
            'color' => 'amber',
            'bg'    => 'bg-amber-500',
            'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Selesai',
            'val'   => $completedOrders,
            'sub'   => 'selesai',
            'color' => 'emerald',
            'bg'    => 'bg-emerald-500',
            'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Total Pengeluaran',
            'val'   => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
            'sub'   => '',
            'color' => 'violet',
            'bg'    => 'bg-violet-600',
            'icon'  => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
    @endphp

    @foreach($stats as $s)
    <article class="stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md">
        <div class="flex items-start justify-between mb-3">
            {{-- Solid colored icon background for clarity --}}
            <div class="{{ $s['bg'] }} w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate-400 font-medium leading-tight">{{ $s['label'] }}</p>
        <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-1 leading-tight tracking-tight">
            {{ $s['val'] }}
        </p>
        @if($s['sub'])
        <p class="text-xs text-slate-400 mt-0.5">{{ $s['sub'] }}</p>
        @endif
    </article>
    @endforeach
</section>


{{-- ════════════════════════════════════════════
     3. KATEGORI & JENIS CUCIAN
        State dikelola oleh Alpine.js $store 'cart'
════════════════════════════════════════════ --}}
@if($services->count())
@php $grouped = $services->groupBy('category_id'); @endphp

<section aria-label="Kategori dan Jenis Cucian" class="mb-8">

    {{-- Section Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Kategori &amp; Jenis Cucian</h2>
            <p class="text-xs text-slate-400 mt-0.5">Klik "<span class="font-semibold">+</span>" pada layanan yang ingin dipesan</p>
        </div>
        <a href="{{ route('user.layanan') }}"
           class="text-xs font-semibold text-brand hover:underline hover:text-brand-dark transition">
            Lihat semua →
        </a>
    </div>

    {{-- Category Grid: 2 col desktop, 1 col mobile --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        @foreach($grouped as $catId => $items)
        @php
            $cat = $items->first()->categoryRelation;
        @endphp
        @if($cat)

        {{-- ── Category Card ── --}}
        <article class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden
                        hover:shadow-md transition-shadow duration-200">

            {{-- Category Header — pastel bg, no "Pesan" button per spec --}}
            <header class="flex items-center gap-3 px-5 py-3.5 border-b
                           {{ $cat->border_class ?? 'border-slate-100' }}
                           {{ $cat->bg_class ?? 'bg-slate-50' }}">
                <span class="text-2xl leading-none" aria-hidden="true">{{ $cat->icon ?? '🧺' }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold {{ $cat->text_class ?? 'text-slate-800' }} truncate">
                        {{ $cat->name }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $items->count() }} layanan tersedia</p>
                </div>
            </header>

            {{-- ════ Service Item List ════ --}}
            <div class="divide-y divide-slate-50">
                @foreach($items as $svc)

                {{--
                    Each row owns its own Alpine component: { qty: 0 }
                    qty drives the two display modes:
                      qty == 0  →  single "+" add button
                      qty  > 0  →  inline [ − ] qty [ + ] counter

                    On every qty change we also update $store('cart')
                    so the sticky bar reacts globally.
                --}}
                <div class="svc-row flex items-center gap-2 sm:gap-3 px-4 sm:px-5 py-3 sm:py-3.5"
                     x-data="svcItem({{ $svc->id }}, '{{ addslashes($svc->name) }}', {{ $svc->price }}, '{{ $svc->unit }}')"
                     :class="{ 'bg-blue-50/40': qty > 0 }">

                    {{-- Service Icon --}}
                    <span class="text-base sm:text-lg leading-none flex-shrink-0" aria-hidden="true">{{ $svc->icon ?? '👕' }}</span>

                    {{-- Service Name + Description --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 leading-snug">
                            {{ $svc->name }}
                        </p>
                        @if($svc->description)
                        <p class="text-[11px] text-gray-600 mt-0.5 line-clamp-1 leading-snug">
                            {{ $svc->description }}
                        </p>
                        @endif
                    </div>

                    {{-- ── Right side: price + counter ── --}}
                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">

                        {{-- Price block (hidden on xs, always visible sm+) --}}
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-slate-900 leading-tight tabular-nums">
                                Rp {{ number_format($svc->price, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-slate-400">/ {{ $svc->unit }}</p>
                        </div>

                        {{-- ══════════════════════════════════════════════
                             COUNTER WIDGET
                             Mode A (qty == 0): shows only the "+" button
                             Mode B (qty  > 0): shows [ − ] qty [ + ] row
                        ══════════════════════════════════════════════ --}}

                        {{-- ── Mode A: plain "+" Add button ── --}}
                        <button type="button"
                                x-show="qty === 0"
                                id="add-svc-{{ $svc->id }}"
                                aria-label="Tambah {{ $svc->name }} ke keranjang"
                                @click="increment()"
                                class="counter-btn
                                       w-8 h-8 rounded-xl
                                       {{ $cat->bg_class ?? 'bg-blue-50' }}
                                       {{ $cat->text_class ?? 'text-blue-600' }}
                                       border {{ $cat->border_class ?? 'border-blue-100' }}
                                       flex items-center justify-center
                                       hover:brightness-95
                                       focus:outline-none focus:ring-2 focus:ring-brand/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                        {{-- ── Mode B: inline [ − ] qty [ + ] ── --}}
                        <div x-show="qty > 0"
                             x-cloak
                             class="flex items-center gap-1 bg-slate-100 rounded-xl p-0.5">

                            {{-- Decrement --}}
                            <button type="button"
                                    id="dec-svc-{{ $svc->id }}"
                                    aria-label="Kurangi {{ $svc->name }}"
                                    @click="decrement()"
                                    class="counter-btn
                                           w-7 h-7 rounded-lg bg-white
                                           text-slate-600 hover:text-red-500
                                           shadow-sm flex items-center justify-center
                                           focus:outline-none focus:ring-2 focus:ring-brand/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                </svg>
                            </button>

                            {{-- Current quantity --}}
                            <span x-text="qty"
                                  class="qty-number text-sm font-extrabold text-slate-900"
                                  aria-live="polite">
                            </span>

                            {{-- Increment --}}
                            <button type="button"
                                    id="inc-svc-{{ $svc->id }}"
                                    aria-label="Tambah {{ $svc->name }}"
                                    @click="increment()"
                                    class="counter-btn
                                           w-7 h-7 rounded-lg
                                           {{ $cat->bg_class ?? 'bg-blue-50' }}
                                           {{ $cat->text_class ?? 'text-blue-600' }}
                                           shadow-sm flex items-center justify-center
                                           hover:brightness-95
                                           focus:outline-none focus:ring-2 focus:ring-brand/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Mobile price (xs only, shown below counter) --}}
                        <div class="sm:hidden text-right" x-show="qty === 0">
                            <p class="text-[11px] font-bold text-slate-800 tabular-nums">
                                Rp&nbsp;{{ number_format($svc->price, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>{{-- /right side --}}
                </div>{{-- /svc-row --}}

                @endforeach
            </div>{{-- /service list --}}
        </article>

        @endif
        @endforeach
    </div>{{-- /category grid --}}

</section>


{{-- ════════════════════════════════════════════
     STICKY BOTTOM CART BAR
     ● Renders outside the category <section> so
       it lives at the root of <main> — no z-index
       conflicts.
     ● Controlled by $store.cart.count
     ● Slides up from the bottom with a spring ease.
════════════════════════════════════════════ --}}
<div id="sticky-cart"
     x-data
     x-show="$store.cart.count > 0"
     x-cloak
     x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-300"
     x-transition:enter-start="opacity-0 translate-y-full"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-full"
     class="fixed bottom-0 left-0 right-0 z-[60] px-3 sm:px-0
            flex justify-center pb-4 sm:pb-5"
     role="complementary"
     aria-label="Keranjang belanja sementara">

    {{-- ── Cart Card ── --}}
    <div class="w-full max-w-2xl
                bg-white border border-slate-200
                rounded-2xl sm:rounded-2xl
                shadow-[0_-4px_30px_rgba(0,0,0,0.12)]
                overflow-hidden">

        {{-- Top accent bar --}}
        <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-400"></div>

        <div class="flex items-center gap-3 sm:gap-4 px-4 sm:px-5 py-3.5">

            {{-- ① Cart icon + badge --}}
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5M7 13l-2.293 2.293
                                 c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0
                                 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                {{-- item count badge --}}
                <span class="absolute -top-1.5 -right-1.5
                             w-5 h-5 bg-amber-400 text-slate-900
                             text-[10px] font-black rounded-full
                             flex items-center justify-center
                             ring-2 ring-white"
                      x-text="$store.cart.count"
                      aria-label="Jumlah item">
                </span>
            </div>

            {{-- ② Summary text --}}
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-semibold text-slate-400 leading-none mb-0.5">
                    <span x-text="$store.cart.count"></span> item dipilih
                </p>
                <p class="text-base sm:text-lg font-extrabold text-slate-900 leading-tight tabular-nums">
                    Total:&nbsp;<span x-text="$store.cart.formattedTotal" class="text-brand"></span>
                </p>
            </div>

            {{-- ③ Action buttons --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Clear cart --}}
                <button type="button"
                        id="btn-clear-cart"
                        @click="$store.cart.reset()"
                        title="Kosongkan keranjang"
                        class="w-9 h-9 rounded-xl border border-slate-200
                               bg-white hover:bg-red-50 hover:border-red-200
                               text-slate-400 hover:text-red-500
                               flex items-center justify-center
                               transition-colors duration-150
                               focus:outline-none focus:ring-2 focus:ring-red-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                 a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                 m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>

                {{-- Lanjut Pesanan CTA --}}
                <a id="btn-lanjut-pesanan"
                   :href="$store.cart.orderUrl()"
                   class="inline-flex items-center gap-2
                          bg-brand hover:bg-brand-dark
                          text-white font-bold text-sm
                          px-4 sm:px-5 py-2.5 rounded-xl
                          shadow-md shadow-blue-500/20
                          transition-all duration-150
                          hover:-translate-y-0.5 hover:shadow-lg
                          active:scale-95
                          focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5
                                 M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17
                                 m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0
                                 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="hidden xs:inline">Lanjut</span> Pesan
                </a>
            </div>
        </div>{{-- /inner flex --}}

        {{-- ④ Item pill preview (desktop only — hidden on mobile to save space) --}}
        <div class="hidden sm:flex items-center gap-1.5 px-5 pb-3 flex-wrap"
             x-show="$store.cart.count > 0">
            <template x-for="item in $store.cart.itemList" :key="item.id">
                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600
                             text-[11px] font-semibold px-2.5 py-1 rounded-full
                             border border-slate-200">
                    <span x-text="item.qty" class="text-brand font-black tabular-nums"></span>
                    <span>×</span>
                    <span x-text="item.name" class="cart-item-pill"></span>
                </span>
            </template>
        </div>

    </div>{{-- /cart card --}}
</div>{{-- /sticky-cart --}}

@endif


{{-- ════════════════════════════════════════════
     4. PESANAN SEDANG DIPROSES
════════════════════════════════════════════ --}}
@if($activeOrders > 0)
<section aria-label="Pesanan Sedang Diproses"
         class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">

    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
        <div class="flex items-center gap-2.5">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-60"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
            </span>
            <h3 class="text-sm font-bold text-slate-900">Pesanan Sedang Diproses</h3>
            <span class="text-[11px] font-bold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">
                {{ $activeOrders }}
            </span>
        </div>
        <a href="{{ route('user.status') }}" class="text-xs font-semibold text-brand hover:underline transition">
            Lihat semua →
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th scope="col" class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nota</th>
                    <th scope="col" class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tanggal</th>
                    <th scope="col" class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Layanan</th>
                    <th scope="col" class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                    <th scope="col" class="px-5 py-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wide text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($activeTransactions as $aTrx)
                @php
                $stClass = match($aTrx->status) {
                    'cuci'    => 'bg-blue-100 text-blue-700',
                    'kering'  => 'bg-cyan-100 text-cyan-700',
                    'setrika' => 'bg-orange-100 text-orange-700',
                    default   => 'bg-slate-100 text-slate-600'
                };
                $stEmoji = match($aTrx->status) {
                    'cuci'    => '🫧 Dicuci',
                    'kering'  => '💨 Dikeringkan',
                    'setrika' => '👔 Disetrika',
                    default   => ucfirst($aTrx->status)
                };
                @endphp
                <tr class="hover:bg-slate-50/80 transition cursor-pointer"
                    onclick="window.location='{{ route('user.show', $aTrx->id) }}'">
                    <td class="px-5 py-3.5 font-bold text-brand text-sm whitespace-nowrap">
                        {{ $aTrx->invoice_code }}
                    </td>
                    <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                        {{ $aTrx->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5">
                        @foreach($aTrx->details->take(2) as $d)
                        <span class="inline-block text-[11px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full mr-1">
                            {{ $d->service->name ?? '-' }}
                        </span>
                        @endforeach
                        @if($aTrx->details->count() > 2)
                        <span class="text-[11px] text-slate-400">+{{ $aTrx->details->count() - 2 }} lainnya</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $stClass }}">
                            {{ $stEmoji }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right font-extrabold text-slate-900 whitespace-nowrap">
                        Rp {{ number_format($aTrx->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif


{{-- ════════════════════════════════════════════
     5. PESANAN TERAKHIR — Progress Tracker
════════════════════════════════════════════ --}}
@if($latestTransaction)
<section aria-label="Pesanan Terakhir"
         class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-2">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6 pb-5 border-b border-slate-100">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pesanan Terakhir</p>
            <h3 class="text-base font-bold text-slate-900">
                <a href="{{ route('user.show', $latestTransaction->id) }}" class="hover:text-brand transition">
                    {{ $latestTransaction->invoice_code }}
                </a>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ $latestTransaction->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        @php
        $stBadge = [
            'baru'    => 'bg-amber-100 text-amber-700',
            'cuci'    => 'bg-blue-100 text-blue-700',
            'kering'  => 'bg-cyan-100 text-cyan-700',
            'setrika' => 'bg-orange-100 text-orange-700',
            'selesai' => 'bg-emerald-100 text-emerald-700',
            'diambil' => 'bg-emerald-100 text-emerald-700',
        ];
        @endphp
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide
                     {{ $stBadge[$latestTransaction->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ $latestTransaction->status }}
        </span>
    </div>

    {{-- Progress Stepper --}}
    @php
    $steps = [
        ['key' => 'baru',    'label' => 'Diterima',    'emoji' => '✅'],
        ['key' => 'cuci',    'label' => 'Dicuci',      'emoji' => '🫧'],
        ['key' => 'kering',  'label' => 'Dikeringkan', 'emoji' => '💨'],
        ['key' => 'setrika', 'label' => 'Disetrika',   'emoji' => '👔'],
        ['key' => 'selesai', 'label' => 'Selesai',     'emoji' => '🎉'],
    ];
    $sOrder = ['baru' => 0, 'cuci' => 1, 'kering' => 2, 'setrika' => 3, 'selesai' => 4, 'diambil' => 4];
    $curr   = $sOrder[$latestTransaction->status] ?? 0;
    @endphp

    <div class="relative flex justify-between items-start">
        {{-- Track line --}}
        <div class="absolute top-4 left-4 right-4 h-1 bg-slate-100 rounded-full z-0">
            <div class="h-full bg-gradient-to-r from-blue-500 to-brand rounded-full transition-all duration-700"
                 style="width: {{ ($curr / 4) * 100 }}%">
            </div>
        </div>

        @foreach($steps as $i => $step)
        <div class="flex flex-col items-center gap-2 z-10 flex-1">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-sm
                        {{ $i <= $curr ? 'bg-brand text-white step-dot-active' : 'bg-slate-100 text-slate-400' }}
                        transition-all duration-300">
                {{ $step['emoji'] }}
            </div>
            <span class="text-[10px] font-semibold text-center hidden sm:block leading-tight
                         {{ $i <= $curr ? 'text-brand' : 'text-slate-400' }}">
                {{ $step['label'] }}
            </span>
        </div>
        @endforeach
    </div>

    {{-- Stats row --}}
    <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-3 gap-3 text-sm">
        <div>
            <p class="text-xs text-slate-400 mb-0.5">Tanggal Masuk</p>
            <p class="font-semibold text-slate-800">{{ $latestTransaction->created_at->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-0.5">Est. Selesai</p>
            <p class="font-semibold text-slate-800">{{ $latestTransaction->created_at->addDays(2)->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-0.5">Total Tagihan</p>
            <p class="font-extrabold text-brand text-base">
                Rp {{ number_format($latestTransaction->total_price, 0, ',', '.') }}
            </p>
        </div>
    </div>
</section>

@else

{{-- ── Empty State ── --}}
<section class="bg-white rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
    <div class="text-5xl mb-4" aria-hidden="true">🧺</div>
    <h3 class="text-base font-bold text-slate-700">Belum Ada Pesanan</h3>
    <p class="text-sm text-slate-400 mt-1 mb-5 max-w-xs mx-auto">
        Yuk mulai pesan laundry pertama Anda lewat layanan di atas!
    </p>
    <a href="{{ route('user.order') }}"
       class="inline-flex items-center gap-2 bg-brand text-white font-semibold
              px-5 py-2.5 rounded-xl hover:bg-brand-dark transition text-sm shadow-lg shadow-blue-200 active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Pesanan Sekarang
    </a>
</section>

@endif

@endsection


@section('scripts')
<script>
/**
 * ─────────────────────────────────────────────────────────
 *  Alpine.js GLOBAL STORE: cart
 *  Registered during alpine:init so it is available to
 *  every component on the page via $store('cart').
 * ─────────────────────────────────────────────────────────
 */
document.addEventListener('alpine:init', () => {

    /* ── 1. Global Cart Store ───────────────────────────── */
    Alpine.store('cart', {
        /**
         * items = { [id]: { id, name, price, unit, qty } }
         */
        items: {},

        /** Total number of individual units in the cart */
        get count() {
            return Object.values(this.items)
                         .reduce((sum, i) => sum + i.qty, 0);
        },

        /** Gross total price */
        get total() {
            return Object.values(this.items)
                         .reduce((sum, i) => sum + i.price * i.qty, 0);
        },

        /** Formatted total for display */
        get formattedTotal() {
            return 'Rp\u00a0' + new Intl.NumberFormat('id-ID').format(this.total);
        },

        /** Flat array of items for template loops */
        get itemList() {
            return Object.values(this.items).filter(i => i.qty > 0);
        },

        /**
         * Update (or remove) one service's quantity.
         * Called by every svcItem component.
         */
        set(id, name, price, unit, qty) {
            if (qty <= 0) {
                delete this.items[id];
            } else {
                this.items[id] = { id, name, price, unit, qty };
            }
            // Swap reference to guarantee Alpine reactivity
            this.items = { ...this.items };
        },

        /**
         * Build the order-page URL pre-populated with selected services.
         * Controller can read: ?service_id[]=4&qty_4=2&service_id[]=7&qty_7=1
         */
        orderUrl() {
            const params = new URLSearchParams();
            this.itemList.forEach(i => {
                params.append('service_id', i.id);
                params.append('qty_' + i.id, i.qty);
            });
            return '{{ route("user.order") }}?' + params.toString();
        },

        /**
         * Clear all items WITHOUT a page reload.
         * Each svcItem component watches its own qty via
         * $store access and will reset to 0 automatically.
         */
        reset() {
            this.items = {};
            /*
             * Broadcast a signal so every svcItem component
             * resets its local qty back to 0.
             */
            window.dispatchEvent(new CustomEvent('cart-reset'));
        },
    });


    /* ── 2. Per-row Service Item Component ─────────────── */
    /**
     * svcItem(id, name, price, unit)
     *
     * Usage (Blade): x-data="svcItem(4, 'Cuci Selimut', 15000, 'pcs')"
     *
     * Displays either:
     *   • qty === 0  →  a plain "+" add button
     *   • qty  > 0  →  an inline [ − qty + ] counter
     *
     * On every change, syncs to $store('cart').
     */
    Alpine.data('svcItem', (id, name, price, unit) => ({
        id,
        name,
        price,
        unit,
        qty: 0,

        init() {
            /* Reset local qty whenever the cart is cleared */
            window.addEventListener('cart-reset', () => {
                this.qty = 0;
            });
        },

        increment() {
            this.qty++;
            Alpine.store('cart').set(this.id, this.name, this.price, this.unit, this.qty);
        },

        decrement() {
            if (this.qty <= 0) return;
            this.qty--;
            Alpine.store('cart').set(this.id, this.name, this.price, this.unit, this.qty);
        },
    }));

});
</script>
@endsection
