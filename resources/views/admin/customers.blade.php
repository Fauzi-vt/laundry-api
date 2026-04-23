@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div x-data="{
    showAddCustomer: false,
    showEditCustomer: false,
    showDeleteCustomer: false,
    showCustomerTrx: false,
    showBulkDelete: false,
    loading: false,
    selectedItems: [],
    
    editCustomer: { id:'', name:'', email:'', phone:'', address:'' },
    deleteCustomer: { id:'', name:'' },
    customerTrx: { customer:{}, transactions:[] },
    customerTrxLoading: false,

    openEditCustomer(c) { this.editCustomer = {...c}; this.showEditCustomer = true; },
    openDeleteCustomer(id, name) { this.deleteCustomer = {id, name}; this.showDeleteCustomer = true; },
    
    async openCustomerTrx(id) {
        this.showCustomerTrx = true;
        this.customerTrxLoading = true;
        this.customerTrx = { customer:{}, transactions:[] };
        try {
            const r = await fetch(`/admin/customers/${id}/trx`, { headers:{ 'Accept':'application/json' } });
            const d = await r.json();
            this.customerTrx = d;
        } finally {
            this.customerTrxLoading = false;
        }
    },

    toggleSelectAll(e) {
        if (e.target.checked) {
            this.selectedItems = Array.from(document.querySelectorAll('.row-checkbox')).map(cb => cb.value);
        } else {
            this.selectedItems = [];
        }
    },

    toggleItem(id) {
        if (this.selectedItems.includes(id)) {
            this.selectedItems = this.selectedItems.filter(item => item !== id);
        } else {
            this.selectedItems.push(id);
        }
    }
}" @keydown.escape.window="showAddCustomer=showEditCustomer=showDeleteCustomer=showCustomerTrx=showBulkDelete=false" x-cloak>

    {{-- ══════════ Stat Cards ══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pelanggan</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $totalCustomers }}</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan Baru (Bulan Ini)</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $newCustomersThisMonth }}</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan Aktif</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $customers->where('transactions_count', '>', 0)->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 2.944a11.955 11.955 0 018.618 3.04M12 2.944V12.5"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Retention Rate</p>
                <p class="text-2xl font-black text-slate-900 mt-0.5">85%</p>
            </div>
        </div>
    </div>

    {{-- ══════════ Header & Actions ══════════ --}}
    <div class="flex flex-wrap items-end justify-between gap-6 mb-6">
        <div class="space-y-1">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Manajemen Pelanggan</h2>
            <p class="text-sm text-slate-500 font-medium">Kelola data, pantau loyalitas, dan ekspor riwayat pelanggan.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white rounded-2xl border border-slate-200 p-1 shadow-sm">
                <button class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
                <div class="w-px h-4 bg-slate-200 mx-1"></div>
                <button class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
            </div>
            <button @click="showAddCustomer = true"
                    class="px-6 py-3 bg-brand text-white font-bold rounded-2xl hover:bg-brand-dark transition-all shadow-lg shadow-blue-100 flex items-center gap-2 transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Pelanggan
            </button>
        </div>
    </div>

    {{-- ══════════ Table & Filters ══════════ --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden relative" :class="loading ? 'opacity-60 pointer-events-none' : ''">
        
        {{-- Skeleton Loader (Overlay) --}}
        <div x-show="loading" class="absolute inset-0 z-50 bg-white/40 flex items-center justify-center backdrop-blur-[1px]">
             <div class="flex gap-2">
                <div class="w-2 h-2 bg-brand rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-brand rounded-full animate-bounce [animation-delay:0.2s]"></div>
                <div class="w-2 h-2 bg-brand rounded-full animate-bounce [animation-delay:0.4s]"></div>
             </div>
        </div>

        {{-- Toolbar: Search, Filters, Bulk Actions --}}
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-1 min-w-[300px]">
                <form method="GET" action="{{ route('admin.customers.index') }}" class="flex-1 flex gap-3" @submit="loading = true">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="cust_search" placeholder="Cari nama, email, atau telepon..." value="{{ request('cust_search') }}"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-brand outline-none transition-all shadow-sm">
                    </div>
                    <select name="status" onchange="this.form.submit(); loading=true"
                            class="px-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 outline-none focus:ring-2 focus:ring-brand shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Bulan Ini</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </form>
            </div>

            {{-- Bulk Actions --}}
            <div x-show="selectedItems.length > 0" x-transition 
                 class="flex items-center gap-3 animate-in fade-in slide-in-from-right-4">
                <span class="text-xs font-bold text-slate-500"><span x-text="selectedItems.length"></span> dipilih</span>
                <button @click="showBulkDelete = true"
                        class="px-4 py-2.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm border border-red-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Massal
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left">
                            <input type="checkbox" @change="toggleSelectAll" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left">
                             <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-brand transition">
                                Pelanggan
                                @if(request('sort') == 'name')
                                    <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                @endif
                             </a>
                        </th>
                        <th class="px-6 py-4 text-left">
                             <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-brand transition">
                                Kontak
                                @if(request('sort') == 'email')
                                    <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                @endif
                             </a>
                        </th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</th>
                        <th class="px-6 py-4 text-center">
                             <a href="{{ request()->fullUrlWithQuery(['sort' => 'transactions_count', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-brand transition">
                                Transaksi
                                @if(request('sort') == 'transactions_count')
                                    <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                @endif
                             </a>
                        </th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($customers as $c)
                    <tr class="group hover:bg-slate-50 transition-colors relative" :class="selectedItems.includes('{{ $c->id }}') ? 'bg-blue-50/50' : ''">
                        <td class="px-8 py-5">
                            <input type="checkbox" value="{{ $c->id }}" x-model="selectedItems"
                                   class="row-checkbox w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer">
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand to-blue-400 text-white flex items-center justify-center font-bold shadow-sm shadow-blue-100 group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <button @click="openCustomerTrx({{ $c->id }})" class="text-left group/btn">
                                    <p class="text-sm font-bold text-slate-900 group-hover/btn:text-brand transition">{{ $c->name }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $c->email }}</p>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @if($c->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->phone) }}" target="_blank" 
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                {{ $c->phone }}
                                <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @else
                            <span class="text-xs text-slate-300 italic">No Phone</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 max-w-xs">
                            <p class="text-xs text-slate-500 leading-relaxed truncate" title="{{ $c->address }}">
                                {{ $c->address ?? '—' }}
                            </p>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full {{ $c->transactions_count > 0 ? 'bg-blue-50 text-brand' : 'bg-slate-50 text-slate-400' }} text-[10px] font-black uppercase">
                                {{ $c->transactions_count }} Order
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditCustomer({ id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', email: '{{ addslashes($c->email) }}', phone: '{{ addslashes($c->phone ?? '') }}', address: '{{ addslashes($c->address ?? '') }}' })"
                                        class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="openDeleteCustomer({{ $c->id }}, '{{ addslashes($c->name) }}')"
                                        class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Data tidak ditemukan</h3>
                                <p class="text-sm text-slate-400">Kami tidak menemukan pelanggan dengan kata kunci "{{ request('cust_search') }}".</p>
                                <a href="{{ route('admin.customers.index') }}" class="mt-6 inline-flex items-center text-brand text-xs font-bold hover:underline">Reset Pencarian</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══════════ Pagination ══════════ --}}
        @if($customers->hasPages())
        <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/10 flex flex-wrap items-center justify-between gap-6">
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">
                Halaman <span class="text-slate-900">{{ $customers->currentPage() }}</span> dari <span class="text-slate-900">{{ $customers->lastPage() }}</span>
                <span class="mx-2">•</span> Total <span class="text-slate-900">{{ $customers->total() }}</span> Pelanggan
            </p>
            <div class="flex items-center gap-2">
                {{ $customers->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════
         MODALS (Tambah, Edit, Hapus, Detail, Bulk)
    ══════════════════════════════════════════════════ --}}

    {{-- Modal: Tambah --}}
    <div x-show="showAddCustomer" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddCustomer = false"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md z-10 overflow-hidden">
            <div class="px-8 py-6 bg-brand text-white flex justify-between items-center">
                <p class="text-xl font-black">Pelanggan Baru</p>
                <button @click="showAddCustomer = false" class="text-white/60 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('admin.customers.store') }}" class="p-8 space-y-5">
                @csrf
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label><input type="text" name="name" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand transition"></div>
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email</label><input type="email" name="email" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand transition"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Telepon</label><input type="text" name="phone" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand transition"></div>
                    <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Password</label><input type="password" name="password" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand transition"></div>
                </div>
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat</label><textarea name="address" rows="2" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand transition resize-none"></textarea></div>
                <button type="submit" class="w-full py-4 bg-brand text-white font-black rounded-2xl shadow-xl shadow-blue-100 hover:bg-brand-dark transition transform active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    {{-- Modal: Edit --}}
    <div x-show="showEditCustomer" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditCustomer = false"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md z-10 overflow-hidden">
            <div class="px-8 py-6 bg-amber-500 text-white flex justify-between items-center">
                <p class="text-xl font-black">Edit Pelanggan</p>
                <button @click="showEditCustomer = false" class="text-white/60 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" :action="`/admin/customers/${editCustomer.id}`" class="p-8 space-y-5">
                @csrf @method('PUT')
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label><input type="text" name="name" required x-model="editCustomer.name" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email</label><input type="email" name="email" required x-model="editCustomer.email" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Telepon</label><input type="text" name="phone" x-model="editCustomer.phone" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat</label><textarea name="address" rows="2" x-model="editCustomer.address" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-amber-500 transition resize-none"></textarea></div>
                <button type="submit" class="w-full py-4 bg-amber-500 text-white font-black rounded-2xl shadow-xl shadow-amber-100 hover:bg-amber-600 transition transform active:scale-95">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus (Konfirmasi) --}}
    <div x-show="showDeleteCustomer" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteCustomer = false"></div>
        <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-sm z-10 p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-3">Hapus Pelanggan?</h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-10">Data <strong x-text="deleteCustomer.name" class="text-slate-900"></strong> dan seluruh riwayat transaksinya akan dihapus secara permanen.</p>
            <div class="flex gap-4">
                <button @click="showDeleteCustomer = false" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition">Batal</button>
                <form method="POST" :action="`/admin/customers/${deleteCustomer.id}`" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-4 bg-red-500 text-white font-black rounded-2xl shadow-xl shadow-red-100 hover:bg-red-600 transition">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Bulk Delete --}}
    <div x-show="showBulkDelete" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showBulkDelete = false"></div>
        <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-sm z-10 p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-3">Hapus Massal?</h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-10">Anda akan menghapus <strong x-text="selectedItems.length" class="text-slate-900"></strong> pelanggan yang dipilih. Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-4">
                <button @click="showBulkDelete = false" class="flex-1 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition">Batal</button>
                <form method="POST" action="{{ route('admin.customers.bulkDestroy') }}" class="flex-1">
                    @csrf @method('DELETE')
                    <template x-for="id in selectedItems" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="w-full py-4 bg-red-500 text-white font-black rounded-2xl shadow-xl shadow-red-100 hover:bg-red-600 transition">Ya, Hapus Semua</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Riwayat Detail --}}
    <div x-show="showCustomerTrx" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCustomerTrx = false"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-8 py-6 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Riwayat Transaksi</p>
                    <p class="text-xl font-black" x-text="customerTrx.customer?.name"></p>
                </div>
                <button @click="showCustomerTrx = false" class="text-white/40 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-8 overflow-y-auto flex-1">
                <div x-show="customerTrxLoading" class="py-20 text-center">
                     <div class="w-12 h-12 border-4 border-brand border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                     <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat riwayat...</p>
                </div>
                <div x-show="!customerTrxLoading && customerTrx.transactions?.length === 0" class="py-20 text-center">
                     <p class="text-sm font-bold text-slate-400">Belum ada riwayat transaksi untuk pelanggan ini.</p>
                </div>
                <div x-show="!customerTrxLoading && customerTrx.transactions?.length > 0" class="space-y-4">
                    <template x-for="t in customerTrx.transactions" :key="t.id">
                        <div class="bg-slate-50 rounded-[2rem] border border-slate-100 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs font-black text-brand tracking-tight" x-text="t.invoice_code"></p>
                                    <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase" x-text="t.created_at"></p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest"
                                      :class="t.status === 'baru' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600'"
                                      x-text="t.status"></span>
                            </div>
                            <div class="space-y-2 mb-4 border-b border-slate-200 pb-4">
                                <template x-for="d in t.details" :key="d.service">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-500" x-text="`${d.service} (${d.qty} ${d.unit})`"></span>
                                        <span class="font-bold text-slate-900" x-text="'Rp ' + Number(d.subtotal).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
                                <span class="text-lg font-black text-slate-900" x-text="'Rp ' + Number(t.total_price).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
