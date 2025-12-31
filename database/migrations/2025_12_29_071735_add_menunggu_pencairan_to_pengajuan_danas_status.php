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
        // First, update any existing 'cancelled' status to 'ditolak' (which is a valid status)
        DB::table('pengajuan_danas')->where('status', 'cancelled')->update(['status' => 'ditolak']);

        // Now add the new statuses to the enum
        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM('draft', 'menunggu_approval', 'revisi', 'disetujui', 'ditolak', 'dicairkan', 'selesai', 'menunggu_pencairan', 'pencairan_rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any 'menunggu_pencairan' or 'pencairan_rejected' to 'disetujui'
        DB::table('pengajuan_danas')->whereIn('status', ['menunggu_pencairan', 'pencairan_rejected'])->update(['status' => 'disetujui']);

        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM('draft', 'menunggu_approval', 'revisi', 'disetujui', 'ditolak', 'dicairkan', 'selesai') DEFAULT 'draft'");
    }
};
