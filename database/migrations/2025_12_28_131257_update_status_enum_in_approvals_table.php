<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::statement("UPDATE approvals SET status = 'disetujui' WHERE status = 'approved'");
        DB::statement("UPDATE approvals SET status = 'ditolak' WHERE status = 'rejected'");

        // Alter enum to match the values used in code
        DB::statement("ALTER TABLE approvals MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data first
        DB::statement("UPDATE approvals SET status = 'approved' WHERE status = 'disetujui'");
        DB::statement("UPDATE approvals SET status = 'rejected' WHERE status = 'ditolak'");

        // Revert enum
        DB::statement("ALTER TABLE approvals MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
