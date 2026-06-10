@extends('layouts.admin')

@section('title', 'Akun Pembayaran')

@section('content')
<div x-data="{
    showAddAccount: false,
    showEditAccount: false,
    showDeleteAccount: false,
    editAccount: { id:'', type:'bank', provider_name:'', provider_code:'', account_number:'', account_name:'', is_active: true },
    deleteAccount: { id:'', provider_name:'' },
    
    openEditAccount(acc) {
        this.editAccount = { 
            id: acc.id,
            type: acc.type,
            provider_name: acc.provider_name,
            provider_code: acc.provider_code,
            account_number: acc.account_number,
            account_name: acc.account_name,
            is_active: acc.is_active === '1' || acc.is_active === 1 || acc.is_active === true
        };
        this.showEditAccount = true;
    },
    openDeleteAccount(id, name) {
        this.deleteAccount = { id, provider_name: name };
        this.showDeleteAccount = true;
    }
}" @keydown.escape.window="showAddAccount=showEditAccount=showDeleteAccount=false" x-cloak>

    {{-- Modal: Tambah Akun --}}
    <div x-show="showAddAccount" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showAddAccount = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-slate-900 dark:bg-slate-950 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Tambah Akun Pembayaran</p>
                <button @click="showAddAccount = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.payment-accounts.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Tipe Akun *</label>
                    <select name="type" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                        <option value="bank">🏦 Transfer Bank</option>
                        <option value="ewallet">📱 Dompet Digital (E-Wallet)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Provider *</label>
                    <input type="text" name="provider_name" required placeholder="Contoh: BCA, GoPay, Mandiri" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Kode Provider *</label>
                    <input type="text" name="provider_code" required placeholder="Contoh: bca, gopay, mandiri (huruf kecil)" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                    <p class="text-[10px] text-slate-400 mt-1">Harus unik & berupa huruf kecil tanpa spasi.</p>
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nomor Rekening / HP *</label>
                    <input type="text" name="account_number" required placeholder="Contoh: 1234567890 atau 0812345678" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Atas Nama Pemilik *</label>
                    <input type="text" name="account_name" required placeholder="Contoh: Rumah Laundry Tasikmalaya" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-brand outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="is_active_add" value="1" checked class="w-4.5 h-4.5 text-brand border-slate-300 rounded focus:ring-brand">
                    <label for="is_active_add" class="text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">Aktifkan akun ini segera</label>
                </div>

                <button type="submit" class="w-full py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md focus:ring-2 focus:ring-brand outline-none mt-2">
                    Simpan Akun
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Akun --}}
    <div x-show="showEditAccount" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showEditAccount = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md z-10 overflow-hidden border dark:border-slate-700">
            <div class="px-6 py-4 bg-amber-500 text-white flex justify-between items-center">
                <p class="font-bold text-sm uppercase tracking-wider">Edit Akun Pembayaran</p>
                <button @click="showEditAccount = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="`/admin/payment-accounts/${editAccount.id}`" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Tipe Akun *</label>
                    <select name="type" required x-model="editAccount.type" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                        <option value="bank">🏦 Transfer Bank</option>
                        <option value="ewallet">📱 Dompet Digital (E-Wallet)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nama Provider *</label>
                    <input type="text" name="provider_name" required x-model="editAccount.provider_name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Kode Provider *</label>
                    <input type="text" name="provider_code" required x-model="editAccount.provider_code" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Nomor Rekening / HP *</label>
                    <input type="text" name="account_number" required x-model="editAccount.account_number" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Atas Nama Pemilik *</label>
                    <input type="text" name="account_name" required x-model="editAccount.account_name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-all">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="is_active_edit" value="1" x-model="editAccount.is_active" class="w-4.5 h-4.5 text-brand border-slate-300 rounded focus:ring-brand">
                    <label for="is_active_edit" class="text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">Aktifkan akun ini</label>
                </div>

                <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all shadow-md focus:ring-2 focus:ring-amber-500 outline-none mt-2">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus Akun --}}
    <div x-show="showDeleteAccount" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm" @click="showDeleteAccount = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-sm z-10 p-8 text-center border dark:border-slate-700">
            <div class="w-16 h-16 bg-red-50 dark:bg-slate-900/50 rounded-xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-red-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Hapus Akun Pembayaran</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">Anda yakin ingin menghapus akun <strong x-text="deleteAccount.provider_name" class="text-slate-900 dark:text-white"></strong>?</p>
            <div class="flex gap-3">
                <button @click="showDeleteAccount = false" class="flex-1 py-3 border border-slate-200 dark:border-slate-600 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">Batal</button>
                <form method="POST" :action="`/admin/payment-accounts/${deleteAccount.id}`" class="flex-1">
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
                <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Akun Pembayaran</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola nomor rekening bank dan e-wallet tujuan transfer pembayaran dari pelanggan.</p>
            </div>
            <button @click="showAddAccount = true;"
                    class="px-6 py-3 bg-brand text-white font-bold rounded-xl hover:bg-brand-dark transition-all shadow-md flex items-center gap-2 transform active:scale-95 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Akun
            </button>
        </div>

        {{-- Stats & Search row --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-6">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Akun</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">{{ $paymentAccounts->total() }}</p>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bank</p>
                    <p class="text-2xl font-black text-blue-600 mt-0.5">{{ \App\Models\PaymentAccount::where('type', 'bank')->count() }}</p>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">E-Wallet</p>
                    <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ \App\Models\PaymentAccount::where('type', 'ewallet')->count() }}</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.payment-accounts.index') }}" class="relative w-full md:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari akun..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand outline-none transition">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </form>
        </div>

        {{-- Table/List view --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4">Tipe</th>
                            <th scope="col" class="px-6 py-4">Provider</th>
                            <th scope="col" class="px-6 py-4">Kode</th>
                            <th scope="col" class="px-6 py-4">No. Rekening / HP</th>
                            <th scope="col" class="px-6 py-4">Atas Nama</th>
                            <th scope="col" class="px-6 py-4 text-center">Status</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($paymentAccounts as $acc)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4.5 font-bold whitespace-nowrap">
                                @if($acc->type === 'bank')
                                <span class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-100 dark:border-blue-900/60 inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-6 9 6m-1.5 12V10.5A1.5 1.5 0 0018 9v-.75H6V9a1.5 1.5 0 00-1.5 1.5V21M3 21h18"/></svg> Bank
                                </span>
                                @else
                                <span class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/60 inline-flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> E-Wallet
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-8 rounded-md overflow-hidden flex-shrink-0 flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-700">
                                        @include('partials._payment_logo', ['code' => $acc->provider_code, 'size' => 'sm'])
                                    </div>
                                    <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $acc->provider_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4.5 text-xs text-slate-400 whitespace-nowrap font-mono">
                                {{ $acc->provider_code }}
                            </td>
                            <td class="px-6 py-4.5 font-mono text-slate-900 dark:text-slate-100 whitespace-nowrap">
                                {{ $acc->account_number }}
                            </td>
                            <td class="px-6 py-4.5 font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                {{ $acc->account_name }}
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-center">
                                @if($acc->is_active)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">Aktif</span>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-center">
                                <div class="inline-flex gap-2">
                                    <button @click="openEditAccount($el.dataset)"
                                            data-id="{{ $acc->id }}"
                                            data-type="{{ $acc->type }}"
                                            data-provider_name="{{ $acc->provider_name }}"
                                            data-provider_code="{{ $acc->provider_code }}"
                                            data-account_number="{{ $acc->account_number }}"
                                            data-account_name="{{ $acc->account_name }}"
                                            data-is_active="{{ $acc->is_active ? 1 : 0 }}"
                                            title="Edit Akun"
                                            class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-amber-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-amber-600 dark:hover:text-white transition flex items-center justify-center focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteAccount({{ $acc->id }}, '{{ addslashes($acc->provider_name) }}')"
                                            title="Hapus Akun"
                                            class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-500 hover:text-white dark:bg-slate-700 text-slate-500 dark:text-slate-400 dark:hover:bg-red-600 dark:hover:text-white transition flex items-center justify-center focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                <div class="text-4xl mb-3">💳</div>
                                <p class="font-bold">Belum ada akun pembayaran</p>
                                <p class="text-xs mt-1">Silakan tambahkan rekening bank atau e-wallet untuk menerima pembayaran non-tunai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($paymentAccounts->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20">
                {{ $paymentAccounts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
