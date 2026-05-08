<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Rumah Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-surface dark:bg-slate-900 font-sans antialiased min-h-screen text-slate-800 dark:text-slate-200 transition-colors duration-300" 
      x-data="{ sidebarOpen: window.innerWidth > 1024 }"
      @resize.window="if(window.innerWidth > 1024) sidebarOpen = true">

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
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm md:hidden"
         x-cloak>
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'" 
           class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-800 border-r border-slate-100 dark:border-slate-700 transition-all duration-300 flex flex-col">
        
        {{-- Sidebar Logo --}}
        <div class="h-20 flex items-center px-6 border-b border-slate-50 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 bg-brand rounded-xl flex items-center justify-center shadow-sm shadow-blue-200 dark:shadow-none">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <div x-show="sidebarOpen" x-transition:enter="delay-150" class="flex-shrink-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-none tracking-tight">Rumah Laundry</p>
                    <p class="text-[10px] text-brand dark:text-blue-400 font-bold leading-none mt-1 uppercase tracking-tighter">Admin Panel</p>
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
                         ? 'bg-gradient-to-r from-brand to-blue-500 text-white shadow-xl shadow-blue-100 dark:shadow-none font-bold scale-[1.02]' 
                         : 'text-slate-600 dark:text-slate-300 font-semibold hover:bg-blue-50 dark:hover:bg-slate-700/50 hover:text-brand dark:hover:text-blue-400' }}"
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
        <div class="p-4 border-t border-slate-50 dark:border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-slate-400 dark:text-slate-400 hover:text-red-500 dark:hover:text-rose-400 hover:bg-red-50 dark:hover:bg-rose-900/30 transition-all duration-300 group">
                    <svg class="w-5 h-5 flex-shrink-0 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-bold whitespace-nowrap">Keluar</span>
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
                    <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>

                {{-- Mobile Toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Mobile Brand --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">Admin</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Dark Mode Toggle Button --}}
                <button @click="darkMode = !darkMode" class="p-2 rounded-xl text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-amber-500 dark:hover:text-amber-300 transition-colors" aria-label="Toggle Dark Mode">
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                
                <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>

                {{-- User Info --}}
                <div class="hidden sm:flex flex-col items-right text-right">
                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand to-blue-400 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-100 dark:shadow-none">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-4 md:p-6 lg:p-8 flex-1 page-fade">
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm font-semibold px-4 py-3 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-rose-900/30 border border-red-100 dark:border-rose-800 text-red-600 dark:text-rose-400 text-sm px-4 py-3 rounded-2xl">
                <ul class="space-y-1 font-medium">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
            </div>
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
