@extends('layouts.app')

@section('title', 'Masuk — Rumah Laundry Tasikmalaya')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-slate-50">
    <!-- Kolom Kiri (Visual - 55% lebar) -->
    <div class="hidden md:flex md:w-[55%] relative overflow-hidden bg-slate-900">
        <!-- Background image with laundry/minimalist theme -->
        <img src="{{ asset('images/bg-auth.png') }}" 
             alt="Rumah Laundry" 
             class="absolute inset-0 w-full h-full object-cover opacity-70">
        <!-- Dark transparent gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-900/60 to-blue-900/40"></div>
        
        <!-- Content in Visual Column -->
        <div class="relative z-10 flex flex-col justify-between p-12 h-full text-white">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md border border-white/20 bg-white flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-lg font-bold tracking-tight">Rumah Laundry</span>
            </div>
            
            <div class="space-y-4 max-w-md">
                <h3 class="text-3xl font-extrabold leading-tight">Kelola Cucian Lebih Praktis & Efisien</h3>
                <p class="text-slate-300 text-sm leading-relaxed">Sistem manajemen laundry modern berbasis cloud untuk memantau performa bisnis, memproses pesanan, dan mengelola pelanggan dalam satu dasbor.</p>
            </div>
            
            <p class="text-xs text-slate-400">© 2026 Rumah Laundry Tasikmalaya. All rights reserved.</p>
        </div>
    </div>

    <!-- Kolom Kanan (Form - 45% lebar) -->
    <div class="w-full md:w-[45%] bg-white flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 overflow-y-auto min-h-screen">
        <div class="max-w-md w-full mx-auto space-y-8 relative">
            
            <!-- Close Button (Back to Landing Page) -->
            <a href="/" class="absolute -top-6 -right-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 p-1.5 rounded-lg transition-colors" title="Kembali ke Beranda">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>

            <!-- Logo Placeholder for Mobile & Header -->
            <div class="space-y-6">
                <div class="flex md:hidden items-center justify-center mb-4">
                    <div class="w-12 h-12 rounded-xl overflow-hidden shadow-md border border-slate-100 flex items-center justify-center bg-white ring-4 ring-blue-50">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <!-- Logo Placeholder for Desktop (hidden on mobile) -->
                    <div class="hidden md:flex mb-6">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shadow-md border border-slate-100 flex items-center justify-center bg-white ring-4 ring-blue-50">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Selamat Datang
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Akses sistem manajemen Rumah Laundry
                    </p>
                </div>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="flex flex-col">
                        <span class="font-bold mb-1">Terdapat kesalahan:</span>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Success Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form class="space-y-6" action="/login" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Alamat Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="appearance-none block w-full pl-11 pr-4 py-3 bg-slate-50 border border-transparent rounded-xl shadow-inner placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white text-sm transition-all duration-300" 
                                placeholder="nama@email.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- Kata Sandi Field -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="appearance-none block w-full pl-11 pr-4 py-3 bg-slate-50 border border-transparent rounded-xl shadow-inner placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white text-sm transition-all duration-300" 
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4.5 w-4.5 text-blue-600 focus:ring-blue-500 border-slate-300 rounded cursor-pointer transition">
                        <label for="remember" class="ml-2 block text-slate-700 cursor-pointer select-none font-medium">
                            Ingat saya
                        </label>
                    </div>

                    <div>
                        <a href="#" class="font-bold text-blue-600 hover:text-blue-700 transition">
                            Lupa sandi?
                        </a>
                    </div>
                </div>

                <!-- Tombol Login Utama -->
                <div>
                    <button type="submit" 
                        class="w-full flex justify-center items-center py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-xl hover:shadow-blue-500/20 active:scale-[0.98] hover:-translate-y-0.5 transition-all duration-300 outline-none text-sm">
                        Masuk ke Sistem
                    </button>
                </div>
                
                <!-- Register Link -->
                <div class="text-center text-sm text-slate-600">
                    Belum punya akun pegawai? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
