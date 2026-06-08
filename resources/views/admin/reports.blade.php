@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('styles')
<style>
    @media print {
        @page {
            size: auto; /* Memungkinkan pemilihan Potret atau Lanskap secara bebas pada dialog cetak */
            margin: 15mm 15mm 15mm 15mm;
        }

        /* Hide web layout UI elements */
        aside, header, footer, .no-print {
            display: none !important;
        }
        
        /* Reset padding for print container */
        .lg\:pl-64, .lg\:pl-20, .page-fade, main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        
        body {
            background-color: white !important;
            color: black !important;
            font-size: 11px !important;
        }

        /* Toggle layout visibility */
        .print-layout {
            display: block !important;
        }
        .screen-layout {
            display: none !important;
        }
        
        /* Print tables design */
        table {
            width: 100% !important;
            table-layout: fixed !important; /* Memaksa kolom mengikuti rasio lebar persentase */
            border-collapse: collapse !important;
            margin-top: 10px !important;
        }
        th, td {
            border: 1px solid #94a3b8 !important;
            padding: 6px 8px !important;
            text-align: left !important;
            color: black !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        th {
            background-color: #f1f5f9 !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            font-size: 10px !important;
        }
        tr {
            page-break-inside: avoid !important;
        }
    }
    .print-layout {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="screen-layout space-y-8">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Laporan Keuangan</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ikhtisar pendapatan dan riwayat transaksi sukses.</p>
        </div>
        <button onclick="window.print()"
                class="px-6 py-3 bg-slate-900 dark:bg-slate-800 text-white font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-700 transition shadow-sm flex items-center gap-2 no-print focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm dark:shadow-none p-8 text-center md:col-span-2 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Total Akumulasi Pendapatan</p>
            <p class="text-5xl font-black text-brand tracking-tighter">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-3 font-medium">Berdasarkan semua transaksi berstatus <span class="text-slate-900 dark:text-slate-200 font-bold">Selesai</span> / <span class="text-slate-900 dark:text-slate-200 font-bold">Diambil</span></p>
        </div>
        <div class="bg-brand dark:bg-brand-dark/30 dark:border dark:border-brand/20 rounded-3xl shadow-xl shadow-brand/10 dark:shadow-none p-8 text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-brand-light/90 dark:text-brand-ring uppercase tracking-widest mb-2">Bulan Ini</p>
                <p class="text-3xl font-black">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                <div class="mt-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-brand-light uppercase">{{ now()->format('F Y') }}</span>
                </div>
            </div>
            {{-- Decorative SVG --}}
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.37-.31-2.42-1.13-2.42-2.52h1.76c0 .54.42.92 1.15.92.73 0 1.25-.33 1.25-1 0-.6-.42-.87-1.46-1.13-1.44-.36-3.08-.85-3.08-2.67 0-1.28.98-2.28 2.32-2.61V7.09h2.82V9c1.08.18 2.05.8 2.21 2.07h-1.84c-.08-.54-.53-.94-1.22-.94-.65 0-1.09.34-1.09.84 0 .54.41.81 1.48 1.09 1.44.38 3.12.87 3.12 2.76 0 1.34-1.01 2.22-2.44 2.27z"/></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center bg-slate-50/30 dark:bg-slate-800/80">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Riwayat Pendapatan Terbaru</h3>
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">10 Transaksi Terakhir</span>
        </div>
        <div class="w-full overflow-x-auto scrollbar-hide rounded-lg">
            <table class="w-full min-w-max divide-y divide-slate-50 dark:divide-slate-700">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                    <tr class="whitespace-nowrap">
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Waktu Selesai</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Invoice</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Rincian Layanan</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($latestIncomes as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $trx->updated_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $trx->updated_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="font-extrabold text-brand dark:text-brand text-xs tracking-tight">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $trx->user->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($trx->details as $d)
                                <span class="inline-flex px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[10px] font-medium border border-slate-200 dark:border-slate-600">
                                    {{ $d->service->name }} ({{ $d->quantity }})
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right font-black text-slate-900 dark:text-white">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="text-5xl mb-4">📈</div>
                            <p class="font-bold text-slate-800 dark:text-slate-200">Belum ada data pendapatan</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Selesaikan transaksi untuk melihat laporan di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     PRINT REPORT LAYOUT
     ══════════════════════════════════════════════ --}}
<div class="print-layout space-y-6 hidden">
    {{-- Header Dokumen --}}
    <div class="flex justify-between items-start border-b-2 border-slate-800 pb-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">RUMAH LAUNDRY</h1>
            <p class="text-xs text-slate-600 font-semibold mt-0.5">Layanan Laundry Profesional Terpercaya</p>
            <p class="text-[9px] text-slate-500 mt-1 max-w-sm">M42G+RHR, Jl. Muktamar NU. XXIX, Cipakat, Kec. Singaparna, Kabupaten Tasikmalaya, Jawa Barat 46417</p>
            <p class="text-[9px] text-slate-400 mt-0.5">Sistem Laporan Keuangan Digital CIPASUNG</p>
        </div>
        <div class="text-right">
            <h2 class="text-base font-bold text-slate-900 tracking-wide">LAPORAN PENDAPATAN KEUANGAN</h2>
            <div class="text-[10px] text-slate-600 space-y-0.5 mt-1">
                <p><span class="font-bold">Periode:</span> {{ now()->translatedFormat('F Y') }}</p>
                <p><span class="font-bold">Tanggal Cetak:</span> <span class="print-time">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span></p>
                <p><span class="font-bold">Operator:</span> {{ auth()->user()->name }} (Admin)</p>
            </div>
        </div>
    </div>

    {{-- I. Ringkasan Keuangan --}}
    <div class="space-y-2">
        <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-l-2 border-slate-800 pl-2">I. Ringkasan Pendapatan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="border border-slate-300 rounded-lg p-3 bg-slate-50/50">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total Akumulasi Pendapatan (Tahunan)</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="border border-slate-300 rounded-lg p-3 bg-slate-50/50">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Pendapatan Bulan Ini ({{ now()->translatedFormat('F Y') }})</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- II. Rincian Pendapatan Terbaru --}}
    <div class="space-y-2">
        <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-l-2 border-slate-800 pl-2">II. Rincian 10 Transaksi Terakhir</h3>
        <table class="text-[10px]">
            <colgroup>
                <col style="width: 5%;">
                <col style="width: 20%;">
                <col style="width: 15%;">
                <col style="width: 20%;">
                <col style="width: 25%;">
                <col style="width: 15%;">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Waktu Transaksi</th>
                    <th>Kode Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Rincian Layanan</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestIncomes as $index => $trx)
                <tr>
                    <td class="text-center font-semibold">{{ $index + 1 }}</td>
                    <td>{{ $trx->updated_at->translatedFormat('d M Y, H:i') }} WIB</td>
                    <td class="font-extrabold tracking-tight">{{ $trx->invoice_code }}</td>
                    <td class="font-semibold">{{ $trx->user->name ?? '-' }}</td>
                    <td>
                        <div class="space-y-0.5">
                            @foreach($trx->details as $d)
                            <div>• {{ $d->service->name }} ({{ $d->quantity }} {{ $d->service->unit }})</div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-right font-black">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-slate-500">Belum ada data pendapatan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
            @if(count($latestIncomes) > 0)
            <tfoot>
                <tr class="font-bold bg-slate-100">
                    <td colspan="5" class="text-right uppercase font-bold text-[9px] tracking-wider">Subtotal Riwayat Laporan (10 Transaksi)</td>
                    <td class="text-right text-xs font-black text-slate-900 border-t-2 border-slate-400">Rp {{ number_format($latestIncomes->sum('total_price'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- III. Lembar Pengesahan --}}
    <div class="pt-8">
        <div class="flex justify-between items-center text-[10px]">
            <div class="text-center w-40">
                <p class="text-slate-600 mb-14">Dibuat Oleh,</p>
                <div class="border-b border-slate-800 w-full mx-auto mb-1"></div>
                <p class="font-bold uppercase">{{ auth()->user()->name }}</p>
                <p class="text-[9px] text-slate-500">Administrator</p>
            </div>
            <div class="text-center w-40">
                <p class="text-slate-600 mb-14">Menyetujui/Mengetahui,</p>
                <div class="border-b border-slate-800 w-full mx-auto mb-1"></div>
                <p class="font-bold uppercase">Owner Rumah Laundry</p>
                <p class="text-[9px] text-slate-500">Pemilik Usaha</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updatePrintTime() {
            const timeElements = document.querySelectorAll('.print-time');
            const now = new Date();
            
            // Nama bulan dalam Bahasa Indonesia
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const dateStr = String(now.getDate()).padStart(2, '0') + ' ' + 
                            months[now.getMonth()] + ' ' + 
                            now.getFullYear() + ', ' + 
                            String(now.getHours()).padStart(2, '0') + ':' + 
                            String(now.getMinutes()).padStart(2, '0');
                            
            timeElements.forEach(el => {
                el.textContent = dateStr + ' WIB';
            });
        }

        // Perbarui waktu secara otomatis sesaat sebelum dialog cetak browser terbuka
        window.addEventListener('beforeprint', updatePrintTime);
        
        // Inisialisasi awal saat halaman dimuat
        updatePrintTime();
    });
</script>
@endsection
