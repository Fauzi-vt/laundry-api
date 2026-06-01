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
                ['route' => 'admin.monitoring', 'label' => 'Monitoring', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625z M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                ['route' => 'admin.customers.index', 'label' => 'Pelanggan', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20c-2.213 0-4.3-.632-6.09-1.735a4.125 4.125 0 010-7.03 11.414 11.414 0 0111.083 0 4.125 4.125 0 013.918 3.52M8 7a3 3 0 11-6 0 3 3 0 016 0zm14 0a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.services.index',  'label' => 'Layanan', 'icon' => 'M8.25 6.75h12M8.25 12h12M8.25 17.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'],
                ['route' => 'admin.categories.index', 'label' => 'Kategori', 'icon' => 'M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24H15a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18M2.25 13.5v-6a2.25 2.25 0 012.25-2.25h3.184c.597 0 1.17.237 1.591.659l2.25 2.25a2.25 2.25 0 001.591.659H19.5A2.25 2.25 0 0121.75 11.25v2.25m-18 0v6a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25v-6'],
                ['route' => 'admin.reports.index',   'label' => 'Laporan', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3v3m-6-6v6M1.5 5.625c0-1.036.84-1.875 1.875-1.875h9.75c1.036 0 1.875.84 1.875 1.875v12.75c0 1.036-.84 1.875-1.875 1.875h-9.75a1.875 1.875 0 01-1.875-1.875V5.625z'],
            ];
            $cur = request()->route()->getName();
            @endphp

            @foreach($navItems as $n)
            @php $active = ($cur === $n['route']); @endphp
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
                    <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
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
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1.5 rounded-xl text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        <span class="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-brand rounded-full"></span>
                    </button>
                    {{-- Dropdown Notifications --}}
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl py-2 z-50">
                        <p class="px-4 py-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase border-b dark:border-slate-700/60">Notifikasi</p>
                        <div class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                            <p class="font-bold">Sistem Berjalan Lancar</p>
                            <p class="text-[10px] text-slate-400 mt-1">Semua layanan aktif dan termonitor.</p>
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
                        confirmButtonColor: '#c5a373',
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
