<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('payment_method', 50)->nullable(); // e.g., 'cash', 'transfer'
            $table->string('delivery_type', 50)->nullable(); // e.g., 'antar_jemput', 'bawa_sendiri'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['address', 'phone', 'payment_method', 'delivery_type']);
        });
    }
};
