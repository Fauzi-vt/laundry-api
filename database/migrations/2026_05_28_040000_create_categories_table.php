<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('accent_color')->nullable();
            $table->timestamps();
        });

        // 2. Insert initial categories
        $categories = [
            ['name' => 'Kiloan', 'icon' => '👕', 'accent_color' => 'blue', 'description' => 'Layanan cuci per kilogram, ekonomis & praktis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Linen & Selimut', 'icon' => '🛏️', 'accent_color' => 'violet', 'description' => 'Selimut, bedcover, sprei, bantal & guling', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sepatu & Tas', 'icon' => '👟', 'accent_color' => 'orange', 'description' => 'Sepatu, sneakers, tas kain dan ransel', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Setrika', 'icon' => '👔', 'accent_color' => 'emerald', 'description' => 'Khusus setrika tanpa cuci, rapi & bebas kusut', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Umum', 'icon' => '🧺', 'accent_color' => 'slate', 'description' => 'Layanan laundry umum lainnya', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        // 3. Add category_id to services table
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('name');
        });

        // 4. Map existing category text to category_id
        $services = DB::table('services')->get();
        foreach ($services as $service) {
            $catName = $service->category ?? 'Umum';
            // Find category
            $cat = DB::table('categories')->where('name', $catName)->first();
            if (!$cat) {
                if (stripos($catName, 'kiloan') !== false) {
                    $cat = DB::table('categories')->where('name', 'Kiloan')->first();
                } elseif (stripos($catName, 'selimut') !== false || stripos($catName, 'linen') !== false) {
                    $cat = DB::table('categories')->where('name', 'Linen & Selimut')->first();
                } elseif (stripos($catName, 'sepatu') !== false || stripos($catName, 'tas') !== false) {
                    $cat = DB::table('categories')->where('name', 'Sepatu & Tas')->first();
                } elseif (stripos($catName, 'setrika') !== false) {
                    $cat = DB::table('categories')->where('name', 'Setrika')->first();
                } else {
                    $cat = DB::table('categories')->where('name', 'Umum')->first();
                }
            }
            
            DB::table('services')->where('id', $service->id)->update([
                'category_id' => $cat->id
            ]);
        }

        // Set foreign key
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // 5. Drop category column from services
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->default('Umum')->after('name');
        });

        // Copy back category name
        $services = DB::table('services')->get();
        foreach ($services as $service) {
            $catName = 'Umum';
            if ($service->category_id) {
                $cat = DB::table('categories')->where('id', $service->category_id)->first();
                if ($cat) {
                    $catName = $cat->name;
                }
            }
            DB::table('services')->where('id', $service->id)->update([
                'category' => $catName
            ]);
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
