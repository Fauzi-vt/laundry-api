<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Rumah Laundry</title>
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
        [x-cloak]          { display: none !important; }
        ::-webkit-scrollbar{ width:4px;height:4px }
        ::-webkit-scrollbar-track { background:transparent }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:99px }

        /* Smooth page transitions */
        .page-fade { animation: fadeSlide .25s ease both }
        @keyframes fadeSlide { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

        /* Nav pill underline */
        .nav-pill { position:relative }
        .nav-pill.active::after {
            content:''; position:absolute; bottom:-9px; left:50%; transform:translateX(-50%);
            width:20px; height:3px; background:#2563eb; border-radius:99px;
        }

        /* Glass card */
        .glass { background:rgba(255,255,255,.85); backdrop-filter:blur(12px); }

        /* Input focus ring */
        .field { @apply w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 outline-none transition focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand-ring; }

        /* Badge */
        .badge-blue   { @apply bg-blue-100 text-blue-700 }
        .badge-green  { @apply bg-emerald-100 text-emerald-700 }
        .badge-yellow { @apply bg-amber-100 text-amber-700 }
        .badge-orange { @apply bg-orange-100 text-orange-700 }
        .badge-red    { @apply bg-red-100 text-red-700 }
        .badge-gray   { @apply bg-slate-100 text-slate-600 }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @yield('styles')
</head>
<body class="bg-surface font-sans antialiased min-h-screen text-slate-800" x-data="{ showProfile: false }">

{{-- ══════════════════════════════════════════════
     MODAL: EDIT PROFIL
══════════════════════════════════════════════ --}}
<div x-show="showProfile" x-cloak
     class="fixed inset-0 z-[999] flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showProfile=false"></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col z-10"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <p class="text-xs font-semibold text-brand uppercase tracking-widest mb-0.5">Akun Saya</p>
                <h2 class="text-lg font-bold text-slate-900">Edit Profil & Alamat</h2>
            </div>
            <button @click="showProfile=false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Modal Body --}}
        <div class="overflow-y-auto flex-1">
            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nama Lengkap *</label>
                        <input type="text" name="name" required value="{{ old('name', auth()->user()->name) }}" class="field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Email *</label>
                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}" class="field">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08123456789" class="field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', auth()->user()->whatsapp) }}" placeholder="628xxxxxxxxx" class="field">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Alamat / Titik Jemput</label>
                    <textarea name="address" rows="2" class="field resize-none">{{ old('address', auth()->user()->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Pilih Lokasi Rumah di Peta</label>
                    <div id="map" class="w-full h-48 rounded-xl border border-slate-200 z-0" 
                         x-init="setTimeout(() => { if(!map) initMap(); }, 500)"></div>
                    <p class="text-[10px] text-slate-400 mt-1.5 italic">*Klik pada peta atau geser penanda untuk menentukan lokasi jemput.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Latitude</label>
                        <input type="number" name="latitude" id="lat_input" step="0.0000001" value="{{ old('latitude', auth()->user()->latitude) }}" readonly
                               class="field bg-slate-50 text-slate-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Longitude</label>
                        <input type="number" name="longitude" id="lng_input" step="0.0000001" value="{{ old('longitude', auth()->user()->longitude) }}" readonly
                               class="field bg-slate-50 text-slate-500 cursor-not-allowed">
                    </div>
                </div>

                <button type="button" onclick="getLocation()" class="w-full py-2.5 bg-blue-50 text-brand border border-brand/20 text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Deteksi Lokasi GPS Saya
                </button>
                <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Password Baru</label>
                        <input type="password" name="password" placeholder="Min. 8 karakter" class="field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" class="field">
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-brand text-white font-semibold rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-blue-200 text-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TOP NAVBAR
══════════════════════════════════════════════ --}}
<header class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

        {{-- Brand --}}
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center shadow-sm shadow-blue-300">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-bold text-slate-900 leading-none">Rumah Laundry</p>
                <p class="text-[10px] text-slate-400 font-medium leading-none mt-0.5">Tasikmalaya</p>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden md:flex items-center gap-1">
            @php
            $navItems = [
                ['route' => 'user.dashboard',  'label' => 'Beranda'],
                ['route' => 'user.layanan',    'label' => 'Layanan'],
                ['route' => 'user.order',      'label' => 'Pesan'],
                ['route' => 'user.pembayaran', 'label' => 'Pembayaran'],
                ['route' => 'user.status',     'label' => 'Status'],
            ];
            $cur = request()->route()->getName();
            @endphp
            @foreach($navItems as $n)
            @php $active = $cur === $n['route']; @endphp
            <a href="{{ route($n['route']) }}"
               class="nav-pill px-4 py-2 text-sm font-medium rounded-lg transition
                      {{ $active ? 'text-brand bg-brand-light font-semibold active' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                {{ $n['label'] }}
                @if($n['route']==='user.pembayaran' && isset($pendingCount) && $pendingCount>0)
                <span class="ml-1 inline-flex items-center justify-center w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full">{{ $pendingCount }}</span>
                @endif
                @if($n['route']==='user.status' && isset($activeOrders) && $activeOrders>0)
                <span class="ml-1 inline-flex items-center justify-center w-4 h-4 bg-brand text-white text-[9px] font-bold rounded-full">{{ $activeOrders }}</span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Right Actions --}}
        <div class="flex items-center gap-2">

            {{-- Notifikasi --}}
            <div x-data="{ open: false }" @click.outside="open=false" class="relative">
                <button @click="open=!open"
                        class="relative w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:text-brand hover:bg-brand-light transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(isset($activeOrders) && $activeOrders > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                    <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100 bg-slate-50">
                        <p class="text-sm font-semibold text-slate-800">Notifikasi</p>
                        @if(isset($activeOrders) && $activeOrders > 0)
                        <span class="text-xs bg-red-100 text-red-600 font-semibold px-2 py-0.5 rounded-full">{{ $activeOrders }} aktif</span>
                        @endif
                    </div>
                    <div class="max-h-56 overflow-y-auto">
                        @if(isset($activeTransactions) && $activeTransactions->count())
                            @foreach($activeTransactions->take(4) as $n)
                            <a href="{{ route('user.status') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-base flex-shrink-0">🫧</div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $n->invoice_code }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 capitalize">{{ $n->status }}</p>
                                </div>
                            </a>
                            @endforeach
                        @else
                        <div class="py-10 text-center text-slate-400">
                            <div class="text-3xl mb-2">🎉</div>
                            <p class="text-xs font-medium">Tidak ada pesanan aktif</p>
                        </div>
                        @endif
                    </div>
                    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100">
                        <a href="{{ route('user.status') }}" class="text-xs font-semibold text-brand hover:underline">Lihat semua →</a>
                    </div>
                </div>
            </div>

            {{-- Avatar + Dropdown --}}
            <div x-data="{ openP: false }" @click.outside="openP=false" class="relative">
                <button @click="openP=!openP"
                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand to-blue-400 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-slate-700">{{ explode(' ', trim(auth()->user()->name))[0] }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openP" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1.5 px-1.5 space-y-0.5">
                        <button @click="showProfile=true; openP=false"
                                class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition text-left">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Edit Profil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile hamburger --}}
            <div x-data="{ mob: false }" class="md:hidden">
                <button @click="mob=!mob" class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                {{-- Mobile menu --}}
                <div x-show="mob" x-cloak x-transition class="absolute top-16 left-0 right-0 bg-white border-b border-slate-100 shadow-lg z-50 px-4 py-3 space-y-1">
                    @php
                    $mobileNavItems = [
                        ['route' => 'user.dashboard',  'label' => 'Beranda',    'emoji' => '🏠'],
                        ['route' => 'user.layanan',    'label' => 'Layanan',    'emoji' => '🏷️'],
                        ['route' => 'user.order',      'label' => 'Pesan',      'emoji' => '📦'],
                        ['route' => 'user.pembayaran', 'label' => 'Pembayaran', 'emoji' => '💳'],
                        ['route' => 'user.status',     'label' => 'Status',     'emoji' => '📋'],
                    ];
                    @endphp
                    @foreach($mobileNavItems as $n)
                    <a href="{{ route($n['route']) }}" @click="mob=false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->route()->getName() === $n['route'] ? 'bg-brand-light text-brand' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span>{{ $n['emoji'] }}</span>
                        {{ $n['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Flash messages --}}
@if(session('success'))
<div class="max-w-6xl mx-auto px-4 sm:px-6 pt-4">
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
</div>
@endif
@if($errors->any())
<div class="max-w-6xl mx-auto px-4 sm:px-6 pt-4">
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
        <ul class="space-y-0.5">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
    </div>
</div>
@endif

{{-- Page Content --}}
<main class="max-w-6xl mx-auto px-4 sm:px-6 py-7 w-full page-fade">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="max-w-6xl mx-auto px-4 sm:px-6 pb-8 mt-4">
    <div class="border-t border-slate-200 pt-6 flex flex-wrap justify-between items-center gap-3 text-xs text-slate-400">
        <p>© {{ date('Y') }} <span class="font-semibold text-slate-600">Rumah Laundry Tasikmalaya</span>. All rights reserved.</p>
        <a href="https://wa.me/62812345678" class="text-brand font-semibold hover:underline">Hubungi via WhatsApp</a>
    </div>
</footer>

<script>
let map, marker;
const initialLat = {{ auth()->user()->latitude ?? -7.3297 }};
const initialLng = {{ auth()->user()->longitude ?? 108.2144 }};

function initMap() {
    if (map) return;
    map = L.map('map').setView([initialLat, initialLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateInputs(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });
}

function updateInputs(lat, lng) {
    document.getElementById('lat_input').value = lat.toFixed(7);
    document.getElementById('lng_input').value = lng.toFixed(7);
}

function getLocation() {
    if (!navigator.geolocation) return alert('Browser tidak mendukung GPS.');
    navigator.geolocation.getCurrentPosition(pos => {
        const { latitude, longitude } = pos.coords;
        const newPos = [latitude, longitude];
        if(map) {
            map.setView(newPos, 17);
            marker.setLatLng(newPos);
        }
        updateInputs(latitude, longitude);
    }, () => alert('Gagal mendapatkan lokasi. Aktifkan izin lokasi di browser.'));
}
</script>
@yield('scripts')
</body>
</html>
