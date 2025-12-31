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
        // Get current status values from pengajuan_danas table
        $statuses = ['draft', 'menunggu_approval', 'revisi', 'disetujui', 'ditolak', 'dicairkan', 'selesai', 'menunggu_pencairan', 'pencairan_rejected'];

        // Add 'cair' status
        $allStatuses = array_merge($statuses, ['cair']);

        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM('" . implode("','", $allStatuses) . "') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original status values
        $statuses = ['draft', 'menunggu_approval', 'revisi', 'disetujui', 'ditolak', 'dicairkan', 'selesai', 'menunggu_pencairan', 'pencairan_rejected'];

        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM('" . implode("','", $statuses) . "') DEFAULT 'draft'");
    }
};
