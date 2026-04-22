@extends('layouts.user')
@section('title', 'Order Laundry')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Buat Pesanan</h1>
    <p class="text-sm text-slate-400 mt-0.5">Pilih layanan dan isi jumlah cucian Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ══ FORM ORDER ══ --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Form Pemesanan</h2>
                    <p class="text-[11px] text-slate-400">Isi detail pesanan laundry Anda</p>
                </div>
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="p-6">
                @csrf

                {{-- Order items --}}
                <div id="orderItems" class="space-y-3 mb-4">
                    <div class="order-item bg-slate-50 rounded-xl border border-slate-200 p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jenis Layanan</label>
                                <select name="items[0][service_id]" required onchange="recalc()"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-brand-ring transition">
                                    <option value="">— Pilih Layanan —</option>
                                    @foreach($services as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-unit="{{ $s->unit }}"
                                        {{ request('service_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} — Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jumlah <span class="item-unit text-slate-400 font-normal">(Kg/Pcs)</span></label>
                                <input type="number" name="items[0][quantity]" step="0.1" min="0.1" placeholder="0.0" required oninput="recalc()"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-brand-ring transition">
                            </div>
                        </div>
                        <p class="text-[11px] text-brand font-medium mt-2 item-subtotal"></p>
                    </div>
                </div>

                <button type="button" onclick="addItem()"
                        class="w-full py-2.5 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand hover:text-brand text-slate-400 text-sm font-medium transition flex items-center justify-center gap-2 mb-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Layanan
                </button>

                {{-- Estimasi total --}}
                <div class="bg-brand-light border border-brand/20 rounded-xl px-5 py-4 mb-4">
                    <p class="text-xs font-semibold text-brand/70 uppercase tracking-wide mb-1">Estimasi Total</p>
                    <p id="grandTotal" class="text-2xl font-bold text-brand">Rp 0</p>
                    <p class="text-[11px] text-brand/60 mt-0.5">*Tagihan final sesuai timbangan di kasir</p>
                </div>

                {{-- Catatan --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Catatan Khusus <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea name="note" rows="2" placeholder="Contoh: pisahkan baju berwarna, dll."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 outline-none focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand-ring transition resize-none"></textarea>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-brand text-white font-semibold py-3.5 rounded-xl hover:bg-brand-dark transition shadow-lg shadow-blue-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Konfirmasi & Kirim Pesanan
                </button>
            </form>
        </div>
    </div>

    {{-- ══ SIDEBAR ══ --}}
    <div class="space-y-4">

        {{-- Alur Pemesanan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Alur Pemesanan
            </h3>
            <ol class="space-y-3">
                @foreach([
                    ['Pilih layanan & isi jumlah', 'brand'],
                    ['Kirim pesanan & catat nomor nota', 'brand'],
                    ['Antar cucian ke gerai / tunggu jemput', 'brand'],
                    ['Bayar saat ambil cucian di kasir', 'brand'],
                ] as $i => $step)
                <li class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-brand-light text-brand text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i+1 }}</div>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $step[0] }}</p>
                </li>
                @endforeach
            </ol>
        </div>

        {{-- Alamat jemput --}}
        @if($user->address)
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Alamat Penjemputan
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ $user->address }}</p>
        </div>
        @endif

        {{-- Garansi --}}
        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-5">
            <h3 class="text-xs font-bold text-emerald-700 mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Garansi Kami
            </h3>
            <ul class="space-y-1.5 text-xs text-emerald-700">
                @foreach(['Pakaian diproses higienis & aman','Produk luntur dipisah otomatis','Notifikasi ketika cucian selesai','Kepuasan pelanggan terjamin'] as $g)
                <li class="flex items-center gap-1.5"><span class="w-1 h-1 bg-emerald-500 rounded-full flex-shrink-0"></span>{{ $g }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@php $servicesJson = $services->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'price'=>$s->price,'unit'=>$s->unit])->values(); @endphp
<script>
const services = @json($servicesJson);
let itemCount = 1;

function addItem() {
    const idx = itemCount++;
    const opts = services.map(s =>
        `<option value="${s.id}" data-price="${s.price}" data-unit="${s.unit}">${s.name} — Rp ${s.price.toLocaleString('id-ID')}/${s.unit}</option>`
    ).join('');
    const div = document.createElement('div');
    div.className = 'order-item bg-slate-50 rounded-xl border border-slate-200 p-4 relative';
    div.innerHTML = `
        <button type="button" onclick="this.closest('.order-item').remove();recalc()"
                class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jenis Layanan</label>
                <select name="items[${idx}][service_id]" required onchange="recalc()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-blue-100 transition">
                    <option value="">— Pilih Layanan —</option>${opts}
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jumlah</label>
                <input type="number" name="items[${idx}][quantity]" step="0.1" min="0.1" placeholder="0.0" required oninput="recalc()"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-blue-100 transition">
            </div>
        </div>
        <p class="text-[11px] text-brand font-medium mt-2 item-subtotal"></p>`;
    document.getElementById('orderItems').appendChild(div);
}

function recalc() {
    let grand = 0;
    document.querySelectorAll('.order-item').forEach(item => {
        const sel = item.querySelector('select');
        const qty = parseFloat(item.querySelector('input[type=number]')?.value || 0);
        const opt = sel?.options[sel.selectedIndex];
        const price = parseFloat(opt?.dataset.price || 0);
        const unit  = opt?.dataset.unit || '';
        const sub   = price * qty;
        grand += sub;
        const subEl = item.querySelector('.item-subtotal');
        if (subEl) subEl.textContent = sub > 0 ? `Subtotal: Rp ${sub.toLocaleString('id-ID')} (${qty} ${unit})` : '';
        const unitEl = item.querySelector('.item-unit');
        if (unitEl && unit) unitEl.textContent = `(${unit})`;
    });
    document.getElementById('grandTotal').textContent = `Rp ${grand.toLocaleString('id-ID')}`;
}
document.getElementById('orderItems').addEventListener('change', recalc);
document.getElementById('orderItems').addEventListener('input', recalc);
document.addEventListener('DOMContentLoaded', recalc);
</script>
@endsection
