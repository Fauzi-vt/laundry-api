<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - Rumah Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{primary:'#1e40af',secondary:'#0ea5e9'}}}}</script>
    <style>
        [x-cloak]{display:none!important}
        ::-webkit-scrollbar{width:5px;height:5px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
        .notif-dot { animation: ping 1.5s cubic-bezier(0,0,.2,1) infinite; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col text-slate-800" x-data="userApp()">

{{-- ═══════════════════════════════════════════
     MODAL: EDIT PROFIL & ALAMAT
═══════════════════════════════════════════ --}}
<div x-show="showProfile" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showProfile=false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-primary text-white flex justify-between items-center flex-shrink-0">
            <div>
                <p class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Akun Saya</p>
                <p class="text-xl font-black">Edit Profil & Alamat</p>
            </div>
            <button @click="showProfile=false" class="text-white/70 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Form --}}
        <div class="overflow-y-auto flex-1">
            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5">
                @csrf @method('PUT')

                {{-- Nama & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Nama Lengkap *</label>
                        <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Email *</label>
                        <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>

                {{-- Phone & WhatsApp --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">No. Telepon</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-sm">📞</span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08123456789"
                                   class="w-full pl-9 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">WhatsApp Kurir</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-sm">💬</span>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="628xxxxxxxxx"
                                   class="w-full pl-9 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Format internasional: 628xxx</p>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Alamat Rumah / Titik Jemput</label>
                    <textarea name="address" rows="3" placeholder="Jl. Contoh No. 1, RT/RW 01/02, Kelurahan, Kecamatan, Kota..."
                              class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-none">{{ old('address', $user->address) }}</textarea>
                </div>

                {{-- Koordinat --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-black text-blue-700 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Titik Koordinat GPS (Opsional)
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-blue-600 mb-1">Latitude</label>
                            <input type="number" name="latitude" step="0.0000001" value="{{ old('latitude', $user->latitude) }}" placeholder="-7.123456"
                                   class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-600 mb-1">Longitude</label>
                            <input type="number" name="longitude" step="0.0000001" value="{{ old('longitude', $user->longitude) }}" placeholder="108.234567"
                                   class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none bg-white">
                        </div>
                    </div>
                    <button type="button" onclick="getLocation()" class="mt-3 w-full py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                        Gunakan Lokasi GPS Saya Sekarang
                    </button>
                </div>

                {{-- Ganti Password --}}
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wide mb-3">Ganti Password (biarkan kosong jika tidak ingin diubah)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Min. 8 karakter"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>
</div>

{{-- NAVBAR --}}
<nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center gap-2">
            <img src="/images/logo.svg" alt="Rumah Laundry" class="h-9 w-auto">
            <span class="hidden sm:inline ml-1 px-2 py-0.5 bg-blue-100 text-primary text-xs font-bold rounded-full">Pelanggan</span>
        </div>

        {{-- Right: Bell + Profile --}}
        <div class="flex items-center gap-2">

            {{-- Ikon Lonceng Notifikasi --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open=false">
                <button @click="open=!open" class="relative p-2 text-slate-400 hover:text-primary transition rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($activeOrders > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </button>
                {{-- Dropdown Notifikasi --}}
                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                        <p class="text-sm font-black text-slate-900">Notifikasi</p>
                        @if($activeOrders > 0)
                        <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">{{ $activeOrders }} Aktif</span>
                        @endif
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-50">
                        @forelse($activeTransactions as $notif)
                        <div class="px-4 py-3 hover:bg-slate-50 transition cursor-pointer" @click="tab='status'; open=false">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                                    {{ $notif->status === 'setrika' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                    @if($notif->status === 'cuci') 🫧
                                    @elseif($notif->status === 'kering') 💨
                                    @elseif($notif->status === 'setrika') 👔
                                    @else 📦
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800">{{ $notif->invoice_code }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Status: <span class="font-semibold text-primary capitalize">{{ $notif->status }}</span>
                                        @if($notif->status === 'setrika') — ⚡ Siap diambil segera!
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-4 py-8 text-center text-slate-400">
                            <div class="text-3xl mb-2">🎉</div>
                            <p class="text-sm font-semibold">Tidak ada pesanan aktif</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
                        <button @click="tab='status'; open=false" class="w-full text-center text-xs font-bold text-primary hover:underline">
                            Lihat semua status →
                        </button>
                    </div>
                </div>
            </div>

            {{-- Profil Dropdown --}}
            <div class="relative" x-data="{ openP: false }" @click.outside="openP=false">
                <button @click="openP=!openP" class="flex items-center gap-2.5 pl-3 border-l border-slate-200 hover:bg-slate-50 rounded-xl px-3 py-2 transition">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-blue-400 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-slate-800 leading-none">{{ explode(' ', trim($user->name))[0] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Member</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                {{-- Dropdown Menu --}}
                <div x-show="openP" x-cloak x-transition
                     class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                        <p class="text-sm font-black text-slate-900">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="py-1">
                        <button @click="showProfile=true; openP=false"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition text-left">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Edit Profil & Alamat
                        </button>
                        @if($user->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->whatsapp) }}" target="_blank"
                           class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition text-left">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            WhatsApp Saya
                        </a>
                        @endif
                        <div class="border-t border-slate-100 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-5 py-3.5 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
</div>
@endif
@if($errors->any() && request()->isMethod('put'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-5 py-3.5 rounded-xl">
        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
</div>
@endif

<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

    {{-- ═══════ HERO BANNER ═══════ --}}
    <div class="relative bg-gradient-to-r from-slate-900 via-primary to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-xl overflow-hidden">
        <div class="relative z-10">
            <p class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-2">Dashboard Pelanggan</p>
            <h1 class="text-3xl font-black mb-1">Halo, {{ explode(' ', trim($user->name))[0] }}! 👋</h1>
            <p class="text-blue-100 text-sm max-w-lg">Kelola pesanan, pantau status cucian, dan perbarui profil Anda di satu tempat.</p>
            {{-- Profil singkat --}}
            @if($user->address)
            <div class="mt-3 inline-flex items-center gap-2 bg-white/10 rounded-lg px-3 py-1.5 text-sm">
                <svg class="w-4 h-4 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <span class="text-blue-100 truncate max-w-xs">{{ Str::limit($user->address, 50) }}</span>
            </div>
            @endif
            <div class="mt-5 flex flex-wrap gap-3">
                {{-- Shortcut: buka form order langsung --}}
                <button @click="openOrderForm()" class="bg-white text-primary font-bold px-5 py-2.5 rounded-xl hover:bg-blue-50 transition shadow text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Pesanan Baru
                </button>
                <button @click="tab='status'" class="bg-white/20 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-white/30 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Status Cucian
                    @if($activeOrders > 0)
                    <span class="bg-red-500 text-white text-xs font-black w-5 h-5 rounded-full flex items-center justify-center">{{ $activeOrders }}</span>
                    @endif
                </button>
                <button @click="showProfile=true" class="bg-white/10 text-white/80 font-semibold px-4 py-2.5 rounded-xl hover:bg-white/20 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Edit Profil
                </button>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute right-20 bottom-0 w-40 h-40 bg-secondary/20 rounded-full blur-2xl"></div>
    </div>

    {{-- ═══════ STAT CARDS ═══════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php $stats = [
            ['label'=>'Total Pesanan', 'val'=>$transactions->count(), 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color'=>'blue', 'unit'=>'Order'],
            ['label'=>'Sedang Diproses', 'val'=>$activeOrders, 'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'orange', 'unit'=>'Active'],
            ['label'=>'Selesai', 'val'=>$completedOrders, 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'green', 'unit'=>'Done'],
            ['label'=>'Total Bayar', 'val'=>'Rp '.number_format($totalSpent,0,',','.'), 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'color'=>'purple', 'unit'=>''],
        ] @endphp
        @foreach($stats as $s)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition group">
            <div class="w-12 h-12 rounded-xl bg-{{ $s['color'] }}-100 text-{{ $s['color'] }}-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $s['label'] }}</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ $s['val'] }} <span class="text-sm font-medium text-slate-400">{{ $s['unit'] }}</span></p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══════ TABEL RINGKASAN PESANAN AKTIF ═══════ --}}
    @if($activeOrders > 0)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-blue-500 rounded-full animate-pulse"></span>
                <h3 class="text-base font-black text-slate-900">Pesanan Aktif Saat Ini</h3>
                <span class="ml-1 bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $activeOrders }}</span>
            </div>
            <button @click="tab='status'" class="text-xs font-bold text-primary hover:underline">Lihat Detail →</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ID Pesanan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Masuk</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Layanan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status Terkini</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Logistik</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-50">
                    @foreach($activeTransactions as $aTrx)
                    @php
                        $estPickup = $aTrx->created_at->addDays(2);
                        $isUrgent  = in_array($aTrx->status, ['setrika', 'selesai']);
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3.5">
                            <p class="font-black text-primary text-sm">{{ $aTrx->invoice_code }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                            {{ $aTrx->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            @foreach($aTrx->details->take(2) as $d)
                            <span class="inline-block text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full mr-1 mb-1">
                                {{ $d->service->name }} ({{ $d->quantity }} {{ $d->service->unit }})
                            </span>
                            @endforeach
                            @if($aTrx->details->count() > 2)
                            <span class="text-xs text-slate-400">+{{ $aTrx->details->count()-2 }} lainnya</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-black uppercase animate-pulse
                                {{ $aTrx->status === 'cuci' ? 'bg-blue-100 text-blue-700' :
                                   ($aTrx->status === 'kering' ? 'bg-cyan-100 text-cyan-700' :
                                    'bg-orange-100 text-orange-700') }}">
                                @if($aTrx->status === 'cuci') 🫧 Dicuci
                                @elseif($aTrx->status === 'kering') 💨 Dikeringkan
                                @elseif($aTrx->status === 'setrika') 👔 Disetrika
                                @else {{ $aTrx->status }}
                                @endif
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-black text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($aTrx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($isUrgent)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs font-bold">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                                Siap Diambil
                            </span>
                            @else
                            <div class="text-xs text-slate-500">
                                <p class="font-semibold text-slate-700">Est. {{ $estPickup->format('d M') }}</p>
                                <p class="text-slate-400">{{ $estPickup->diffForHumans() }}</p>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════ TAB NAVIGATION ═══════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-1.5 mb-8 overflow-x-auto">
        <nav class="flex gap-1 min-w-max">
            @php $menus = [
                ['id'=>'overview',   'label'=>'Ikhtisar',       'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['id'=>'layanan',    'label'=>'Lihat Layanan',  'icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['id'=>'order',      'label'=>'Order Laundry',  'icon'=>'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['id'=>'pembayaran', 'label'=>'Pembayaran',     'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                ['id'=>'status',     'label'=>'Status Laundry', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ] @endphp
            @foreach($menus as $m)
            <button @click="tab='{{ $m['id'] }}'"
                :class="tab==='{{ $m['id'] }}' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m['icon'] }}"/></svg>
                {{ $m['label'] }}
                @if($m['id']==='pembayaran' && $pendingPayments->count() > 0)
                <span class="ml-1 bg-red-500 text-white text-xs font-black w-5 h-5 rounded-full flex items-center justify-center">{{ $pendingPayments->count() }}</span>
                @endif
                @if($m['id']==='status' && $activeOrders > 0)
                <span class="ml-1 bg-blue-400 text-white text-xs font-black w-5 h-5 rounded-full flex items-center justify-center">{{ $activeOrders }}</span>
                @endif
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ═══════════════════════════════════════
         TAB: OVERVIEW
    ═══════════════════════════════════════ --}}
    <div x-show="tab==='overview'" x-transition x-cloak>
        @if($latestTransaction)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <div class="flex flex-wrap gap-4 justify-between items-start mb-6 pb-5 border-b border-slate-100">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Pesanan Terkini</h3>
                    <p class="text-sm text-slate-500 mt-1">No. Nota: <span class="font-bold text-primary">{{ $latestTransaction->invoice_code }}</span></p>
                </div>
                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest
                    {{ in_array($latestTransaction->status,['baru']) ? 'bg-yellow-100 text-yellow-700' :
                      (in_array($latestTransaction->status,['cuci','kering','setrika']) ? 'bg-blue-100 text-blue-700 animate-pulse' :
                       'bg-green-100 text-green-700') }}">
                    {{ $latestTransaction->status }}
                </span>
            </div>
            @php
                $steps = [['key'=>'baru','label'=>'Diterima','icon'=>'✅'],['key'=>'cuci','label'=>'Dicuci','icon'=>'🫧'],['key'=>'kering','label'=>'Dikeringkan','icon'=>'💨'],['key'=>'setrika','label'=>'Disetrika','icon'=>'👔'],['key'=>'selesai','label'=>'Selesai','icon'=>'🎉']];
                $statusOrder = ['baru'=>0,'cuci'=>1,'kering'=>2,'setrika'=>3,'selesai'=>4,'diambil'=>4];
                $currStep = $statusOrder[$latestTransaction->status] ?? 0;
            @endphp
            <div class="relative flex justify-between items-start mt-4">
                <div class="absolute top-5 left-0 right-0 h-0.5 bg-slate-200 mx-6 z-0">
                    <div class="h-full bg-primary transition-all duration-700" style="width: {{ ($currStep/4)*100 }}%"></div>
                </div>
                @foreach($steps as $i => $step)
                <div class="flex flex-col items-center gap-2 z-10 flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg {{ $i <= $currStep ? 'bg-primary text-white shadow-lg shadow-blue-200' : 'bg-slate-100 text-slate-400' }} transition-all">{{ $step['icon'] }}</div>
                    <span class="text-xs font-semibold {{ $i <= $currStep ? 'text-primary' : 'text-slate-400' }} hidden sm:block text-center">{{ $step['label'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm pt-6 border-t border-slate-100">
                <div><p class="text-slate-400 font-medium">Tanggal Masuk</p><p class="font-bold text-slate-800 mt-1">{{ $latestTransaction->created_at->format('d M Y, H:i') }}</p></div>
                <div><p class="text-slate-400 font-medium">Estimasi Selesai</p><p class="font-bold text-slate-800 mt-1">{{ $latestTransaction->created_at->addDays(2)->format('d M Y') }} <span class="text-xs text-slate-400">({{ $latestTransaction->created_at->addDays(2)->diffForHumans() }})</span></p></div>
                <div><p class="text-slate-400 font-medium">Total Tagihan</p><p class="font-black text-primary text-xl mt-1">Rp {{ number_format($latestTransaction->total_price,0,',','.') }}</p></div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 p-16 text-center">
            <div class="text-5xl mb-4">🧺</div>
            <h3 class="text-xl font-bold text-slate-700">Belum Ada Pesanan</h3>
            <p class="text-slate-400 mt-2 mb-6">Yuk, mulai pesan laundry pertama Anda!</p>
            <button @click="openOrderForm()" class="bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-blue-800 transition shadow-lg">Buat Pesanan Sekarang</button>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════
         TAB: LIHAT LAYANAN
    ═══════════════════════════════════════ --}}
    <div x-show="tab==='layanan'" x-transition x-cloak>
        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-slate-900">Daftar Layanan & Harga</h2>
            <p class="text-slate-500 mt-1">Harga transparan, tidak ada biaya tersembunyi. Klik layanan untuk langsung memesan.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $s)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-lg hover:border-primary transition-all group relative overflow-hidden cursor-pointer"
                 @click="openOrderForm({{ $s->id }})">
                <div class="absolute top-0 right-0 w-28 h-28 bg-slate-50 rounded-bl-full group-hover:bg-blue-50 transition-colors -z-0"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-primary flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform shadow-sm">
                        @if(str_contains(strtolower($s->name),'sepatu')) 👟
                        @elseif(str_contains(strtolower($s->name),'selimut') || str_contains(strtolower($s->name),'bedcover')) 🛏️
                        @elseif(str_contains(strtolower($s->name),'kilat')) ⚡
                        @else 👕
                        @endif
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-lg">{{ $s->name }}</h3>
                    <p class="text-slate-400 text-xs mt-1 mb-5">Layanan berkualitas, hasil bersih & wangi.</p>
                    <div class="flex items-end justify-between pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Harga</p>
                            <p class="text-2xl font-black text-primary">Rp {{ number_format($s->price,0,',','.') }}</p>
                            <p class="text-xs text-slate-500 font-semibold">per {{ $s->unit }}</p>
                        </div>
                        <span class="bg-primary/10 text-primary font-bold text-sm px-4 py-2 rounded-xl group-hover:bg-primary group-hover:text-white transition">Pesan →</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 text-slate-400">Belum ada layanan tersedia.</div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         TAB: ORDER LAUNDRY
    ═══════════════════════════════════════ --}}
    <div x-show="tab==='order'" x-transition x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- Form Order --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-900 to-primary text-white">
                        <h3 class="text-xl font-bold">Form Pemesanan Laundry</h3>
                        <p class="text-blue-200 text-sm mt-1">Pilih jenis layanan dan isi jumlah cucian</p>
                    </div>
                    @if($errors->any() && !request()->isMethod('put'))
                    <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="p-6 space-y-6">
                        @csrf
                        <div id="orderItems" class="space-y-4">
                            <div class="order-item bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Jenis Layanan</label>
                                        <select name="items[0][service_id]" required onchange="recalc()" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg bg-white text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach($services as $s)
                                            <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-unit="{{ $s->unit }}" data-sid="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Jumlah <span class="item-unit text-slate-400">(Kg/Pcs)</span></label>
                                        <input type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="cth: 2.5" required oninput="recalc()"
                                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                                    </div>
                                </div>
                                <div class="text-right text-xs text-slate-400 font-medium item-subtotal"></div>
                            </div>
                        </div>

                        <button type="button" onclick="addItem()" class="w-full py-3 rounded-xl border-2 border-dashed border-slate-300 hover:border-primary hover:text-primary font-semibold text-slate-400 text-sm transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Layanan Lain
                        </button>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Estimasi Total</p>
                            <p id="grandTotal" class="text-3xl font-black text-primary">Rp 0</p>
                            <p class="text-xs text-blue-400 mt-1">*Tagihan final ditentukan berdasarkan timbangan di kasir</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Catatan Khusus (Opsional)</label>
                            <textarea name="note" rows="2" placeholder="Contoh: Pisahkan baju berwarna, atau catatan lainnya..." class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-extrabold py-4 rounded-xl hover:bg-blue-800 transition shadow-lg text-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi & Kirim Pesanan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2"><span class="text-lg">📋</span> Alur Pemesanan</h4>
                    <ol class="space-y-4">
                        @foreach(['Pilih jenis layanan & jumlah cucian','Konfirmasi pesanan dan dapatkan nomor nota','Antar cucian ke gerai atau tunggu penjemputan','Lakukan pembayaran di kasir saat mengambil'] as $i => $step)
                        <li class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-primary text-white text-xs font-black flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i+1 }}</div>
                            <p class="text-sm text-slate-600">{{ $step }}</p>
                        </li>
                        @endforeach
                    </ol>
                </div>
                @if($user->address)
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                    <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Alamat Penjemputan
                    </h4>
                    <p class="text-sm text-slate-600">{{ $user->address }}</p>
                    @if($user->latitude && $user->longitude)
                    <p class="text-xs text-slate-400 mt-2">📍 GPS: {{ $user->latitude }}, {{ $user->longitude }}</p>
                    @endif
                    @if($user->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->whatsapp) }}" target="_blank"
                       class="mt-3 w-full flex items-center justify-center gap-2 py-2 bg-green-500 text-white text-xs font-bold rounded-lg hover:bg-green-600 transition">
                        💬 Hubungi via WhatsApp
                    </a>
                    @endif
                </div>
                @endif
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5">
                    <h4 class="font-bold text-green-800 mb-2 flex items-center gap-2"><span>✅</span> Garansi Kami</h4>
                    <ul class="space-y-2 text-sm text-green-700">
                        <li>• Pakaian diproses higienis & aman</li>
                        <li>• Produk luntur dipisah otomatis</li>
                        <li>• Notifikasi ketika cucian selesai</li>
                        <li>• Kepuasan Pelanggan Terjamin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         TAB: PEMBAYARAN
    ═══════════════════════════════════════ --}}
    <div x-show="tab==='pembayaran'" x-transition x-cloak>
        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-slate-900">Tagihan & Pembayaran</h2>
            <p class="text-slate-500 mt-1">Rincian biaya untuk setiap pesanan Anda.</p>
        </div>
        @if($pendingPayments->count() > 0)
        <div class="space-y-4 mb-8">
            <h3 class="text-sm font-black text-orange-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 bg-orange-500 rounded-full animate-ping"></span> Menunggu Pembayaran ({{ $pendingPayments->count() }})
            </h3>
            @foreach($pendingPayments as $trx)
            <div class="bg-white rounded-2xl border-2 border-orange-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-orange-50 flex flex-wrap justify-between items-center gap-3">
                    <div>
                        <p class="text-xs text-orange-600 font-bold uppercase tracking-wide">Nomor Nota</p>
                        <p class="text-xl font-black text-orange-700">{{ $trx->invoice_code }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-bold uppercase">Total Tagihan</p>
                        <p class="text-3xl font-black text-primary">Rp {{ number_format($trx->total_price,0,',','.') }}</p>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <table class="w-full text-sm">
                        <thead><tr class="text-xs text-slate-400 uppercase tracking-wide text-left border-b border-slate-100">
                            <th class="pb-2">Layanan</th><th class="pb-2 text-center">Jumlah</th><th class="pb-2 text-right">Subtotal</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($trx->details as $d)
                            <tr>
                                <td class="py-2 font-medium text-slate-700">{{ $d->service->name }}</td>
                                <td class="py-2 text-center text-slate-500">{{ $d->quantity }} {{ $d->service->unit }}</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot><tr class="border-t-2 border-slate-200">
                            <td colspan="2" class="pt-3 font-black text-slate-900">TOTAL</td>
                            <td class="pt-3 text-right font-black text-primary text-lg">Rp {{ number_format($trx->total_price,0,',','.') }}</td>
                        </tr></tfoot>
                    </table>
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs font-medium px-4 py-2.5 rounded-lg">
                        💳 Lakukan pembayaran saat mengambil cucian Anda di kasir Rumah Laundry.
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @php $paid = $transactions->whereIn('status',['selesai','diambil','cuci','kering','setrika']) @endphp
        @if($paid->count() > 0)
        <h3 class="text-sm font-black text-green-700 uppercase tracking-widest mb-4 flex items-center gap-2"><span>✅</span> Riwayat Transaksi Lainnya</h3>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50"><tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nota</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Jenis Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Total</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($paid as $trx)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-3 font-bold text-primary">{{ $trx->invoice_code }}</td>
                        <td class="px-6 py-3 text-slate-500 whitespace-nowrap">{{ $trx->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3">
                            @foreach($trx->details->take(1) as $d)
                            <span class="text-xs text-slate-600">{{ $d->service->name }}</span>
                            @endforeach
                            @if($trx->details->count() > 1) <span class="text-xs text-slate-400">+{{ $trx->details->count()-1 }} lainnya</span> @endif
                        </td>
                        <td class="px-6 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ in_array($trx->status,['cuci','kering','setrika']) ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $trx->status }}</span></td>
                        <td class="px-6 py-3 text-right font-extrabold text-slate-900">Rp {{ number_format($trx->total_price,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if($pendingPayments->count() === 0 && $paid->count() === 0)
        <div class="text-center py-20 text-slate-400"><div class="text-5xl mb-4">💳</div><p class="font-bold">Belum ada tagihan</p></div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════
         TAB: STATUS LAUNDRY
    ═══════════════════════════════════════ --}}
    <div x-show="tab==='status'" x-transition x-cloak>
        <div class="mb-6 flex flex-wrap gap-4 justify-between items-end">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900">Status Laundry</h2>
                <p class="text-slate-500 mt-1">Pantau progress cucian Anda secara real-time.</p>
            </div>
            {{-- Lacak manual by invoice --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 flex gap-2" x-data="{inv:'',res:null,err:'',loading:false}">
                <input x-model="inv" type="text" placeholder="Cek via No. Nota..." class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-48 outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <button @click="(async()=>{ if(!inv)return; loading=true; err=''; res=null;
                    const r=await fetch('/api/track/'+inv); const d=await r.json();
                    loading=false; r.ok ? res=d.data : err='Nota tidak ditemukan.'; })()" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-800 transition flex-shrink-0">
                    <span x-show="!loading">Lacak</span>
                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </button>
                <div x-show="err" class="absolute mt-14 bg-red-50 text-red-600 text-xs px-3 py-2 rounded-lg border border-red-200" x-text="err"></div>
                <template x-if="res">
                    <div class="absolute mt-14 z-10 bg-white border border-slate-200 rounded-xl shadow-xl p-4 w-64 text-sm">
                        <p class="font-bold text-slate-800 mb-1" x-text="'#'+res.invoice_code"></p>
                        <p class="text-slate-500 mb-2" x-text="'Pelanggan: '+res.user?.name"></p>
                        <p class="text-xs font-black uppercase tracking-wide px-2 py-1 rounded-full inline-block"
                           :class="['cuci','kering','setrika'].includes(res.status)?'bg-blue-100 text-blue-700':res.status==='baru'?'bg-yellow-100 text-yellow-700':'bg-green-100 text-green-700'"
                           x-text="res.status"></p>
                    </div>
                </template>
            </div>
        </div>

        @if($transactions->count() > 0)
        <div class="space-y-4">
            @foreach($transactions as $trx)
            @php
                $statusOrder2 = ['baru'=>0,'cuci'=>1,'kering'=>2,'setrika'=>3,'selesai'=>4,'diambil'=>4];
                $cs = $statusOrder2[$trx->status] ?? 0;
                $steps2 = [['k'=>'baru','l'=>'Diterima','e'=>'✅'],['k'=>'cuci','l'=>'Dicuci','e'=>'🫧'],['k'=>'kering','l'=>'Kering','e'=>'💨'],['k'=>'setrika','l'=>'Setrika','e'=>'👔'],['k'=>'selesai','l'=>'Selesai','e'=>'🎉']];
                $estDone = $trx->created_at->addDays(2);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex flex-wrap justify-between items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm">#{{ $loop->iteration }}</div>
                        <div>
                            <p class="font-black text-primary">{{ $trx->invoice_code }}</p>
                            <p class="text-xs text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        {{-- Badge jadwal logistik --}}
                        @if(in_array($trx->status, ['setrika', 'selesai', 'diambil']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs font-bold">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                            Siap Diambil
                        </span>
                        @elseif(in_array($trx->status, ['cuci','kering']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl text-xs font-bold">
                            🕐 Est. selesai {{ $estDone->format('d M') }}
                        </span>
                        @endif
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Total</p>
                            <p class="font-black text-slate-800">Rp {{ number_format($trx->total_price,0,',','.') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase
                            {{ $trx->status==='baru' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' :
                               (in_array($trx->status,['cuci','kering','setrika']) ? 'bg-blue-100 text-blue-700 border border-blue-200 animate-pulse' :
                                'bg-green-100 text-green-700 border border-green-200') }}">
                            {{ $trx->status }}
                        </span>
                    </div>
                </div>
                <div class="px-6 py-5">
                    {{-- Progress --}}
                    <div class="relative flex justify-between items-start">
                        <div class="absolute top-4 left-4 right-4 h-0.5 bg-slate-100 z-0">
                            <div class="h-full bg-primary transition-all duration-700" style="width:{{ ($cs/4)*100 }}%"></div>
                        </div>
                        @foreach($steps2 as $i => $st)
                        <div class="flex flex-col items-center gap-1.5 z-10 flex-1">
                            <div class="w-8 h-8 rounded-full text-sm flex items-center justify-center {{ $i <= $cs ? 'bg-primary text-white shadow-md shadow-blue-200' : 'bg-slate-100 text-slate-400' }} transition-all">{{ $st['e'] }}</div>
                            <span class="text-xs font-semibold {{ $i <= $cs ? 'text-primary' : 'text-slate-400' }} hidden sm:block">{{ $st['l'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    {{-- Detail items --}}
                    @if($trx->details && $trx->details->count())
                    <div class="mt-5 space-y-1.5">
                        @foreach($trx->details as $d)
                        <div class="flex justify-between text-sm bg-slate-50 px-4 py-2 rounded-lg">
                            <span class="font-medium text-slate-700">{{ $d->service->name }} <span class="text-slate-400">({{ $d->quantity }} {{ $d->service->unit }})</span></span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($d->subtotal,0,',','.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 text-slate-400"><div class="text-5xl mb-4">🔍</div><p class="font-bold">Tidak ada pesanan untuk dilacak</p></div>
        @endif
    </div>

</main>

<footer class="bg-white border-t border-slate-100 py-5 mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap justify-between items-center gap-2 text-sm text-slate-400">
        <p>© 2026 <span class="font-semibold text-slate-600">Rumah Laundry Tasikmalaya</span>. All rights reserved.</p>
        @if($user->whatsapp)
        <p>Butuh bantuan? <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->whatsapp) }}" class="text-primary font-semibold hover:underline">Hubungi kami via WhatsApp</a></p>
        @else
        <p>Butuh bantuan? <a href="https://wa.me/62812345678" class="text-primary font-semibold hover:underline">Hubungi kami via WhatsApp</a></p>
        @endif
    </div>
</footer>

@php
    $servicesJson = $services->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'price'=>$s->price,'unit'=>$s->unit])->values();
@endphp
<script>
const services = @json($servicesJson);

function userApp() {
    return {
        tab: '{{ $initialTab }}',
        showProfile: {{ $errors->any() && request()->isMethod('put') ? 'true' : 'false' }},
        openOrderForm(serviceId = null) {
            this.tab = 'order';
            if (serviceId) {
                this.$nextTick(() => {
                    const sel = document.querySelector('#orderItems select');
                    if (sel) { sel.value = serviceId; recalc(); }
                });
            }
        }
    }
}

// GPS lokasi
function getLocation() {
    if (!navigator.geolocation) { alert('Browser tidak mendukung GPS.'); return; }
    navigator.geolocation.getCurrentPosition(pos => {
        document.querySelector('input[name=latitude]').value = pos.coords.latitude.toFixed(7);
        document.querySelector('input[name=longitude]').value = pos.coords.longitude.toFixed(7);
    }, () => alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.'));
}

// Order form
let itemCount = 1;
function addItem() {
    const idx = itemCount++;
    const opts = services.map(s => `<option value="${s.id}" data-price="${s.price}" data-unit="${s.unit}">${s.name} - Rp ${s.price.toLocaleString('id-ID')}/${s.unit}</option>`).join('');
    const div = document.createElement('div');
    div.className = 'order-item bg-slate-50 rounded-xl p-4 border border-slate-200 relative';
    div.innerHTML = `
        <button type="button" onclick="this.parentElement.remove();recalc()" class="absolute top-3 right-3 text-slate-400 hover:text-red-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Jenis Layanan</label>
                <select name="items[${idx}][service_id]" required onchange="recalc()" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg bg-white text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Pilih Layanan --</option>${opts}
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Jumlah</label>
                <input type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" placeholder="cth: 2.5" required oninput="recalc()"
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
        </div>
        <div class="text-right text-xs text-slate-400 font-medium item-subtotal"></div>`;
    document.getElementById('orderItems').appendChild(div);
}

function recalc() {
    let grand = 0;
    document.querySelectorAll('.order-item').forEach(item => {
        const sel = item.querySelector('select');
        const qty = parseFloat(item.querySelector('input[type=number]')?.value || 0);
        const opt = sel?.options[sel.selectedIndex];
        const price = parseFloat(opt?.dataset.price || 0);
        const unit  = opt?.dataset.unit || '';
        const subtotal = price * (qty || 0);
        grand += subtotal;
        const sub = item.querySelector('.item-subtotal');
        if (sub) sub.textContent = subtotal > 0 ? `Subtotal: Rp ${subtotal.toLocaleString('id-ID')} (${qty} ${unit})` : '';
        const lbl = item.querySelector('.item-unit');
        if (lbl && unit) lbl.textContent = `(${unit})`;
    });
    document.getElementById('grandTotal').textContent = `Rp ${grand.toLocaleString('id-ID')}`;
}

document.getElementById('orderItems').addEventListener('change', recalc);
document.getElementById('orderItems').addEventListener('input', recalc);
</script>
</body>
</html>
