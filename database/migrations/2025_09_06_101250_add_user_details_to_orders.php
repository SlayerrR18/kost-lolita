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
            $table->string('emergency_phone')->nullable()->after('phone');
            $table->string('ktp_number', 20)->nullable()->after('ktp_image');
            $table->string('emergency_contact_name')->nullable()->after('emergency_phone');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_phone',
                'ktp_number',
                'emergency_contact_name',
                'emergency_contact_relation'
            ]);
        });
    }
};
