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

                {{-- Metode Pembayaran --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Metode Pembayaran
                    </label>

                    {{-- Cash --}}
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">💵 Tunai</p>
                    <div class="grid grid-cols-1 gap-2 mb-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" class="peer sr-only" checked>
                            <div class="flex items-center gap-3 px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                <span class="text-xl">💵</span>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-700 peer-checked:text-emerald-700">Cash / Tunai</p>
                                    <p class="text-[11px] text-slate-400">Bayar langsung di kasir saat ambil cucian</p>
                                </div>
                                <svg class="w-5 h-5 text-emerald-500 opacity-0 peer-checked:opacity-100 transition flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                    </div>

                    {{-- Transfer Bank --}}
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">🏦 Transfer Bank</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-3">
                        @foreach([
                            ['val'=>'bca',     'label'=>'BCA',     'color'=>'blue'],
                            ['val'=>'bri',     'label'=>'BRI',     'color'=>'sky'],
                            ['val'=>'mandiri', 'label'=>'Mandiri', 'color'=>'amber'],
                            ['val'=>'bsi',     'label'=>'BSI',     'color'=>'teal'],
                            ['val'=>'bni',     'label'=>'BNI',     'color'=>'orange'],
                        ] as $bank)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $bank['val'] }}" class="peer sr-only user-pay-method">
                            <div class="flex items-center justify-center gap-2 px-3 py-2.5 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-500 bg-white peer-checked:border-brand peer-checked:bg-brand-light peer-checked:text-brand transition-all hover:border-slate-300">
                                🏦 {{ $bank['label'] }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- E-Wallet --}}
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">📱 Dompet Digital (E-Wallet)</p>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach([
                            ['val'=>'gopay',     'label'=>'GoPay',      'emoji'=>'🟢'],
                            ['val'=>'ovo',       'label'=>'OVO',        'emoji'=>'🟣'],
                            ['val'=>'dana',      'label'=>'DANA',       'emoji'=>'🔵'],
                            ['val'=>'shopeepay', 'label'=>'ShopeePay',  'emoji'=>'🟠'],
                        ] as $ew)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $ew['val'] }}" class="peer sr-only user-pay-method">
                            <div class="flex items-center gap-2.5 px-3.5 py-2.5 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-500 bg-white peer-checked:border-brand peer-checked:bg-brand-light peer-checked:text-brand transition-all hover:border-slate-300">
                                <span>{{ $ew['emoji'] }}</span> {{ $ew['label'] }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Bank/E-wallet info box --}}
                    <div id="userPayInfo" class="hidden p-3.5 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-[11px] font-bold text-blue-700 mb-1.5">📋 Informasi Rekening / Nomor Tujuan</p>
                        <div id="userPayDetail" class="text-xs text-blue-800 space-y-0.5 font-medium"></div>
                        <p class="text-[10px] text-blue-500 mt-2">Kirim bukti transfer setelah melakukan pembayaran.</p>
                    </div>
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

// ── Payment method info (user form) ──────────────────────────────────────────
const userBankAccounts = {
    cash:      null,
    bca:       'BCA — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>1234567890</strong>',
    bri:       'BRI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>0987654321</strong>',
    mandiri:   'Mandiri — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>1122334455</strong>',
    bsi:       'BSI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>7081234567</strong>',
    bni:       'BNI — a/n Rumah Laundry Tasikmalaya<br>No. Rek: <strong>0123456789</strong>',
    gopay:     'GoPay — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
    ovo:       'OVO — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
    dana:      'DANA — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
    shopeepay: 'ShopeePay — <strong>0812-3456-7890</strong><br>a/n Rumah Laundry',
};

document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const val = this.value;
        const infoBox = document.getElementById('userPayInfo');
        const detailEl = document.getElementById('userPayDetail');
        if (userBankAccounts[val]) {
            detailEl.innerHTML = userBankAccounts[val];
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }
    });
});
</script>
@endsection
