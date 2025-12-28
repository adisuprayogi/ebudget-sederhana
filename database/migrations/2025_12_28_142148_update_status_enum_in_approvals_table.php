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
        // Add 'waiting' and 'cancelled' to status ENUM
        DB::statement("ALTER TABLE approvals MODIFY COLUMN status ENUM('pending','waiting','disetujui','ditolak','cancelled')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM values
        DB::statement("ALTER TABLE approvals MODIFY COLUMN status ENUM('pending','disetujui','ditolak')");
    }
};
