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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pengeluaran
            $table->text('description')->nullable(); // Deskripsi
            $table->decimal('amount', 12, 2); // Nominal pengeluaran
            $table->date('date'); // Tanggal pengeluaran
            $table->string('category'); // Kategori (maintenance, utilities, supplies, etc)
            $table->string('payment_method')->nullable(); // Metode pembayaran
            $table->string('reference')->nullable(); // Referensi (invoice, receipt number, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
