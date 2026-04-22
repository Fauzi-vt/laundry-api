@extends('layouts.user')
@section('title', 'Detail Pesanan #' . $trx->invoice_code)

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ url()->previous() == url()->current() ? route('user.status') : url()->previous() }}" 
       class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-brand hover:border-brand transition shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 class="text-xl font-bold text-slate-900">Detail Pesanan</h1>
        <p class="text-sm text-slate-400 mt-0.5">Nota: <span class="font-bold text-brand">{{ $trx->invoice_code }}</span></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- ══ KIRI: STATUS & TIMELINE ══ --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Progress Card --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand flex items-center justify-center text-xl shadow-sm shadow-blue-100">
                        @php
                            $icons = ['baru'=>'📦','cuci'=>'🫧','kering'=>'💨','setrika'=>'👔','selesai'=>'🎉','diambil'=>'🤝'];
                            echo $icons[$trx->status] ?? '🧺';
                        @endphp
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Status Saat Ini</p>
                        <h2 class="text-lg font-bold text-slate-900 capitalize">{{ $trx->status }}</h2>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Estimasi Selesai</p>
                    <p class="text-sm font-bold text-slate-700">{{ $trx->created_at->addDays(2)->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Visual Timeline --}}
            @php
                $steps = [
                    ['key'=>'baru',   'label'=>'Diterima', 'time' => $trx->created_at],
                    ['key'=>'cuci',   'label'=>'Dicuci',   'time' => $trx->status == 'baru' ? null : $trx->updated_at],
                    ['key'=>'kering', 'label'=>'Kering',   'time' => in_array($trx->status, ['baru','cuci']) ? null : $trx->updated_at],
                    ['key'=>'setrika','label'=>'Setrika',  'time' => in_array($trx->status, ['baru','cuci','kering']) ? null : $trx->updated_at],
                    ['key'=>'selesai','label'=>'Selesai',  'time' => in_array($trx->status, ['baru','cuci','kering','setrika']) ? null : $trx->updated_at],
                ];
                $sOrd = ['baru'=>0,'cuci'=>1,'kering'=>2,'setrika'=>3,'selesai'=>4,'diambil'=>4];
                $curr = $sOrd[$trx->status] ?? 0;
            @endphp

            <div class="relative pl-8 space-y-8 before:content-[''] before:absolute before:left-[15px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                @foreach($steps as $i => $s)
                <div class="relative">
                    {{-- Dot --}}
                    <div class="absolute -left-[25px] top-1.5 w-4 h-4 rounded-full border-2 bg-white transition-all duration-500
                                {{ $i <= $curr ? 'border-brand scale-125' : 'border-slate-200' }}">
                        @if($i <= $curr) <div class="w-1.5 h-1.5 bg-brand rounded-full m-auto mt-[1px]"></div> @endif
                    </div>
                    
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold {{ $i <= $curr ? 'text-slate-900' : 'text-slate-300' }}">{{ $s['label'] }}</h3>
                            <p class="text-xs {{ $i <= $curr ? 'text-slate-500' : 'text-slate-300' }}">
                                {{ $i <= $curr ? 'Cucian sedang dalam tahap ' . strtolower($s['label']) : 'Menunggu antrean' }}
                            </p>
                        </div>
                        @if($s['time'] && $i <= $curr)
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                            {{ $s['time']->format('H:i') }} WIB
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Items Table Card --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Rincian Layanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 text-left">
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Layanan</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Jumlah</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Harga</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($trx->details as $d)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $d->service->name }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $d->service->category }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg font-bold text-slate-600 text-xs">
                                    {{ $d->quantity }} {{ $d->service->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-slate-500 font-medium text-xs">
                                Rp {{ number_format($d->price,0,',','.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">
                                Rp {{ number_format($d->subtotal,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-6 bg-slate-50/50 flex flex-col items-end gap-1">
                <p class="text-xs font-semibold text-slate-400 uppercase">Total Tagihan</p>
                <p class="text-2xl font-black text-brand">Rp {{ number_format($trx->total_price,0,',','.') }}</p>
                @if($trx->status == 'baru')
                <div class="mt-3 flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-100 text-amber-700 text-[10px] font-bold rounded-lg uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Menunggu Pembayaran di Kasir
                </div>
                @else
                <div class="mt-3 flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold rounded-lg uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lunas
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ KANAN: INFO PELANGGAN & HUBUNGI ══ --}}
    <div class="space-y-6">
        
        {{-- Contact Admin --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-lg shadow-emerald-200/50">
            <h3 class="font-bold text-lg mb-2">Ada pertanyaan?</h3>
            <p class="text-xs text-emerald-50/80 leading-relaxed mb-6">Hubungi admin kami jika ada kendala atau ingin mempercepat proses laundry Anda.</p>
            
            <a href="https://wa.me/62812345678?text=Halo%20Admin,%20saya%20ingin%20tanya%20tentang%20pesanan%20{{ $trx->invoice_code }}" 
               target="_blank"
               class="w-full flex items-center justify-center gap-2 bg-white text-emerald-600 font-bold py-3 rounded-2xl hover:bg-emerald-50 transition shadow-sm text-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 0 5.414 0 12.05c0 2.123.552 4.197 1.598 6.03L0 24l6.105-1.598a11.826 11.826 0 005.946 1.654h.005c6.637 0 12.05-5.414 12.05-12.05 0-3.217-1.252-6.241-3.526-8.515"/></svg>
                Chat Admin via WA
            </a>
        </div>

        {{-- Lokasi Card --}}
        @if($trx->user->address)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Lokasi Penjemputan
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $trx->user->address }}</p>
            
            @if($trx->user->latitude && $trx->user->longitude)
            <div id="trxMap" class="w-full h-36 rounded-2xl border border-slate-100 z-0"></div>
            @endif
        </div>
        @endif

        {{-- Info Card --}}
        <div class="bg-slate-900 rounded-3xl p-6 text-white">
            <div class="space-y-4 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">Metode Pengiriman</span>
                    <span class="font-semibold">Antar-Jemput</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Pembayaran</span>
                    <span class="font-semibold">Bayar di Kasir (Cash/QR)</span>
                </div>
                <div class="flex justify-between pt-4 border-t border-white/10">
                    <span class="text-slate-400">Butuh Bantuan?</span>
                    <span class="font-semibold">0812-3456-7890</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
@if($trx->user->latitude && $trx->user->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tLat = {{ $trx->user->latitude }};
        const tLng = {{ $trx->user->longitude }};
        const trxMap = L.map('trxMap', { zoomControl: false, dragging: false, touchZoom: false, scrollWheelZoom: false, doubleClickZoom: false }).setView([tLat, tLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(trxMap);
        L.marker([tLat, tLng]).addTo(trxMap);
    });
</script>
@endif
@endsection
