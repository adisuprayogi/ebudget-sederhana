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
        // Current statuses from previous migration
        $currentStatuses = ['pending', 'processing', 'processed', 'completed', 'failed', 'cancelled', 'menunggu', 'selesai', 'closed', 'revisi'];

        // Add LPJ-related statuses
        $lpjStatuses = ['menunggu_lpj', 'menunggu_verifikasi_lpj', 'menunggu_pengembalian'];

        // Combine all statuses
        $allStatuses = array_merge($currentStatuses, $lpjStatuses);

        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('" . implode("','", $allStatuses) . "') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove LPJ-related statuses
        $currentStatuses = ['pending', 'processing', 'processed', 'completed', 'failed', 'cancelled', 'menunggu', 'selesai', 'closed', 'revisi'];

        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('" . implode("','", $currentStatuses) . "') DEFAULT 'menunggu'");
    }
};
