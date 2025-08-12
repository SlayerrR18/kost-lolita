<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders','type')) {
                $table->enum('type', ['new','extension'])->default('new')->after('status');
            }
            if (!Schema::hasColumn('orders','parent_order_id')) {
                $table->foreignId('parent_order_id')->nullable()->after('type')
                      ->constrained('orders')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders','confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('tanggal_keluar');
            }
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders','parent_order_id')) $table->dropConstrainedForeignId('parent_order_id');
            if (Schema::hasColumn('orders','type')) $table->dropColumn('type');
            if (Schema::hasColumn('orders','confirmed_at')) $table->dropColumn('confirmed_at');
        });
    }
};

