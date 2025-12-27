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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('response_photo')->nullable()->after('response');
            $table->timestamp('processing_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('processing_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['response_photo', 'processing_at', 'completed_at']);
        });
    }
};
