<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['open','in_progress','resolved'])->default('open')->after('date');
            $table->text('response')->nullable()->after('status');
            $table->timestamp('responded_at')->nullable()->after('response');
            $table->foreignId('handled_by')->nullable()->after('responded_at')
                  ->constrained('users')->nullOnDelete();
            $table->index(['status','date']);
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('handled_by');
            $table->dropColumn(['status','response','responded_at']);
        });
    }
};

