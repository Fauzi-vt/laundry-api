@extends('layouts.admin')

@section('title', 'Data Layanan')

@section('content')
<div x-data="{
    showAddService: false,
    showEditService: false,
    showDeleteService: false,
    editService: { id:'', name:'', price:'', unit:'' },
    deleteService: { id:'', name:'' },

    openEditService(s) { this.editService = {...s}; this.showEditService = true; },
    openDeleteService(id, name) { this.deleteService = {id, name}; this.showDeleteService = true; }
}" @keydown.escape.window="showAddService=showEditService=showDeleteService=false" x-cloak>

    {{-- Modal: Tambah Layanan --}}
    <div x-show="showAddService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddService = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-brand text-white flex justify-between items-center">
                <div><p class="text-xs text-blue-200 font-bold uppercase tracking-widest mb-1">Layanan</p><p class="text-lg font-black">Tambah Layanan</p></div>
                <button @click="showAddService = false" class="text-white/70 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('admin.services.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Nama Layanan *</label>
                    <input type="text" name="name" required placeholder="Cuci Kiloan Ekspres" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" placeholder="6000" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Satuan *</label>
                        <select name="unit" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50">
                            <option value="kg">kg</option>
                            <option value="pcs">pcs</option>
                            <option value="pasang">pasang</option>
                            <option value="item">item</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-3.5 bg-brand text-white font-bold rounded-2xl hover:bg-brand-dark transition-all shadow-lg shadow-blue-100">Tambah Layanan</button>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Layanan --}}
    <div x-show="showEditService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditService = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-amber-600 text-white flex justify-between items-center">
                <div><p class="text-xs text-amber-100 font-bold uppercase tracking-widest mb-1">Edit</p><p class="text-lg font-black">Edit Layanan</p></div>
                <button @click="showEditService = false" class="text-white/70 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" :action="`/admin/services/${editService.id}`" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Nama Layanan *</label>
                    <input type="text" name="name" required x-model="editService.name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" x-model="editService.price" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase">Satuan *</label>
                        <select name="unit" required x-model="editService.unit" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                            <option value="kg">kg</option>
                            <option value="pcs">pcs</option>
                            <option value="pasang">pasang</option>
                            <option value="item">item</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-3.5 bg-amber-500 text-white font-bold rounded-2xl hover:bg-amber-600 transition-all shadow-lg shadow-amber-100">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus Layanan --}}
    <div x-show="showDeleteService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteService = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10 p-8 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Hapus Layanan</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">Anda yakin ingin menghapus layanan <strong x-text="deleteService.name" class="text-slate-900"></strong>?</p>
            <div class="flex gap-3">
                <button @click="showDeleteService = false" class="flex-1 py-3 border border-slate-200 rounded-2xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition">Batal</button>
                <form method="POST" :action="`/admin/services/${deleteService.id}`" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 transition shadow-lg shadow-red-100">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Data Layanan</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola jenis jasa, harga, dan satuan layanan laundry.</p>
            </div>
            <button @click="showAddService = true"
                    class="px-6 py-3 bg-brand text-white font-bold rounded-2xl hover:bg-brand-dark transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Layanan Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $s)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        @if(str_contains(strtolower($s->name),'sepatu')) 👟
                        @elseif(str_contains(strtolower($s->name),'selimut')||str_contains(strtolower($s->name),'bedcover')) 🛏️
                        @elseif(str_contains(strtolower($s->name),'kilat')) ⚡
                        @else 👕
                        @endif
                    </div>
                    <div class="flex gap-1">
                        <button @click="openEditService({ id: {{ $s->id }}, name: '{{ addslashes($s->name) }}', price: {{ $s->price }}, unit: '{{ $s->unit }}' })"
                                class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button @click="openDeleteService({{ $s->id }}, '{{ addslashes($s->name) }}')"
                                class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 leading-tight">{{ $s->name }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $s->category ?? 'Layanan Laundry' }}</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-400">Harga /{{ $s->unit }}</span>
                    <span class="text-lg font-black text-brand">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                <div class="text-5xl mb-4">🏷️</div>
                <p class="font-bold text-slate-800">Belum ada layanan tersedia</p>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan layanan pertama Anda.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
