<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Rumah Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { DEFAULT: '#2563eb', dark: '#1d4ed8', light: '#eff6ff', ring: '#bfdbfe' },
                        surface: '#f8fafc',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 4px; height: 4px }
        ::-webkit-scrollbar-track { background: transparent }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px }
        
        .page-fade { animation: fadeSlide .25s ease both }
        @keyframes fadeSlide { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
    </style>
    @yield('styles')
</head>
<body class="bg-surface font-sans antialiased min-h-screen text-slate-800" x-data="{ sidebarOpen: true }">

    {{-- ══════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════ --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
           class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-100 transition-all duration-300 flex flex-col hidden md:flex">
        
        {{-- Sidebar Logo --}}
        <div class="h-20 flex items-center px-6 border-b border-slate-50 overflow-hidden">
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 bg-brand rounded-xl flex items-center justify-center shadow-sm shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <div x-show="sidebarOpen" x-transition:enter="delay-150" class="flex-shrink-0">
                    <p class="text-sm font-bold text-slate-900 leading-none tracking-tight">Rumah Laundry</p>
                    <p class="text-[10px] text-brand font-bold leading-none mt-1 uppercase tracking-tighter">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            @php
            $navItems = [
                ['route' => 'admin.monitoring', 'label' => 'Monitoring', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'admin.customers.index', 'label' => 'Pelanggan', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.services.index',  'label' => 'Layanan', 'icon' => 'M7 7h10M7 12h10m-7 5h7'],
                ['route' => 'admin.reports.index',   'label' => 'Laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ];
            $cur = request()->route()->getName();
            @endphp

            @foreach($navItems as $n)
            @php $active = ($cur === $n['route']); @endphp
            <a href="{{ route($n['route']) }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group relative
                      {{ $active 
                         ? 'bg-gradient-to-r from-brand to-blue-500 text-white shadow-xl shadow-blue-100 font-bold scale-[1.02]' 
                         : 'text-slate-500 hover:bg-blue-50 hover:text-brand' }}"
               title="{{ $n['label'] }}">
                <div class="relative z-10">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $n['icon'] }}"/>
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-sm whitespace-nowrap relative z-10">{{ $n['label'] }}</span>
                
                @if($active)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-full"></span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-slate-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300 group">
                    <svg class="w-5 h-5 flex-shrink-0 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════ --}}
    <div :class="sidebarOpen ? 'md:pl-64' : 'md:pl-20'" class="transition-all duration-300 min-h-screen flex flex-col">
        
        {{-- Top Header (Mobile & Desktop actions) --}}
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-600 hidden md:block transition-colors">
                    <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>

                {{-- Mobile Brand --}}
                <div class="flex items-center gap-2 md:hidden">
                    <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-900">Admin</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- User Info --}}
                <div class="hidden sm:flex flex-col items-right text-right mr-2">
                    <p class="text-sm font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand to-blue-400 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-100">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-6 md:p-8 flex-1 page-fade">
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-100 text-red-600 text-sm px-4 py-3 rounded-2xl">
                <ul class="space-y-1 font-medium">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="p-6 border-t border-slate-50 flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <p>© {{ date('Y') }} Rumah Laundry Admin</p>
            <p>v1.0.0 — System CIPASUNG</p>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
