@extends('layouts.admin')

@section('title', 'Jadwal Antar-Jemput')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <nav aria-label="Breadcrumb" class="mb-2">
                <ol class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 dark:text-slate-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Admin</a></li>
                    <li><span class="mx-1 text-slate-300 dark:text-slate-600">/</span></li>
                    <li class="text-slate-700 dark:text-slate-300" aria-current="page">Antar-Jemput</li>
                </ol>
            </nav>
            <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Antar-Jemput Cucian</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola jadwal penjemputan cucian kotor dan pengantaran cucian bersih pelanggan.</p>
        </div>
    </header>

    {{-- Widget Ringkasan Antar-Jemput --}}
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Penjemputan</p>
            <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Baru Masuk (Butuh Jemput)</p>
            <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['baru'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sedang Diproses</p>
            <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['proses'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 p-4.5">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Selesai (Siap Diantar)</p>
            <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </section>

    {{-- Daftar Penjemputan & Pengantaran --}}
    <section class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700/40 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/40">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Daftar Pengiriman Aktif
            </h2>
            
            <form method="GET" action="{{ route('admin.shuttles.index') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2" role="search">
                <input type="text" name="search" placeholder="Cari invoice / nama..." value="{{ request('search') }}"
                       class="w-full sm:w-52 px-3.5 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-xs outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                
                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-xs bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300">
                    <option value="">Semua Status</option>
                    @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                
                @if(request('search') || request('status'))
                <a href="{{ route('admin.shuttles.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-900/50">
                    <tr class="whitespace-nowrap">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alamat Kirim</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Telepon / WA</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Perbarui Status</th>
                        <th class="px-6 py-4 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Navigasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors" id="trx-{{ $trx->id }}"
                        x-data="{ currentStatus: '{{ $trx->status }}', loading: false }">
                        <td class="px-6 py-4 whitespace-nowrap font-extrabold text-brand text-sm">{{ $trx->invoice_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900 dark:text-white">{{ $trx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 max-w-xs truncate" title="{{ $trx->address ?? 'Bawa sendiri' }}">
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $trx->address ?? 'Ambil sendiri di outlet' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $trx->phone ?? $trx->user->phone ?? '-' }}</span>
                            @php
                                $phoneNum = $trx->phone ?? $trx->user->phone;
                                if ($phoneNum) {
                                    // Bersihkan nomor untuk link whatsapp
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNum);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                }
                            @endphp
                            @if(isset($cleanPhone))
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($trx->user->name ?? '') }},%20kami%20dari%20Rumah%20Laundry%20ingin%20mengonfirmasi%20jadwal%20penjemputan/pengantaran%20cucian%20Anda."
                               target="_blank" class="ml-2 px-2 py-0.5 bg-emerald-500 text-white rounded text-[10px] font-bold hover:bg-emerald-600 transition-colors inline-flex items-center gap-1">
                                WhatsApp
                            </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold capitalize inline-flex border"
                                  :class="{
                                      'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50': currentStatus === 'baru',
                                      'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-800/50': ['cuci','kering','setrika'].includes(currentStatus),
                                      'bg-emerald-50 text-emerald-900 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/80': ['selesai','diambil'].includes(currentStatus)
                                  }"
                                  x-text="currentStatus">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <select x-model="currentStatus"
                                    @change="updateStatus({{ $trx->id }}, $event.target.value, $el)"
                                    :disabled="loading"
                                    class="py-1 px-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded text-[11px] font-bold focus:ring-1 focus:ring-brand outline-none transition-colors text-slate-700 dark:text-slate-300 cursor-pointer">
                                @foreach(['baru','cuci','kering','setrika','selesai','diambil'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($trx->address)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($trx->address) }}" target="_blank"
                               class="text-xs font-bold text-blue-500 hover:text-blue-700 inline-flex items-center gap-1">
                                📍 Buka Peta
                            </a>
                            @else
                            <span class="text-xs text-slate-400">Outlet</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada pesanan antar-jemput hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </section>
</div>
@endsection

@section('scripts')
<script>
    async function updateStatus(id, newStatus, selectEl) {
        const row = document.getElementById(`trx-${id}`);
        const comp = row?._x_dataStack?.[0];
        if (comp) comp.loading = true;

        try {
            const res = await fetch(`/transactions/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (res.ok) {
                if (row) {
                    row.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20');
                    setTimeout(() => row.classList.remove('bg-emerald-50', 'dark:bg-emerald-900/20'), 1500);
                }
            } else {
                alert('Gagal memperbarui status');
            }
        } catch (e) {
            alert('Kesalahan jaringan');
        } finally {
            if (comp) comp.loading = false;
        }
    }
</script>
@endsection
