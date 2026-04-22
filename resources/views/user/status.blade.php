@extends('layouts.user')
@section('title', 'Status Laundry')

@section('content')
<div class="flex flex-wrap items-start justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Status Laundry</h1>
        <p class="text-sm text-slate-400 mt-0.5">Pantau progress cucian Anda secara real-time.</p>
    </div>

    {{-- Lacak via Nota --}}
    <div x-data="{ inv:'', res:null, err:'', loading:false }" class="flex-shrink-0">
        <div class="flex items-center gap-2 bg-white rounded-xl border border-slate-200 shadow-sm p-2">
            <input x-model="inv" type="text" placeholder="Cek via No. Nota..."
                   class="w-44 px-3 py-1.5 text-sm text-slate-700 bg-transparent outline-none placeholder:text-slate-400">
            <button @click="(async()=>{
                        if(!inv)return; loading=true; err=''; res=null;
                        try{ const r=await fetch('/api/track/'+inv); const d=await r.json(); r.ok? res=d.data : err='Nota tidak ditemukan.'; }
                        catch(e){err='Koneksi gagal.'} loading=false; })()"
                    class="flex items-center gap-1.5 bg-brand text-white text-xs font-semibold px-3.5 py-2 rounded-lg hover:bg-brand-dark transition flex-shrink-0">
                <svg x-show="loading" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span x-show="!loading">Lacak</span>
            </button>
        </div>
        <p x-show="err" x-text="err" class="text-xs text-red-500 font-medium mt-1.5 px-1"></p>
        <template x-if="res">
            <div class="mt-2 bg-white border border-slate-200 rounded-xl shadow-lg p-4 text-sm z-10 w-64">
                <p class="font-bold text-slate-800 text-sm" x-text="'#'+res.invoice_code"></p>
                <p class="text-xs text-slate-400 mt-1" x-text="'Pelanggan: '+(res.user?.name??'-')"></p>
                <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[11px] font-semibold"
                      :class="['cuci','kering','setrika'].includes(res.status)?'badge-blue':res.status==='baru'?'badge-yellow':'badge-green'"
                      x-text="res.status"></span>
            </div>
        </template>
    </div>
</div>

@if($transactions->count() > 0)
<div class="space-y-4">
    @foreach($transactions as $trx)
    @php
        $sOrd = ['baru'=>0,'cuci'=>1,'kering'=>2,'setrika'=>3,'selesai'=>4,'diambil'=>4];
        $cs   = $sOrd[$trx->status] ?? 0;
        $steps = [
            ['l'=>'Diterima',    'e'=>'✅'],
            ['l'=>'Dicuci',      'e'=>'🫧'],
            ['l'=>'Dikeringkan', 'e'=>'💨'],
            ['l'=>'Disetrika',   'e'=>'👔'],
            ['l'=>'Selesai',     'e'=>'🎉'],
        ];
        $stColors = [
            'baru'    => 'badge-yellow',
            'cuci'    => 'badge-blue',
            'kering'  => 'bg-cyan-100 text-cyan-700',
            'setrika' => 'badge-orange',
            'selesai' => 'badge-green',
            'diambil' => 'badge-green',
        ];
        $est = $trx->created_at->addDays(2);
        $done = in_array($trx->status, ['setrika','selesai','diambil']);
    @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/70 hover:bg-slate-100 transition cursor-pointer"
             onclick="window.location='{{ route('user.show', $trx->id) }}'">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-light flex items-center justify-center text-brand text-xs font-bold">#{{ $loop->iteration }}</div>
                <div>
                    <p class="font-bold text-brand text-sm">{{ $trx->invoice_code }}</p>
                    <p class="text-[11px] text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                @if($done)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full text-xs font-semibold">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span>
                    Siap Diambil
                </span>
                @elseif(in_array($trx->status,['cuci','kering']))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-200 text-blue-600 rounded-full text-xs font-semibold">
                    🕐 Est. {{ $est->format('d M') }}
                </span>
                @endif
                <div class="text-right">
                    <p class="text-[11px] text-slate-400">Total</p>
                    <p class="font-bold text-slate-900 text-sm">Rp {{ number_format($trx->total_price,0,',','.') }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $stColors[$trx->status] ?? 'badge-gray' }}">
                    {{ $trx->status }}
                </span>
            </div>
        </div>

        {{-- Progress tracker --}}
        <div class="px-6 py-5">
            <div class="relative flex justify-between items-start mb-4">
                <div class="absolute top-4 left-4 right-4 h-0.5 bg-slate-100 z-0">
                    <div class="h-full bg-brand rounded-full transition-all duration-700" style="width:{{ ($cs/4)*100 }}%"></div>
                </div>
                @foreach($steps as $i => $st)
                <div class="flex flex-col items-center gap-1.5 z-10 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm transition-all
                                {{ $i <= $cs ? 'bg-brand text-white shadow-md shadow-blue-200' : 'bg-slate-100 text-slate-400' }}">
                        {{ $st['e'] }}
                    </div>
                    <span class="text-[10px] font-medium hidden sm:block {{ $i <= $cs ? 'text-brand' : 'text-slate-400' }} text-center">{{ $st['l'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Detail items --}}
            @if($trx->details && $trx->details->count())
            <div class="space-y-1.5 mt-4 pt-4 border-t border-slate-100">
                @foreach($trx->details as $d)
                <div class="flex items-center justify-between text-sm bg-slate-50 px-4 py-2.5 rounded-xl">
                    <div>
                        <span class="font-medium text-slate-700">{{ $d->service->name }}</span>
                        <span class="text-slate-400 text-xs ml-2">({{ $d->quantity }} {{ $d->service->unit }})</span>
                    </div>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($d->subtotal,0,',','.') }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
    <div class="text-5xl mb-4">🔍</div>
    <p class="font-semibold text-slate-600">Belum ada pesanan untuk dilacak</p>
    <p class="text-sm text-slate-400 mt-1">Buat pesanan pertama Anda sekarang!</p>
    <a href="{{ route('user.order') }}"
       class="mt-5 inline-flex items-center gap-2 bg-brand text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-brand-dark transition shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Pesanan
    </a>
</div>
@endif
@endsection
