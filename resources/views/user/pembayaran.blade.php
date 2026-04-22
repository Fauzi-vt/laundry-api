@extends('layouts.user')
@section('title', 'Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Tagihan & Pembayaran</h1>
    <p class="text-sm text-slate-400 mt-0.5">Rincian biaya untuk setiap pesanan Anda.</p>
</div>

{{-- MENUNGGU PEMBAYARAN --}}
@if($pendingPayments->count() > 0)
<div class="mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
        <h2 class="text-sm font-bold text-amber-700">Menunggu Pembayaran ({{ $pendingPayments->count() }})</h2>
    </div>
    <div class="space-y-4">
        @foreach($pendingPayments as $trx)
        <div class="bg-white rounded-2xl border-2 border-amber-200 shadow-sm overflow-hidden hover:shadow-md transition cursor-pointer"
             onclick="window.location='{{ route('user.show', $trx->id) }}'">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 bg-amber-50 border-b border-amber-100">
                <div>
                    <p class="text-[11px] font-semibold text-amber-600 uppercase tracking-wide">Nomor Nota</p>
                    <p class="text-xl font-bold text-amber-800 mt-0.5">{{ $trx->invoice_code }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Total Tagihan</p>
                    <p class="text-2xl font-bold text-brand mt-0.5">Rp {{ number_format($trx->total_price,0,',','.') }}</p>
                </div>
            </div>
            <div class="px-6 py-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="pb-2.5 text-[11px] font-semibold text-slate-400 uppercase">Layanan</th>
                            <th class="pb-2.5 text-[11px] font-semibold text-slate-400 uppercase text-center">Jumlah</th>
                            <th class="pb-2.5 text-[11px] font-semibold text-slate-400 uppercase text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($trx->details as $d)
                        <tr>
                            <td class="py-2.5 font-medium text-slate-700">{{ $d->service->name }}</td>
                            <td class="py-2.5 text-center text-slate-500 text-xs">{{ $d->quantity }} {{ $d->service->unit }}</td>
                            <td class="py-2.5 text-right font-semibold text-slate-800">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <td colspan="2" class="pt-3 font-bold text-slate-900 text-sm">TOTAL</td>
                            <td class="pt-3 text-right font-bold text-brand text-base">Rp {{ number_format($trx->total_price,0,',','.') }}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-4 flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-medium px-4 py-2.5 rounded-xl">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Lakukan pembayaran saat mengambil cucian di kasir Rumah Laundry.
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- RIWAYAT TRANSAKSI --}}
@php $paid = $transactions->whereNotIn('status', ['baru']) @endphp
@if($paid->count() > 0)
<div>
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <h2 class="text-sm font-bold text-slate-700">Riwayat Transaksi</h2>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase">Nota</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase">Tanggal</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase">Layanan</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold text-slate-400 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($paid as $trx)
                    @php
                    $stCls = in_array($trx->status,['selesai','diambil']) ? 'badge-green' : 'badge-blue';
                    @endphp
                    <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('user.show', $trx->id) }}'">
                        <td class="px-5 py-3.5 font-semibold text-brand text-sm">{{ $trx->invoice_code }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 whitespace-nowrap">{{ $trx->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            @foreach($trx->details->take(1) as $d){{ $d->service->name }}@endforeach
                            @if($trx->details->count()>1) <span class="text-slate-400">+{{ $trx->details->count()-1 }}</span>@endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $stCls }}">{{ $trx->status }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-slate-900">Rp {{ number_format($trx->total_price,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($pendingPayments->count() === 0 && $paid->count() === 0)
<div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
    <div class="text-5xl mb-4">💳</div>
    <p class="font-semibold text-slate-600">Belum ada tagihan</p>
    <p class="text-sm text-slate-400 mt-1">Buat pesanan pertama Anda untuk mulai!</p>
    <a href="{{ route('user.order') }}" class="mt-4 inline-flex items-center gap-2 bg-brand text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-brand-dark transition shadow-lg shadow-blue-200">
        Buat Pesanan
    </a>
</div>
@endif
@endsection
