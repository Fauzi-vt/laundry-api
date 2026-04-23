@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Keuangan</h2>
            <p class="text-sm text-slate-500 mt-1">Ikhtisar pendapatan dan riwayat transaksi sukses.</p>
        </div>
        <button onclick="window.print()"
                class="px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-sm flex items-center gap-2 no-print">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 text-center md:col-span-2 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Akumulasi Pendapatan</p>
            <p class="text-5xl font-black text-brand tracking-tighter">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-3 font-medium">Berdasarkan semua transaksi berstatus <span class="text-slate-900 font-bold">Selesai</span> / <span class="text-slate-900 font-bold">Diambil</span></p>
        </div>
        <div class="bg-brand rounded-3xl shadow-xl shadow-blue-100 p-8 text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-2">Bulan Ini</p>
                <p class="text-3xl font-black">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                <div class="mt-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-blue-50 uppercase">{{ now()->format('F Y') }}</span>
                </div>
            </div>
            {{-- Decorative SVG --}}
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.37-.31-2.42-1.13-2.42-2.52h1.76c0 .54.42.92 1.15.92.73 0 1.25-.33 1.25-1 0-.6-.42-.87-1.46-1.13-1.44-.36-3.08-.85-3.08-2.67 0-1.28.98-2.28 2.32-2.61V7.09h2.82V9c1.08.18 2.05.8 2.21 2.07h-1.84c-.08-.54-.53-.94-1.22-.94-.65 0-1.09.34-1.09.84 0 .54.41.81 1.48 1.09 1.44.38 3.12.87 3.12 2.76 0 1.34-1.01 2.22-2.44 2.27z"/></svg>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-lg font-bold text-slate-900">Riwayat Pendapatan Terbaru</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">10 Transaksi Terakhir</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Selesai</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Invoice</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rincian Layanan</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse($latestIncomes as $trx)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-xs font-semibold text-slate-700">{{ $trx->updated_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx->updated_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="font-extrabold text-brand text-xs tracking-tight">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($trx->details as $d)
                                <span class="inline-flex px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-medium border border-slate-200">
                                    {{ $d->service->name }} ({{ $d->quantity }})
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right font-black text-slate-900">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="text-5xl mb-4">📈</div>
                            <p class="font-bold text-slate-800">Belum ada data pendapatan</p>
                            <p class="text-xs text-slate-400 mt-1">Selesaikan transaksi untuk melihat laporan di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
