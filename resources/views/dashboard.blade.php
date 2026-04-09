<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rumah Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#1e40af', secondary: '#0ea5e9' }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">L</div>
                        <span class="font-bold text-xl text-slate-900 tracking-tight">Laundry Admin</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-700">Halo, {{ $user->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'monitoring' }">
        
        <!-- Navigation Tabs -->
        <div class="mb-8 border-b border-slate-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="tab = 'monitoring'" :class="{'border-primary text-primary': tab === 'monitoring', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'monitoring'}" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition">
                    Monitoring Cucian
                </button>
                <button @click="tab = 'master'" :class="{'border-primary text-primary': tab === 'master', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'master'}" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition">
                    Data Master
                </button>
                <button @click="tab = 'laporan'" :class="{'border-primary text-primary': tab === 'laporan', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'laporan'}" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition">
                    Laporan Keuangan
                </button>
            </nav>
        </div>

        <!-- Tab 1: Monitoring / Dashboard -->
        <div x-show="tab === 'monitoring'">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500 truncate mb-1">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500 truncate mb-1">Total Transaksi (Selesai)</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $transactions->where('status', 'selesai')->count() + $transactions->where('status', 'diambil')->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500 truncate mb-1">Cucian Diproses</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $transactions->whereIn('status', ['baru', 'cuci', 'kering', 'setrika'])->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-medium text-slate-500 truncate mb-1">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $customers->count() }}</p>
                </div>
            </div>

            <!-- Monitoring Transaksi -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Monitoring Status Cucian</h3>
                    <!-- In a real app, 'Tambah' would pop a modal or go to a form page -->
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50 transition" x-data="{ currentStatus: '{{ $trx->status }}', loading: false }" id="trx-{{ $trx->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $trx->invoice_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $trx->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': currentStatus === 'baru',
                                              'bg-blue-100 text-blue-800': currentStatus === 'cuci' || currentStatus === 'kering' || currentStatus === 'setrika',
                                              'bg-green-100 text-green-800': currentStatus === 'selesai' || currentStatus === 'diambil'
                                          }" x-text="currentStatus">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <select x-model="currentStatus" @change="updateStatus({{ $trx->id }}, $event.target.value)" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        <option value="baru">Baru</option>
                                        <option value="cuci">Cuci</option>
                                        <option value="kering">Kering</option>
                                        <option value="setrika">Setrika</option>
                                        <option value="selesai">Selesai</option>
                                        <option value="diambil">Diambil</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Master Data -->
        <div x-show="tab === 'master'" style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Master Customer -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">Data Pelanggan</h3>
                    </div>
                    <ul class="divide-y divide-slate-200">
                        @forelse($customers as $c)
                        <li class="px-6 py-4 hover:bg-slate-50 transition flex flex-col gap-1">
                            <span class="text-sm font-semibold text-slate-900">{{ $c->name }}</span>
                            <span class="text-xs text-slate-500">{{ $c->phone ?? 'Tidak ada No Hp' }} • {{ $c->address ?? 'Alamat tidak diketahui' }}</span>
                        </li>
                        @empty
                        <li class="px-6 py-8 text-center text-sm text-slate-500">Data pelanggan kosong.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Master Services -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">Data Layanan (Jasa)</h3>
                    </div>
                    <ul class="divide-y divide-slate-200">
                        @forelse($services as $s)
                        <li class="px-6 py-4 hover:bg-slate-50 transition flex justify-between items-center">
                            <div>
                                <span class="block text-sm font-semibold text-slate-900">{{ $s->name }}</span>
                                <span class="block text-xs text-slate-500">Per {{ $s->unit }}</span>
                            </div>
                            <span class="text-sm font-medium text-slate-800">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                        </li>
                        @empty
                        <li class="px-6 py-8 text-center text-sm text-slate-500">Data layanan kosong.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab 3: Laporan Keuangan -->
        <div x-show="tab === 'laporan'" style="display: none;">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Laporan Keuangan Periodik</h3>
                    <div class="text-sm text-slate-500">Rekapitulasi Akumulasi Pendapatan</div>
                </div>
                
                <div class="p-8 text-center border-b border-slate-200">
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-2">Total Akumulasi Pendapatan</p>
                    <p class="text-4xl md:text-5xl font-extrabold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-slate-500">Dihitung dari semua transaksi yang berstatus Selesai/Diambil</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Selesai</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rincian</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($transactions->whereIn('status', ['selesai', 'diambil']) as $trx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $trx->updated_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $trx->invoice_code }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 w-1/3">
                                    <ul class="list-disc pl-4">
                                        @foreach($trx->details as $detail)
                                            <li>{{ $detail->service->name }} ({{ $detail->quantity }} {{ $detail->service->unit }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-bold text-right">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Use Alpine or inline js for AJAX status update
        async function updateStatus(id, newStatus) {
            try {
                // We're expecting Sanctum or Session auth
                const res = await fetch(`/api/transactions/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                
                if (res.ok) {
                    // Update successful!
                    location.reload(); // Quick reload to update stats
                } else {
                    alert('Gagal update status!');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
</body>
</html>
