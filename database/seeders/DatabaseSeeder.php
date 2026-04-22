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

        // Services dengan kategori
        $services = [
            // Kategori: Kiloan
            ['category' => 'Kiloan', 'name' => 'Cuci Kiloan Standar',    'price' => 6000,  'unit' => 'kg',     'description' => 'Dicuci bersih, dikeringkan, dilipat rapi. Durasi 2-3 hari.'],
            ['category' => 'Kiloan', 'name' => 'Cuci Kiloan Kilat',      'price' => 10000, 'unit' => 'kg',     'description' => 'Proses cepat selesai dalam 1 hari. Cocok untuk kebutuhan mendesak.'],
            ['category' => 'Kiloan', 'name' => 'Cuci + Setrika Kiloan',  'price' => 8000,  'unit' => 'kg',     'description' => 'Cuci bersih sekaligus disetrika rapi. Siap pakai!'],
            // Kategori: Linen & Selimut
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Selimut Tipis',  'price' => 15000, 'unit' => 'pcs', 'description' => 'Selimut ukuran standar hingga single.'],
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Bedcover / Selimut Tebal', 'price' => 25000, 'unit' => 'pcs', 'description' => 'Bedcover, selimut tebal, sprei queen & king.'],
            ['category' => 'Linen & Selimut', 'name' => 'Cuci Bantal / Guling', 'price' => 10000, 'unit' => 'pcs', 'description' => 'Bersih, fluffy, wangi seperti baru.'],
            // Kategori: Sepatu & Tas
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Sepatu Standar',  'price' => 20000, 'unit' => 'pasang', 'description' => 'Sepatu kanvas, sneakers, olahraga.'],
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Sepatu Premium',   'price' => 35000, 'unit' => 'pasang', 'description' => 'Sepatu kulit, suede, atau sepatu branded. Perawatan ekstra.'],
            ['category' => 'Sepatu & Tas', 'name' => 'Cuci Tas',             'price' => 30000, 'unit' => 'pcs',    'description' => 'Tas kain, kanvas, ransel. Bersih & tidak merusak bahan.'],
            // Kategori: Setrika
            ['category' => 'Setrika', 'name' => 'Setrika Saja (Kiloan)',   'price' => 4000,  'unit' => 'kg',  'description' => 'Hanya setrika, tanpa cuci. Rapi & bebas kusut.'],
            ['category' => 'Setrika', 'name' => 'Setrika Saja (Satuan)',   'price' => 3000,  'unit' => 'pcs', 'description' => 'Per lembar pakaian, cocok untuk baju formal.'],
        ];

        foreach ($services as $srv) {
            \App\Models\Service::firstOrCreate(
                ['name' => $srv['name']],
                [
                    'category'    => $srv['category'],
                    'price'       => $srv['price'],
                    'unit'        => $srv['unit'],
                    'description' => $srv['description'],
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
    }
}

