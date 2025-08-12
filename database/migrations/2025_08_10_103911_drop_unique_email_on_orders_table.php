<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // nama default index unik biasanya 'orders_email_unique'
            try {
                $table->dropUnique('orders_email_unique');
            } catch (\Throwable $e) {
                // fallback kalau nama index beda
                DB::statement('ALTER TABLE `orders` DROP INDEX `orders_email_unique`');
            }

            // optional: jadikan index biasa biar pencarian cepat
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->unique('email');
        });
    }
};

