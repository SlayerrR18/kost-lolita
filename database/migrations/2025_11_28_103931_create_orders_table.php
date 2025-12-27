<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // relasi ke user & room
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // data snapshot pemesan
            $table->string('email');
            $table->string('full_name');
            $table->string('phone');
            $table->string('address');

            // data identitas
            $table->string('id_number');
            $table->string('id_photo_path');

            // data sewa
            $table->integer('rent_duration');
            $table->date('start_date');

            // bukti transfer
            $table->string('transfer_proof_path');
            // status order
            $table->string('status')->default('pending');

            // opsional: catatan admin
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

