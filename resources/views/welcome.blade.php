<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Laundry Tasikmalaya - Solusi Pakaian Bersih Anda</title>
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
                        primary: { DEFAULT: '#2563eb', dark: '#1d4ed8', light: '#eff6ff', ring: '#bfdbfe' }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col" x-data="landingPage()">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 relative z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center shadow-sm border border-slate-100 bg-white">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 leading-none tracking-tight">Rumah Laundry</p>
                                <p class="text-[10px] text-slate-400 font-medium leading-none mt-1">Tasikmalaya</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-bold bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-300 shadow-md inline-flex items-center justify-center text-center">Dashboard</a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                class="text-sm font-semibold text-gray-600 hover:text-red-600 transition-colors duration-300">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}"
                            class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors duration-300 hidden sm:block">Daftar Akun</a>
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-300 shadow-md inline-flex items-center justify-center text-center">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Login -->
    <div x-show="showLogin" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showLogin" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showLogin = false">
            </div>

            <!-- Modal panel -->
            <div x-show="showLogin" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block w-full max-w-md p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100">

                <button @click="showLogin = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="text-center mb-6">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden flex items-center justify-center shadow-xl border border-slate-100 bg-white">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900">Selamat Datang</h3>
                    <p class="text-sm text-slate-500 mt-1">Akses sistem Rumah Laundry</p>
                </div>

                @if ($errors->any() && !old('name'))
                    <!-- Menampilkan error login jika old('name') kosong (karena form register pakai name) -->
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm border border-red-100 mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="••••••••">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-slate-600">
                            <input type="checkbox" name="remember"
                                class="mr-2 text-primary focus:ring-primary rounded border-slate-300"> Ingat saya
                        </label>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-xl hover:shadow-blue-500/20 active:scale-95 transition-all duration-300 mt-2">Masuk</button>

                    <p class="text-center text-sm text-slate-600 mt-4">
                        Belum punya akun? <button type="button"
                            @click="showLogin = false; setTimeout(() => showRegister = true, 300)"
                            class="text-primary font-semibold hover:underline">Daftar</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Register -->
    <div x-show="showRegister" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="showRegister" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showRegister = false">
            </div>

            <!-- Modal panel -->
            <div x-show="showRegister" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block w-full max-w-md p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100">

                <button @click="showRegister = false"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-slate-900">Daftar Akun</h3>
                    <p class="text-sm text-slate-500 mt-1">Registrasi pegawai ke dalam sistem</p>
                </div>

                @if ($errors->any() && old('name'))
                    <!-- Menampilkan error register jika old('name') ada -->
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm border border-red-100 mb-4">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/register" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="Budi Santoso" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="Min. 8 Karakter">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                            placeholder="Ulangi password">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-xl hover:shadow-blue-500/20 active:scale-95 transition-all duration-300 mt-2">Buat Akun</button>

                    <p class="text-center text-sm text-slate-600 mt-4">
                        Sudah punya akun? <button type="button"
                            @click="showRegister = false; setTimeout(() => showLogin = true, 300)"
                            class="text-primary font-semibold hover:underline">Masuk</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <div class="relative bg-slate-50/60 overflow-hidden border-b border-slate-200/50 py-12 lg:py-20">
        <!-- Soft decorative background gradients for a premium feel -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100/40 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100/40 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Left Column: Teks Promosi / Copywriting -->
                <div class="space-y-8 text-center lg:text-left">
                    <div class="space-y-4">
                        <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                            Cuci Bersih, Cepat, &amp;&nbsp;<br class="hidden sm:inline">
                            <span class="text-blue-600">Wangi Sepanjang Hari</span>
                        </h1>
                        <p class="text-lg text-gray-500 leading-relaxed mt-6 max-w-xl mx-auto lg:mx-0">
                            Kami ahlinya dalam merawat pakaian Anda. Mulai dari cuci kiloan, sepatu, hingga selimut.
                            Nikmati layanan laundry premium berkualitas tinggi dengan hasil cucian harum higienis di Singaparna, Tasikmalaya.
                        </p>
                    </div>

                    <!-- Keunggulan (Trust Badges) -->
                    <div class="flex flex-wrap items-center gap-4 justify-center lg:justify-start">
                        <!-- Badge 1: Truck -->
                        <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-full pl-2 pr-4.5 py-1.5 shadow-sm hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                            <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.75a1.125 1.125 0 01-1.125-1.125V15m1.5 3.75h1.5m11.25-3.75h1.5a1.125 1.125 0 011.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125H9.75m10.5-3.75H9.75m0 0V8.25m0 0h5.625c.621 0 1.125.504 1.125 1.125v3.375c0 .621-.504 1.125-1.125 1.125h-5.625M9.75 8.25V4.875c0-.621-.504-1.125-1.125-1.125H3.75A1.125 1.125 0 002.625 4.875V15"></path>
                                </svg>
                            </span>
                            <span class="text-xs font-bold text-slate-700">Gratis Antar-Jemput</span>
                        </div>
                        <!-- Badge 2: Star -->
                        <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-full pl-2 pr-4.5 py-1.5 shadow-sm hover:shadow-md hover:border-amber-100 transition-all duration-300 group">
                            <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </span>
                            <span class="text-xs font-bold text-slate-700">Top Rated Service</span>
                        </div>
                        <!-- Badge 3: Shield -->
                        <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-full pl-2 pr-4.5 py-1.5 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300 group">
                            <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                                </svg>
                            </span>
                            <span class="text-xs font-bold text-slate-700">100% Higienis</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Widget Pelacakan Cucian -->
                <div class="relative w-full aspect-[4/3] sm:aspect-[16/10] lg:aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center p-6 sm:p-8">
                    <!-- Inner Card -->
                    <div class="relative z-10 bg-white/90 p-8 rounded-xl shadow-lg w-full max-w-md">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelacakan Status Cucian</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">Masukkan Nomor Nota / Invoice Code Anda untuk melihat perkembangan cucian Anda secara real-time.</p>

                        <div>
                            <form @submit.prevent="track" class="flex gap-2">
                                <input type="text" x-model="invoice" placeholder="Contoh: INV-DUMMY123"
                                    class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder-gray-400"
                                    required>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg transition duration-300 flex items-center justify-center min-w-[80px]">
                                    <span x-show="!loading" class="text-sm">Lacak</span>
                                    <svg x-show="loading" class="animate-spin h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        style="display: none;">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </button>
                            </form>

                            <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100"
                                style="display: none;" x-text="errorMsg"></div>

                            <div x-show="result" class="mt-6 border-t border-slate-200/80 pt-4" style="display: none;">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase font-semibold">Pelanggan</p>
                                        <p class="font-medium text-slate-800" x-text="result?.user?.name"></p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800': result?.status === 'baru',
                                                'bg-blue-100 text-blue-800': result?.status === 'cuci' || result?.status === 'kering' || result?.status === 'setrika',
                                                'bg-green-100 text-green-800': result?.status === 'selesai' || result?.status === 'diambil'
                                            }" x-text="result?.status"></span>
                                    </div>
                                </div>

                                <!-- Timeline Status -->
                                <div class="relative mt-5">
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-200/60">
                                        <div :style="`width: ${getProgress()}%`"
                                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-500">
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-bold text-slate-500">
                                        <span :class="{'text-blue-600': getProgress() >= 20}">Baru</span>
                                        <span :class="{'text-blue-600': getProgress() >= 40}">Cuci</span>
                                        <span :class="{'text-blue-600': getProgress() >= 60}">Kering</span>
                                        <span :class="{'text-blue-600': getProgress() >= 80}">Setrika</span>
                                        <span :class="{'text-blue-600': getProgress() >= 100}">Selesai</span>
                                    </div>
                                </div>

                                <div class="mt-4 bg-slate-50/80 rounded-xl p-3 text-xs border border-slate-100">
                                    <p class="font-bold mb-2 text-slate-900">Detail Pakaian</p>
                                    <template x-for="detail in result?.details" :key="detail.id">
                                        <div class="flex justify-between mb-1.5 text-slate-700">
                                            <span x-text="`${detail.service.name} (${detail.quantity} ${detail.service.unit})`"></span>
                                            <span class="font-bold text-slate-900" x-text="formatRupiah(detail.subtotal)"></span>
                                        </div>
                                    </template>
                                    <div class="border-t border-slate-200 mt-2.5 pt-2 flex justify-between font-extrabold text-slate-900">
                                        <span>Total</span>
                                        <span class="text-blue-600 text-sm" x-text="formatRupiah(result?.total_price)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="py-16 bg-slate-50 flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Layanan Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">Daftar Jasa
                    Laundry</p>
            </div>
            <div class="mt-10 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                <!-- Card 1 -->
                <div
                    class="flex flex-col rounded-lg shadow-sm overflow-hidden border border-slate-200 bg-white hover:shadow-md transition">
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-secondary">Harian</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Cuci Kiloan</h3>
                            <p class="mt-3 text-base text-slate-500">Layanan cuci komplit mulai dari cuci, kering, dan
                                setrika. Harga hitungan per Kilogram.</p>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div
                    class="flex flex-col rounded-lg shadow-sm overflow-hidden border border-slate-200 bg-white hover:shadow-md transition">
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-secondary">Khusus</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Cuci Sepatu</h3>
                            <p class="mt-3 text-base text-slate-500">Perawatan mendalam untuk sepatu Anda agar kembali
                                bersih seperti baru. Hitung per Pasang.</p>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div
                    class="flex flex-col rounded-lg shadow-sm overflow-hidden border border-slate-200 bg-white hover:shadow-md transition">
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-secondary">Berat</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Cuci Selimut / Bedcover</h3>
                            <p class="mt-3 text-base text-slate-500">Mesin khusus kapasitas besar menjamin kebersihan
                                maksimal untuk selimut Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex justify-between items-center">
            <div>
                <p class="text-slate-700 font-bold text-sm">Rumah Laundry Tasikmalaya</p>
                <p class="text-slate-500 text-xs mt-1">M42G+RHR, Jl. Muktamar NU. XXIX, Cipakat, Kec. Singaparna, Kabupaten Tasikmalaya, Jawa Barat 46417</p>
                <p class="text-slate-400 text-xs mt-2">© 2026 Rumah Laundry Tasikmalaya - Singaparna. All rights reserved.</p>
            </div>
            <p class="text-slate-400 text-sm mt-4 sm:mt-0">Tugas Kuliah Project System - Univ. Cipasung.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('landingPage', () => ({
                // Auto open modals if there are validation errors
                showLogin: {{ ($errors->any() && !old('name')) ? 'true' : 'false' }},
                showRegister: {{ ($errors->any() && old('name')) ? 'true' : 'false' }},

                // Tracking Feature State
                invoice: '',
                loading: false,
                result: null,
                error: false,
                errorMsg: '',

                async track() {
                    if (!this.invoice) return;
                    this.loading = true;
                    this.error = false;
                    this.result = null;

                    try {
                        const res = await fetch(`/api/track/${this.invoice}`);
                        const data = await res.json();

                        if (res.ok) {
                            this.result = data.data;
                        } else {
                            this.error = true;
                            this.errorMsg = "Invoice tidak ditemukan. Silakan periksa kembali nomor nota.";
                        }
                    } catch (err) {
                        this.error = true;
                        this.errorMsg = "Terjadi kesalahan jaringan.";
                    } finally {
                        this.loading = false;
                    }
                },

                getProgress() {
                    const status = this.result?.status;
                    const map = {
                        'baru': 20,
                        'cuci': 40,
                        'kering': 60,
                        'setrika': 80,
                        'selesai': 100,
                        'diambil': 100
                    };
                    return map[status] || 0;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }))
        })
    </script>
</body>

</html>