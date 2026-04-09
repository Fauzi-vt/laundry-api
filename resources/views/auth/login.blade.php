@extends('layouts.app')

@section('title', 'Masuk — Rumah Laundry Tasikmalaya')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-blue-50 via-slate-50 to-indigo-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <!-- Logo and Header -->
        <div class="text-center">
            <div class="mx-auto w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-2xl shadow-lg ring-4 ring-blue-50">
                L
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-slate-900 tracking-tight">
                Selamat Datang
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Akses sistem manajemen Rumah Laundry
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="/login" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none block w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors" 
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none block w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors" 
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded cursor-pointer transition">
                    <label for="remember" class="ml-2 block text-sm text-slate-700 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-primary hover:text-blue-800 transition">
                        Lupa sandi?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-md mt-2">
                    Masuk ke Sistem
                </button>
            </div>
            
            <div class="mt-6 text-center text-sm text-slate-600">
                Belum punya akun pegawai? 
                <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-blue-800 transition">Daftar sekarang</a>
            </div>
        </form>
    </div>
</div>
@endsection
