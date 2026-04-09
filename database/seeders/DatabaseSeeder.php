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

        // Services
        $services = [
            ['name' => 'Cuci Kiloan Standar', 'price' => 6000, 'unit' => 'kg'],
            ['name' => 'Cuci Kiloan Kilat', 'price' => 10000, 'unit' => 'kg'],
            ['name' => 'Cuci Selimut / Bedcover', 'price' => 25000, 'unit' => 'pcs'],
            ['name' => 'Cuci Sepatu Premium', 'price' => 35000, 'unit' => 'pasang'],
        ];

        foreach ($services as $srv) {
            \App\Models\Service::firstOrCreate(
                ['name' => $srv['name']],
                [
                    'price' => $srv['price'],
                    'unit' => $srv['unit'],
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

