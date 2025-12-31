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
        // Get current status values from pencairan_danas table
        $statuses = ['pending', 'processing', 'processed', 'completed', 'failed', 'cancelled'];
        $newStatuses = ['menunggu', 'selesai', 'closed', 'revisi'];

        // Combine old and new status values
        $allStatuses = array_merge($statuses, $newStatuses);

        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('" . implode("','", $allStatuses) . "') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original status values
        $statuses = ['pending', 'processing', 'processed', 'completed', 'failed', 'cancelled'];

        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('" . implode("','", $statuses) . "') DEFAULT 'pending'");
    }
};
