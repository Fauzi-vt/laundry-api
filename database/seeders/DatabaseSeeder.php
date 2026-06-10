<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '081234567890',
                'address'  => 'Jl. Laundry No. 1'
            ]
        );

        // Buat user biasa default
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'Pelanggan Setia',
                'password' => Hash::make('password'),
                'role'     => 'user',
                'phone'    => '089876543210',
                'address'  => 'Jl. Pelanggan No. 2'
            ]
        );

        // Create initial categories
        $defaultCategories = [
            ['name' => 'Kiloan', 'icon' => '👕', 'accent_color' => 'blue', 'description' => 'Layanan cuci per kilogram, ekonomis & praktis'],
            ['name' => 'Linen & Selimut', 'icon' => '🛏️', 'accent_color' => 'violet', 'description' => 'Selimut, bedcover, sprei, bantal & guling'],
            ['name' => 'Sepatu & Tas', 'icon' => '👟', 'accent_color' => 'orange', 'description' => 'Sepatu, sneakers, tas kain dan ransel'],
            ['name' => 'Setrika', 'icon' => '👔', 'accent_color' => 'emerald', 'description' => 'Khusus setrika tanpa cuci, rapi & bebas kusut'],
            ['name' => 'Umum', 'icon' => '🧺', 'accent_color' => 'slate', 'description' => 'Layanan laundry umum lainnya'],
        ];

        foreach ($defaultCategories as $cat) {
            \App\Models\Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Services dengan kategori dan gambar
        $services = [
            // Kategori: Kiloan
            ['category' => 'Kiloan', 'name' => 'Cuci Kiloan Standar',    'price' => 6000,  'unit' => 'kg',     'image' => 'cuci_kiloan_standar.png',  'description' => 'Dicuci bersih, dikeringkan, dilipat rapi. Durasi 2-3 hari.'],
            ['category' => 'Kiloan', 'name' => 'Cuci Kiloan Kilat',      'price' => 10000, 'unit' => 'kg',     'image' => 'cuci_kiloan_kilat.png',    'description' => 'Proses cepat selesai dalam 1 hari. Cocok untuk kebutuhan mendesak.'],
            ['category' => 'Kiloan', 'name' => 'Cuci + Setrika Kiloan',  'price' => 8000,  'unit' => 'kg',     'image' => 'cuci_setrika_kiloan.png',  'description' => 'Cuci bersih sekaligus disetrika rapi. Siap pakai!'],
            // Kategori: Linen & Selimut
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Selimut Tipis',  'price' => 15000, 'unit' => 'pcs', 'image' => 'cuci_selimut_tipis.png', 'description' => 'Selimut ukuran standar hingga single.'],
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Bedcover / Selimut Tebal', 'price' => 25000, 'unit' => 'pcs', 'image' => 'cuci_bedcover.png',      'description' => 'Bedcover, selimut tebal, sprei queen & king.'],
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Bantal / Guling', 'price' => 10000, 'unit' => 'pcs', 'image' => 'cuci_bantal.png',        'description' => 'Bersih, fluffy, wangi seperti baru.'],
            // Kategori: Sepatu & Tas
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Sepatu Standar',  'price' => 20000, 'unit' => 'pasang', 'image' => 'cuci_sepatu_standar.png',  'description' => 'Sepatu kanvas, sneakers, olahraga.'],
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Sepatu Premium',   'price' => 35000, 'unit' => 'pasang', 'image' => 'cuci_sepatu_premium.png',  'description' => 'Sepatu kulit, suede, atau sepatu branded. Perawatan ekstra.'],
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Tas',             'price' => 30000, 'unit' => 'pcs',    'image' => 'cuci_tas.png',             'description' => 'Tas kain, kanvas, ransel. Bersih & tidak merusak bahan.'],
            // Kategori: Setrika
            ['category' => 'Setrika', 'name' => 'Setrika Saja (Kiloan)',   'price' => 4000,  'unit' => 'kg',  'image' => 'setrika_kiloan.png',       'description' => 'Hanya setrika, tanpa cuci. Rapi & bebas kusut.'],
            ['category' => 'Setrika', 'name' => 'Setrika Saja (Satuan)',   'price' => 3000,  'unit' => 'pcs', 'image' => 'setrika_satuan.png',       'description' => 'Per lembar pakaian, cocok untuk baju formal.'],
        ];

        foreach ($services as $srv) {
            $category = \App\Models\Category::where('name', $srv['category'])->first();
            \App\Models\Service::updateOrCreate(
                ['name' => $srv['name']],
                [
                    'category_id' => $category->id,
                    'price'       => $srv['price'],
                    'unit'        => $srv['unit'],
                    'description' => $srv['description'],
                    'image'       => $srv['image'],
                ]
            );
        }


        // Dummy Transaction if not exists
        if (\App\Models\Transaction::count() === 0) {
            $service = \App\Models\Service::first();
            $trx = \App\Models\Transaction::create([
                'user_id' => $user->id,
                'invoice_code' => 'INV-DUMMY123',
                'total_price' => $service->price * 2,
                'status' => 'cuci'
            ]);

            \App\Models\TransactionDetail::create([
                'transaction_id' => $trx->id,
                'service_id' => $service->id,
                'quantity' => 2,
                'price' => $service->price,
                'subtotal' => $service->price * 2
            ]);
            
            $service2 = \App\Models\Service::skip(2)->first();
            $trx2 = \App\Models\Transaction::create([
                'user_id' => $user->id,
                'invoice_code' => 'INV-LNDRYOK',
                'total_price' => $service2->price * 1,
                'status' => 'selesai'
            ]);

            \App\Models\TransactionDetail::create([
                'transaction_id' => $trx2->id,
                'service_id' => $service2->id,
                'quantity' => 1,
                'price' => $service2->price,
                'subtotal' => $service2->price * 1
            ]);
        }

        // Seed default payment accounts
        $paymentAccounts = [
            ['type' => 'bank', 'provider_name' => 'BCA', 'provider_code' => 'bca', 'account_number' => '1234567890', 'account_name' => 'Rumah Laundry Tasikmalaya'],
            ['type' => 'bank', 'provider_name' => 'BRI', 'provider_code' => 'bri', 'account_number' => '0987654321', 'account_name' => 'Rumah Laundry Tasikmalaya'],
            ['type' => 'bank', 'provider_name' => 'Mandiri', 'provider_code' => 'mandiri', 'account_number' => '1122334455', 'account_name' => 'Rumah Laundry Tasikmalaya'],
            ['type' => 'bank', 'provider_name' => 'BSI', 'provider_code' => 'bsi', 'account_number' => '7081234567', 'account_name' => 'Rumah Laundry Tasikmalaya'],
            ['type' => 'bank', 'provider_name' => 'BNI', 'provider_code' => 'bni', 'account_number' => '0123456789', 'account_name' => 'Rumah Laundry Tasikmalaya'],
            ['type' => 'ewallet', 'provider_name' => 'GoPay', 'provider_code' => 'gopay', 'account_number' => '0812-3456-7890', 'account_name' => 'Rumah Laundry'],
            ['type' => 'ewallet', 'provider_name' => 'OVO', 'provider_code' => 'ovo', 'account_number' => '0812-3456-7890', 'account_name' => 'Rumah Laundry'],
            ['type' => 'ewallet', 'provider_name' => 'DANA', 'provider_code' => 'dana', 'account_number' => '0812-3456-7890', 'account_name' => 'Rumah Laundry'],
            ['type' => 'ewallet', 'provider_name' => 'ShopeePay', 'provider_code' => 'shopeepay', 'account_number' => '0812-3456-7890', 'account_name' => 'Rumah Laundry'],
        ];

        foreach ($paymentAccounts as $acc) {
            \App\Models\PaymentAccount::firstOrCreate(
                ['provider_code' => $acc['provider_code']],
                $acc
            );
        }
    }
}

