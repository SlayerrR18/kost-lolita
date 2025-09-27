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
            // Mengubah panjang kolom 'type' menjadi 50 karakter
            // Method ->change() digunakan untuk memodifikasi kolom yang sudah ada
            $table->string('type', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jika perlu rollback, kembalikan ke ukuran sebelumnya
            // Ganti 20 dengan ukuran asli kolom Anda jika Anda mengetahuinya
            $table->string('type', 20)->nullable()->change();
        });
    }
};
