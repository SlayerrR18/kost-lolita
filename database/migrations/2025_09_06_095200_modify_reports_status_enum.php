<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyReportsStatusEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First modify the column to allow larger values
        DB::statement("ALTER TABLE reports MODIFY COLUMN status VARCHAR(20)");

        // Then update the data
        DB::statement("UPDATE reports SET status = CASE
            WHEN status = 'open' THEN 'dikirim'
            WHEN status = 'in_progress' THEN 'sedang_dikerjakan'
            WHEN status = 'resolved' THEN 'selesai'
            ELSE status END");

        // Finally convert back to ENUM with new values
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('dikirim', 'sedang_dikerjakan', 'selesai') DEFAULT 'dikirim'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First modify to VARCHAR to allow data conversion
        DB::statement("ALTER TABLE reports MODIFY COLUMN status VARCHAR(20)");

        // Then convert data back
        DB::statement("UPDATE reports SET status = CASE
            WHEN status = 'dikirim' THEN 'open'
            WHEN status = 'sedang_dikerjakan' THEN 'in_progress'
            WHEN status = 'selesai' THEN 'resolved'
            ELSE status END");

        // Finally convert back to original ENUM
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('open', 'in_progress', 'resolved') DEFAULT 'open'");
    }
};
