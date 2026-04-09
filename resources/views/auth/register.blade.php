@extends('layouts.app')

@section('title', 'Pendaftaran Akun Pegawai — Rumah Laundry Tasikmalaya')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-blue-50 via-slate-50 to-indigo-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <!-- Logo and Header -->
        <div class="text-center">
            <div class="mx-auto w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-2xl shadow-lg ring-4 ring-blue-50">
                L
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-slate-900 tracking-tight">
                Buat Akun Baru
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Pendaftaran pegawai Rumah Laundry
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-100 flex items-start gap-3">
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

        <form class="mt-8 space-y-5" action="/register" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                            class="appearance-none block w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors" 
                            placeholder="Budi Santoso" value="{{ old('name') }}">
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                            class="appearance-none block w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors" 
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>
                
                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Ulangi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                            class="appearance-none block w-full pl-10 px-3 py-2.5 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors" 
                            placeholder="Ulangi kata sandi">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-md mt-6">
                    Daftar Akun
                </button>
            </div>
            
            <div class="mt-6 text-center text-sm text-slate-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-blue-800 transition">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
