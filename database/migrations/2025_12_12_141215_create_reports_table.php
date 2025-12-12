<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // Relasi ke user yang melapor
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Isi laporan
            $table->text('message');
            $table->string('photo')->nullable(); // Foto bukti
            $table->date('date')->useCurrent(); // Tanggal kejadian

            // Status & Respon Admin
            $table->enum('status', ['dikirim', 'sedang_dikerjakan', 'selesai'])->default('dikirim');
            $table->text('response')->nullable(); // Jawaban admin
            $table->foreignId('handler_id')->nullable()->constrained('users'); // Admin yang menangani

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
