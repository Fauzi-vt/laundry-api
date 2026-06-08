<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Rumah Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-surface dark:bg-slate-900 font-sans antialiased min-h-screen text-slate-800 dark:text-slate-200 transition-colors duration-300" 
      x-data="{ sidebarOpen: window.innerWidth > 1024, showProfile: false }"
      @resize.window="if(window.innerWidth > 1024) sidebarOpen = true">

    {{-- ══════════════════════════════════════════════
         MODAL: EDIT PROFIL
    ══════════════════════════════════════════════ --}}
    <div x-show="showProfile" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showProfile = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col z-10 border dark:border-slate-700"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <p class="text-xs font-bold text-brand uppercase tracking-widest mb-0.5">Akun Saya</p>
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">Edit Profil Administrator</h2>
                </div>
                <button @click="showProfile = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Modal Body --}}
            <div class="overflow-y-auto flex-1">
                <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Lengkap *</label>
                        <input type="text" name="name" required value="{{ old('name', auth()->user()->name) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Email *</label>
                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08123456789" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', auth()->user()->whatsapp) }}" placeholder="628xxxxxxxxx" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700 grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Password Baru</label>
                            <input type="password" name="password" placeholder="Min. 8 karakter" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Konfirmasi</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-brand">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand/10 dark:shadow-none text-sm">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════ --}}
    {{-- Backdrop Mobile --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
         x-cloak>
    </div>

    <aside :class="sidebarOpen ? 'left-0 w-64' : '-left-64 lg:left-0 lg:w-20'" 
           class="fixed inset-y-0 z-50 bg-white dark:bg-slate-800 border-r border-slate-100 dark:border-slate-700 transition-all duration-300 flex flex-col">
        
        {{-- Sidebar Logo --}}
        <div class="h-20 flex items-center px-6 border-b border-slate-50 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center shadow-sm bg-white border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div x-show="sidebarOpen" x-transition:enter="delay-150" class="flex-shrink-0">
                    <p class="text-sm font-extrabold text-slate-900 dark:text-white leading-none tracking-tight uppercase">RUMAH LAUNDRY</p>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            @php
            $navItems = [
                [
                    'route' => 'admin.dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'
                ],
                [
                    'route' => 'admin.customers.index',
                    'label' => 'Pelanggan',
                    'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20c-2.213 0-4.3-.632-6.09-1.735a4.125 4.125 0 010-7.03 11.414 11.414 0 0111.083 0 4.125 4.125 0 013.918 3.52M8 7a3 3 0 11-6 0 3 3 0 016 0zm14 0a3 3 0 11-6 0 3 3 0 016 0z'
                ],
                [
                    'route' => 'admin.orders.index',
                    'label' => 'Pesanan & Transaksi',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                ],
                [
                    'route' => 'admin.services.index',
                    'label' => 'Layanan & Harga',
                    'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.44 1.44 0 002.036 0l4.318-4.318a1.44 1.44 0 000-2.036L11.159 3.659A2.25 2.25 0 009.568 3z M6 7.5h.008v.008H6V7.5z'
                ],
                [
                    'route' => 'admin.shuttles.index',
                    'label' => 'Antar-Jemput',
                    'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'
                ],
                [
                    'route' => 'admin.payments.index',
                    'label' => 'Pembayaran',
                    'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.75-8.25h17.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.375a1.125 1.125 0 01-1.125-1.125V6.375c0-.621.504-1.125 1.125-1.125z'
                ],
                [
                    'route' => 'admin.reports.index',
                    'label' => 'Laporan',
                    'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3v3m-6-6v6M1.5 5.625c0-1.036.84-1.875 1.875-1.875h9.75c1.036 0 1.875.84 1.875 1.875v12.75c0 1.036-.84 1.875-1.875 1.875h-9.75a1.875 1.875 0 01-1.875-1.875V5.625z'
                ],
            ];
            $cur = request()->route() ? request()->route()->getName() : '';
            @endphp

            @foreach($navItems as $n)
            @php 
            $active = ($cur === $n['route']) || 
                      ($n['route'] === 'admin.services.index' && str_contains($cur ?? '', 'categories')); 
            @endphp
            <a href="{{ route($n['route']) }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 group relative
                      {{ $active 
                         ? 'bg-gradient-to-r from-brand to-brand-dark text-white shadow-md shadow-brand/10 dark:shadow-none font-bold' 
                         : 'text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-brand dark:hover:text-brand' }}"
               title="{{ $n['label'] }}">
                <div class="relative z-10">
                    <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $n['icon'] }}"/>
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-xs whitespace-nowrap relative z-10">{{ $n['label'] }}</span>
                
                @if($active)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-full"></span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-slate-50 dark:border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-rose-400 hover:bg-red-50 dark:hover:bg-rose-950/20 transition-all duration-200 group">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 group-hover:rotate-6 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                    <span x-show="sidebarOpen" class="text-xs font-bold whitespace-nowrap">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════ --}}
    <div :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'" class="transition-all duration-300 min-h-screen flex flex-col">
        
        {{-- Top Header --}}
        <header class="h-20 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-700 sticky top-0 z-40 px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-600 dark:hover:text-slate-300 hidden lg:block transition-colors">
                    <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>

                {{-- Mobile Toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Mobile Brand --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center border border-slate-100 dark:border-slate-700 bg-white">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">Admin</span>
                </div>
            </div>

            <div class="flex items-center gap-1">
                {{-- Dark Mode Toggle Button --}}
                <button @click="darkMode = !darkMode" class="p-1.5 rounded-xl text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-amber-500 dark:hover:text-amber-300 transition-colors" aria-label="Toggle Dark Mode">
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                {{-- Notification Button --}}
                @php
                    $newOrdersCount = \App\Models\Transaction::where('status', 'baru')->count();
                    $newOrders = \App\Models\Transaction::with('user')
                        ->where('status', 'baru')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1.5 rounded-xl text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        @if($newOrdersCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-rose-500 text-white rounded-full text-[9px] font-black flex items-center justify-center border-2 border-white dark:border-slate-800 animate-pulse">{{ $newOrdersCount }}</span>
                        @else
                        <span class="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-brand rounded-full"></span>
                        @endif
                    </button>
                    {{-- Dropdown Notifications --}}
                    <div x-show="open" @click.away="open = false" x-transition 
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl py-2 z-50 overflow-hidden"
                         x-cloak>
                        <p class="px-4 py-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase border-b dark:border-slate-700/60">Notifikasi ({{ $newOrdersCount }} Pesanan Baru)</p>
                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-700/60">
                            @forelse($newOrders as $ord)
                            <div class="px-4 py-3 text-xs hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors flex justify-between items-start gap-2">
                                <div class="flex-grow">
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ $ord->user->name ?? 'Pelanggan' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Invoice: <span class="font-bold text-brand">{{ $ord->invoice_code }}</span></p>
                                    <p class="text-[9px] text-slate-500 mt-1">{{ $ord->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0 flex flex-col items-end gap-1">
                                    <span class="font-extrabold text-[11px] text-slate-800 dark:text-slate-100">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</span>
                                    <a href="{{ route('admin.orders.index', ['search' => $ord->invoice_code]) }}" 
                                       class="text-[9px] bg-brand/10 text-brand dark:bg-brand/20 hover:bg-brand hover:text-white px-2 py-0.5 rounded font-black transition-all">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="px-4 py-6 text-center text-xs text-slate-400 dark:text-slate-500">
                                🧺 Tidak ada pesanan baru menanti.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="h-5 w-px bg-slate-200 dark:bg-slate-700 mx-2"></div>

                {{-- User Dropdown Menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                        <div class="hidden sm:flex flex-col items-right text-right">
                            <p class="text-xs font-black text-slate-900 dark:text-white leading-none group-hover:text-brand transition-colors">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Administrator</p>
                        </div>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-sm text-slate-500 dark:text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                    </button>

                    {{-- Dropdown list --}}
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl py-1 z-50 overflow-hidden"
                         x-cloak>
                        <button @click="showProfile = true; open = false" class="w-full text-left block px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-brand transition-colors">Pengaturan Profil</button>
                        <div class="border-t border-slate-100 dark:border-slate-700"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2.5 text-xs font-bold text-red-500 dark:text-rose-400 hover:bg-red-50 dark:hover:bg-rose-950/20 transition-colors">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-4 md:p-6 lg:p-8 flex-1 page-fade">
            @if(session('success') || $errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = localStorage.getItem('darkMode') === 'true';
                    const customSwal = Swal.mixin({
                        background: isDark ? '#1a1c24' : '#ffffff',
                        color: isDark ? '#f8fafc' : '#1e293b',
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-100 dark:border-slate-700'
                        }
                    });

                    @if(session('success'))
                        customSwal.fire({
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            icon: 'success'
                        });
                    @endif

                    @if($errors->any())
                        customSwal.fire({
                            title: 'Kesalahan!',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            icon: 'error'
                        });
                    @endif
                });
            </script>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="p-6 border-t border-slate-50 dark:border-slate-700 flex items-center justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
            <p>© {{ date('Y') }} Rumah Laundry Admin</p>
            <p>v1.0.0 — System CIPASUNG</p>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
