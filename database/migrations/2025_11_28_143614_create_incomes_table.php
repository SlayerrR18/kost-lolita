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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // Sumber pemasukan (Sewa Kamar, Lain-lain, etc)
            $table->text('description')->nullable(); // Deskripsi
            $table->decimal('amount', 12, 2); // Nominal pemasukan
            $table->date('date'); // Tanggal pemasukan
            $table->string('category')->default('other'); // Kategori (room_rent, other, etc)
            $table->string('payment_method')->nullable(); // Metode pembayaran
            $table->string('reference')->nullable(); // Referensi (nomor invoice, order id, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
