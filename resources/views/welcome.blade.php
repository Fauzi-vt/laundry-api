<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Laundry Tasikmalaya - Solusi Pakaian Bersih Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col" x-data="landingPage()">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 relative z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">
                            L</div>
                        <span class="font-bold text-xl text-slate-900 tracking-tight">Rumah Laundry</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="showRegister = true"
                        class="text-sm font-medium text-slate-500 hover:text-primary transition hidden sm:block">Daftar
                        Akun</button>
                    <button @click="showLogin = true"
                        class="text-sm font-medium bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition shadow-sm">Login
                        Pegawai</button>
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
                    <div
                        class="mx-auto w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-2xl shadow-lg ring-4 ring-blue-50 mb-4">
                        L</div>
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
                        class="w-full bg-primary text-white font-medium py-2.5 rounded-lg hover:bg-blue-800 transition shadow-md mt-2">Masuk</button>

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
                        class="w-full bg-primary text-white font-medium py-2.5 rounded-lg hover:bg-blue-800 transition shadow-md mt-2">Buat
                        Akun</button>

                    <p class="text-center text-sm text-slate-600 mt-4">
                        Sudah punya akun? <button type="button"
                            @click="showRegister = false; setTimeout(() => showLogin = true, 300)"
                            class="text-primary font-semibold hover:underline">Masuk</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-16 lg:pt-24 px-4 sm:px-6 lg:px-8">
                <main class="mx-auto max-w-7xl">
                    <div class="sm:text-center lg:text-left">
                        <h1
                            class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl text-balance">
                            <span class="block xl:inline">Cuci Bersih, Cepat, dan</span>
                            <span class="block text-primary">Wangi Sepanjang Hari</span>
                        </h1>
                        <p
                            class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Kami ahlinya dalam merawat pakaian Anda. Mulai dari cuci kiloan, sepatu, hingga selimut.
                            Nikmati layanan premium kami di Singaparna, Tasikmalaya.
                        </p>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <div
                class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full bg-gradient-to-bl from-blue-100 to-indigo-50 flex items-center justify-center relative overflow-hidden">
                <!-- Abstract decorative element -->
                <div class="absolute w-96 h-96 bg-primary/10 rounded-full blur-3xl -top-10 -right-10"></div>
                <div class="absolute w-72 h-72 bg-secondary/20 rounded-full blur-3xl bottom-10 left-10"></div>

                <div
                    class="relative z-10 bg-white/60 backdrop-blur-md p-8 rounded-2xl shadow-xl border border-white/50 w-3/4 max-w-md">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Pelacakan Status Cucian</h3>
                    <p class="text-sm text-slate-600 mb-6">Masukkan Nomor Nota / Invoice Code Anda untuk melihat
                        perkembangan cucian.</p>

                    <div>
                        <form @submit.prevent="track" class="flex gap-2">
                            <input type="text" x-model="invoice" placeholder="Contoh: INV-DUMMY123"
                                class="flex-1 rounded-lg border border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 px-4 py-2 outline-none"
                                required>
                            <button type="submit"
                                class="bg-primary hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-medium transition flex items-center justify-center w-24">
                                <span x-show="!loading">Lacak</span>
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

                        <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm"
                            style="display: none;" x-text="errorMsg"></div>

                        <div x-show="result" class="mt-6 border-t border-slate-200 pt-4" style="display: none;">
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
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-100">
                                    <div :style="`width: ${getProgress()}%`"
                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary transition-all duration-500">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span :class="{'text-primary font-semibold': getProgress() >= 20}">Baru</span>
                                    <span :class="{'text-primary font-semibold': getProgress() >= 40}">Cuci</span>
                                    <span :class="{'text-primary font-semibold': getProgress() >= 60}">Kering</span>
                                    <span :class="{'text-primary font-semibold': getProgress() >= 80}">Setrika</span>
                                    <span :class="{'text-primary font-semibold': getProgress() >= 100}">Selesai</span>
                                </div>
                            </div>

                            <div class="mt-4 bg-slate-50 rounded p-3 text-sm">
                                <p class="font-semibold mb-2">Detail Pakaian</p>
                                <template x-for="detail in result?.details" :key="detail.id">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-600"
                                            x-text="`${detail.service.name} (${detail.quantity} ${detail.service.unit})`"></span>
                                        <span class="font-medium text-slate-800"
                                            x-text="formatRupiah(detail.subtotal)"></span>
                                    </div>
                                </template>
                                <div class="border-t border-slate-200 mt-2 pt-2 flex justify-between font-bold">
                                    <span>Total</span>
                                    <span class="text-primary" x-text="formatRupiah(result?.total_price)"></span>
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
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex justify-between items-center">
            <p class="text-slate-500 text-sm">© 2026 Rumah Laundry Tasikmalaya - Singaparna. All rights reserved.</p>
            <p class="text-slate-400 text-sm mt-2 sm:mt-0">Tugas Kuliah Project System - Univ. Cipasung.</p>
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