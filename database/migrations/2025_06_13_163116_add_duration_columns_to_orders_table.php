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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('duration')->after('status')->comment('Lama sewa dalam bulan');
            $table->date('tanggal_masuk')->after('duration')->nullable();
            $table->date('tanggal_keluar')->after('tanggal_masuk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['duration', 'tanggal_masuk', 'tanggal_keluar']);
        });
    }
};
