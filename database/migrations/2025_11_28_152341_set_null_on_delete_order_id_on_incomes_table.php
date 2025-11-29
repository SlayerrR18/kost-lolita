<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Change foreign key to ON DELETE SET NULL using raw SQL to avoid requiring doctrine/dbal
            if (Schema::hasColumn('incomes', 'order_id')) {
                try {
                    DB::statement('ALTER TABLE `incomes` DROP FOREIGN KEY `incomes_order_id_foreign`');
                } catch (\Exception $e) {
                    // if drop by conventional name fails, ignore
                }

                try {
                    DB::statement('ALTER TABLE `incomes` ADD CONSTRAINT `incomes_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL');
                } catch (\Exception $e) {
                    // ignore if already set or fails
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            if (Schema::hasColumn('incomes', 'order_id')) {
                try {
                    DB::statement('ALTER TABLE `incomes` DROP FOREIGN KEY `incomes_order_id_foreign`');
                } catch (\Exception $e) {
                    // ignore
                }

                try {
                    DB::statement('ALTER TABLE `incomes` ADD CONSTRAINT `incomes_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE');
                } catch (\Exception $e) {
                    // ignore
                }
            }
        });
    }
};
