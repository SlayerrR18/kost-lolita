<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFotoColumnTypeOnKostsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom foto menjadi JSON
        Schema::table('kosts', function (Blueprint $table) {
            $table->json('foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke varchar jika perlu rollback
        Schema::table('kosts', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->change();
        });
    }
};
