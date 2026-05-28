@extends('layouts.admin')

@section('title', 'Data Kategori')

@section('content')
<div x-data="{
    showAddCategory: false,
    showEditCategory: false,
    showDeleteCategory: false,
    editCategory: { id:'', name:'', description:'', icon:'', accent_color:'' },
    deleteCategory: { id:'', name:'' },
    
    // List of predefined accent colors
    colors: [
        { name: 'blue', label: 'Biru', bg: 'bg-blue-500', text: 'text-blue-500' },
        { name: 'violet', label: 'Ungu', bg: 'bg-violet-500', text: 'text-violet-500' },
        { name: 'orange', label: 'Oranye', bg: 'bg-orange-500', text: 'text-orange-500' },
        { name: 'emerald', label: 'Hijau', bg: 'bg-emerald-500', text: 'text-emerald-500' },
        { name: 'rose', label: 'Merah Muda', bg: 'bg-rose-500', text: 'text-rose-500' },
        { name: 'amber', label: 'Kuning', bg: 'bg-amber-500', text: 'text-amber-500' },
        { name: 'indigo', label: 'Nila', bg: 'bg-indigo-500', text: 'text-indigo-500' },
        { name: 'slate', label: 'Abu-abu', bg: 'bg-slate-500', text: 'text-slate-500' }
    ],

    // Common laundry emojis
    emojis: ['👕', '👔', '🛏️', '👟', '👜', '🧺', '🧼', '⚡', '🪄', '🧣', '🧸', '🏠'],

    openEditCategory(cat) {
        this.editCategory = { ...cat };
        this.showEditCategory = true;
    },
    openDeleteCategory(id, name) {
        this.deleteCategory = { id, name };
        this.showDeleteCategory = true;
    }
}" @keydown.escape.window="showAddCategory=showEditCategory=showDeleteCategory=false" x-cloak>

    {{-- Modal: Tambah Kategori --}}
    <div x-show="showAddCategory" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showAddCategory = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-slate-900 dark:bg-slate-950 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Tambah Kategori Baru</p>
                <button @click="showAddCategory = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Helm & Aksesoris" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Deskripsi</label>
                    <textarea name="description" placeholder="Deskripsi singkat jenis kategori ini..." rows="2" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all"></textarea>
                </div>

                {{-- Emoji Selector --}}
                <div x-data="{ selectedEmoji: '👕' }">
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Ikon Kategori (Emoji) *</label>
                    <input type="hidden" name="icon" x-model="selectedEmoji">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-2xl border dark:border-slate-700 shadow-inner flex-shrink-0" x-text="selectedEmoji"></div>
                        <div class="flex flex-wrap gap-1.5 p-2 bg-slate-50 dark:bg-slate-900 rounded-xl border dark:border-slate-700 max-h-24 overflow-y-auto">
                            <template x-for="em in emojis">
                                <button type="button" @click="selectedEmoji = em" 
                                        :class="selectedEmoji === em ? 'bg-brand/10 dark:bg-brand/20 border-brand' : 'border-transparent hover:bg-slate-200 dark:hover:bg-slate-800'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-lg border transition">
                                    <span x-text="em"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Color Selector --}}
                <div x-data="{ selectedColor: 'blue' }">
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Warna Aksen *</label>
                    <input type="hidden" name="accent_color" x-model="selectedColor">
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="col in colors">
                            <button type="button" @click="selectedColor = col.name"
                                    :class="selectedColor === col.name ? 'ring-2 ring-brand ring-offset-2 dark:ring-offset-slate-800 scale-105 font-bold' : 'opacity-85'"
                                    class="py-2 px-1 rounded-xl text-[10px] text-white flex flex-col items-center justify-center gap-1 transition"
                                    :class="col.bg">
                                <span class="w-3.5 h-3.5 rounded-full bg-white/20"></span>
                                <span x-text="col.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md focus:ring-2 focus:ring-brand outline-none mt-2">
                    Tambah Kategori
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Kategori --}}
    <div x-show="showEditCategory" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showEditCategory = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-amber-500 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Edit Kategori</p>
                <button @click="showEditCategory = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="`/admin/categories/${editCategory.id}`" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Kategori *</label>
                    <input type="text" name="name" required x-model="editCategory.name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Deskripsi</label>
                    <textarea name="description" x-model="editCategory.description" placeholder="Deskripsi singkat jenis kategori ini..." rows="2" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all"></textarea>
                </div>

                {{-- Emoji Selector --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Ikon Kategori (Emoji) *</label>
                    <input type="hidden" name="icon" x-model="editCategory.icon">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-2xl border dark:border-slate-700 shadow-inner flex-shrink-0" x-text="editCategory.icon"></div>
                        <div class="flex flex-wrap gap-1.5 p-2 bg-slate-50 dark:bg-slate-900 rounded-xl border dark:border-slate-700 max-h-24 overflow-y-auto">
                            <template x-for="em in emojis">
                                <button type="button" @click="editCategory.icon = em" 
                                        :class="editCategory.icon === em ? 'bg-amber-500/10 border-amber-500' : 'border-transparent hover:bg-slate-200 dark:hover:bg-slate-800'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-lg border transition">
                                    <span x-text="em"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Color Selector --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Warna Aksen *</label>
                    <input type="hidden" name="accent_color" x-model="editCategory.accent_color">
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="col in colors">
                            <button type="button" @click="editCategory.accent_color = col.name"
                                    :class="editCategory.accent_color === col.name ? 'ring-2 ring-amber-500 ring-offset-2 dark:ring-offset-slate-800 scale-105 font-bold' : 'opacity-85'"
                                    class="py-2 px-1 rounded-xl text-[10px] text-white flex flex-col items-center justify-center gap-1 transition"
                                    :class="col.bg">
                                <span class="w-3.5 h-3.5 rounded-full bg-white/20"></span>
                                <span x-text="col.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all shadow-md focus:ring-2 focus:ring-amber-500 outline-none mt-2">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus Kategori --}}
    <div x-show="showDeleteCategory" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showDeleteCategory = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-sm z-10 p-8 text-center border dark:border-slate-700">
            <div class="w-16 h-16 bg-red-50 dark:bg-slate-900/50 rounded-xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-red-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Hapus Kategori</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">Anda yakin ingin menghapus kategori <strong x-text="deleteCategory.name" class="text-slate-900 dark:text-white"></strong>?</p>
            <div class="flex gap-3">
                <button @click="showDeleteCategory = false" class="flex-1 py-3 border border-slate-200 dark:border-slate-600 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">Batal</button>
                <form method="POST" :action="`/admin/categories/${deleteCategory.id}`" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 transition shadow-lg shadow-red-100 dark:shadow-none">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MAIN UI PAGE --}}
    <div class="space-y-6">
        
        {{-- Header Section --}}
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Kategori Layanan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola kategori pencucian, deskripsi, ikon kustom, dan penataan visual.</p>
            </div>
            <button @click="showAddCategory = true;"
                    class="px-6 py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md flex items-center gap-2 transform active:scale-95 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Kategori Baru
            </button>
        </div>

        {{-- Stats & Search row --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-6">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Kategori</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">{{ $categories->total() }}</p>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider font-sans">Total Layanan</p>
                    <p class="text-2xl font-black text-brand dark:text-blue-400 mt-0.5">{{ \App\Models\Service::count() }}</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.categories.index') }}" class="relative w-full md:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand outline-none transition">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </form>
        </div>

        {{-- Grid categories list --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($categories as $cat)
            @php
                $bgClass = $cat->bg_class;
                $textClass = $cat->text_class;
                $borderClass = $cat->border_class;
            @endphp
            <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative group">
                
                <div>
                    <div class="flex items-center justify-between mb-4">
                        {{-- Icon wrapper with category dynamic color --}}
                        <div class="w-14 h-14 rounded-lg {{ $bgClass }} border {{ $borderClass }} flex items-center justify-center text-3xl shadow-sm">
                            {{ $cat->icon ?? '🧺' }}
                        </div>
                        <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full border {{ $borderClass }} {{ $bgClass }} {{ $textClass }}">
                            {{ $cat->services_count }} Layanan
                        </span>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white leading-tight mb-2">{{ $cat->name }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                        {{ $cat->description ?: 'Tidak ada deskripsi untuk kategori ini.' }}
                    </p>
                </div>

                {{-- Action tools --}}
                <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-700/60 pt-4 mt-auto">
                    <span class="text-[9px] font-bold uppercase text-slate-400">Aksen: {{ strtoupper($cat->accent_color ?: 'slate') }}</span>
                    
                    <div class="flex gap-2">
                        <button @click="openEditCategory($el.dataset)"
                                data-id="{{ $cat->id }}"
                                data-name="{{ $cat->name }}"
                                data-description="{{ $cat->description ?? '' }}"
                                data-icon="{{ $cat->icon ?? '🧺' }}"
                                data-accent_color="{{ $cat->accent_color ?? 'slate' }}"
                                class="w-9 h-9 bg-slate-50 hover:bg-amber-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-amber-600 dark:hover:text-white rounded-xl transition flex items-center justify-center focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button @click="openDeleteCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                class="w-9 h-9 bg-slate-50 hover:bg-red-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-red-600 dark:hover:text-white rounded-xl transition flex items-center justify-center focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="text-5xl mb-4">🏷️</div>
                <p class="font-bold text-slate-800 dark:text-white text-sm">Kategori tidak ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan kategori baru atau ubah kata pencarian Anda.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
