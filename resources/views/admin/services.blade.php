@extends('layouts.admin')

@section('title', 'Data Layanan')

@section('content')
<div x-data="{
    showAddService: false,
    showEditService: false,
    showDeleteService: false,
    editService: { id:'', name:'', price:'', unit:'', category_id:'', description:'', image:'' },
    deleteService: { id:'', name:'' },
    addPreview: null,
    editPreview: null,

    openEditService(s) { 
        this.editService = {...s}; 
        this.editPreview = null;
        this.showEditService = true; 
    },
    openDeleteService(id, name) { 
        this.deleteService = {id, name}; 
        this.showDeleteService = true; 
    }
}" @keydown.escape.window="showAddService=showEditService=showDeleteService=false" x-cloak>

    {{-- Modal: Tambah Layanan --}}
    <div x-show="showAddService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showAddService = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-slate-900 dark:bg-slate-950 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Tambah Layanan Baru</p>
                <button @click="showAddService = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="p-5 space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Layanan *</label>
                    <input type="text" name="name" required placeholder="Cuci Kiloan Ekspres" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Kategori *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all cursor-pointer">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" placeholder="6000" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Satuan *</label>
                        <select name="unit" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all cursor-pointer">
                            <option value="kg">kg</option>
                            <option value="pcs">pcs</option>
                            <option value="pasang">pasang</option>
                            <option value="item">item</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Deskripsi</label>
                    <textarea name="description" placeholder="Keterangan singkat layanan..." rows="2" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Gambar Layanan</label>
                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900 p-3 rounded-xl border border-slate-200 dark:border-slate-700">
                        <div class="w-14 h-14 rounded-lg bg-slate-200 dark:bg-slate-800 border flex items-center justify-center overflow-hidden flex-shrink-0">
                            <template x-if="addPreview">
                                <img :src="addPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!addPreview">
                                <span class="text-xl text-slate-400">🖼️</span>
                            </template>
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-brand dark:file:bg-slate-700 dark:file:text-blue-400 hover:file:bg-blue-100 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { const r = new FileReader(); r.onload = (e) => { addPreview = e.target.result; }; r.readAsDataURL(file); }">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md focus:ring-2 focus:ring-brand outline-none mt-2">
                    Tambah Layanan
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Layanan --}}
    <div x-show="showEditService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showEditService = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-amber-500 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Edit Layanan</p>
                <button @click="showEditService = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="`/admin/services/${editService.id}`" enctype="multipart/form-data" class="p-5 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Layanan *</label>
                    <input type="text" name="name" required x-model="editService.name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Kategori *</label>
                    <select name="category_id" required x-model="editService.category_id" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all cursor-pointer">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" x-model="editService.price" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Satuan *</label>
                        <select name="unit" required x-model="editService.unit" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all cursor-pointer">
                            <option value="kg">kg</option>
                            <option value="pcs">pcs</option>
                            <option value="pasang">pasang</option>
                            <option value="item">item</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Deskripsi</label>
                    <textarea name="description" x-model="editService.description" placeholder="Keterangan singkat layanan..." rows="2" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Gambar Layanan</label>
                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900 p-3 rounded-xl border border-slate-200 dark:border-slate-700">
                        <div class="w-14 h-14 rounded-lg bg-slate-200 dark:bg-slate-800 border flex items-center justify-center overflow-hidden flex-shrink-0">
                            <template x-if="editPreview">
                                <img :src="editPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!editPreview && editService.image">
                                <img :src="'/images/services/' + editService.image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!editPreview && !editService.image">
                                <span class="text-xl text-slate-400">🖼️</span>
                            </template>
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-brand dark:file:bg-slate-700 dark:file:text-blue-400 hover:file:bg-blue-100 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { const r = new FileReader(); r.onload = (e) => { editPreview = e.target.result; }; r.readAsDataURL(file); }">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all shadow-md focus:ring-2 focus:ring-amber-500 outline-none mt-2">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus Layanan --}}
    <div x-show="showDeleteService" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showDeleteService = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-sm z-10 p-8 text-center border dark:border-slate-700">
            <div class="w-16 h-16 bg-red-50 dark:bg-slate-900/50 rounded-lg flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-red-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Hapus Layanan</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">Anda yakin ingin menghapus layanan <strong x-text="deleteService.name" class="text-slate-900 dark:text-white"></strong>?</p>
            <div class="flex gap-3">
                <button @click="showDeleteService = false" class="flex-1 py-3 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">Batal</button>
                <form method="POST" :action="`/admin/services/${deleteService.id}`" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition shadow-lg shadow-red-100 dark:shadow-none">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Data Layanan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola jenis jasa, harga, satuan, dan gambar produk laundry.</p>
            </div>
            <button @click="showAddService = true; addPreview = null;"
                    class="px-6 py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md flex items-center gap-2 transform active:scale-95 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Layanan Baru
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @forelse($services as $s)
            @php
                $catColor = $s->categoryRelation->accent ?? 'slate';
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 p-4 flex gap-4 hover:shadow-md transition-all duration-300 group relative">
                
                {{-- Left: Image or Icon fallback (Compact w-20 h-20) --}}
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900 flex-shrink-0 flex items-center justify-center border border-slate-100 dark:border-slate-700 shadow-inner">
                    @if($s->image)
                        <img src="{{ asset('images/services/' . $s->image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl select-none">{{ $s->icon }}</span>
                    @endif
                </div>

                {{-- Center/Right: Details --}}
                <div class="flex-1 min-w-0 pr-16 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-extrabold text-slate-800 dark:text-white text-sm md:text-base truncate">{{ $s->name }}</h3>
                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 bg-slate-50 dark:bg-slate-900/50 px-2 py-0.5 rounded border border-slate-100 dark:border-slate-800">
                                {{ $s->categoryRelation->icon ?? '' }} {{ $s->categoryRelation->name ?? 'Umum' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 line-clamp-1">
                            {{ $s->description ?? 'Tidak ada deskripsi layanan.' }}
                        </p>
                    </div>
                    
                    <div class="text-sm font-black text-brand dark:text-blue-400">
                        Rp {{ number_format($s->price, 0, ',', '.') }} <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">/{{ $s->unit }}</span>
                    </div>
                </div>

                {{-- Action Buttons top right --}}
                <div class="absolute top-4 right-4 flex gap-1">
                    <button @click="openEditService($el.dataset)"
                            data-id="{{ $s->id }}"
                            data-name="{{ $s->name }}"
                            data-price="{{ $s->price }}"
                            data-unit="{{ $s->unit }}"
                            data-category_id="{{ $s->category_id }}"
                            data-description="{{ $s->description ?? '' }}"
                            data-image="{{ $s->image ?? '' }}"
                            class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-amber-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-amber-600 dark:hover:text-white rounded-lg transition focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="openDeleteService({{ $s->id }}, '{{ addslashes($s->name) }}')"
                            class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-red-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-red-600 dark:hover:text-white rounded-lg transition focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="text-4xl mb-3">🏷️</div>
                <p class="font-bold text-slate-800 dark:text-white text-sm">Belum ada layanan tersedia</p>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan layanan pertama Anda.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
