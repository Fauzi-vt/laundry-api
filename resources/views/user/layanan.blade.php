@extends('layouts.user')
@section('title', 'Layanan')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Daftar Layanan & Harga</h1>
    <p class="text-sm text-slate-400 mt-0.5">Harga transparan, tidak ada biaya tersembunyi.</p>
</div>

@php
$grouped = $services->groupBy('category_id');
@endphp

<div class="space-y-6">
@foreach($grouped as $catId => $items)
@php 
    $cat = $items->first()->categoryRelation; 
@endphp
@if($cat)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    {{-- Category Header --}}
    <div class="flex items-center gap-4 px-6 py-5 {{ $cat->bg_class }} border-b {{ $cat->border_class }}">
        <div class="w-12 h-12 rounded-xl {{ $cat->bg_class }} border {{ $cat->border_class }} flex items-center justify-center text-2xl shadow-sm">
            {{ $cat->icon ?? '🧺' }}
        </div>
        <div class="flex-1">
            <h2 class="text-base font-bold {{ $cat->text_class }}">{{ $cat->name }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ $cat->description }}</p>
        </div>
        <span class="text-xs font-semibold {{ $cat->text_class }} {{ $cat->bg_class }} border {{ $cat->border_class }} px-3 py-1 rounded-full">
            {{ $items->count() }} layanan
        </span>
    </div>
    {{-- Service Cards Grid --}}
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($items as $svc)
        <div class="group relative bg-slate-50 hover:bg-white border border-slate-100 hover:border-brand/30 hover:shadow-md rounded-xl p-4 transition-all duration-200 cursor-pointer"
             onclick="window.location='{{ route('user.order', ['service_id' => $svc->id]) }}'">
            <div class="flex items-start justify-between mb-3">
                @if($svc->image)
                    <img src="{{ asset('images/services/' . $svc->image) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                @else
                    <span class="text-2xl">{{ $svc->icon }}</span>
                @endif
                <span class="text-[11px] font-semibold {{ $cat->text_class }} {{ $cat->bg_class }} border {{ $cat->border_class }} px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition">
                    Pesan
                </span>
            </div>
            <p class="font-semibold text-slate-800 text-sm leading-snug mb-1">{{ $svc->name }}</p>
            @if($svc->description)
            <p class="text-[11px] text-slate-400 leading-relaxed mb-3">{{ $svc->description }}</p>
            @endif
            <div class="flex items-end justify-between pt-3 border-t border-slate-200">
                <div>
                    <p class="text-lg font-bold text-slate-900">Rp {{ number_format($svc->price,0,',','.') }}</p>
                    <p class="text-[11px] text-slate-400">per {{ $svc->unit }}</p>
                </div>
                <a href="{{ route('user.order', ['service_id' => $svc->id]) }}"
                   class="flex items-center gap-1.5 text-xs font-semibold {{ $cat->text_class }} hover:underline">
                    Pesan sekarang
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@if($services->isEmpty())
<div class="bg-white rounded-2xl border border-slate-100 py-20 text-center">
    <div class="text-5xl mb-4">🧺</div>
    <p class="font-semibold text-slate-600">Belum ada layanan tersedia.</p>
</div>
@endif
</div>
@endsection
