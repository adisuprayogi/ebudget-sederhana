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
        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM(
            'draft',
            'menunggu_approval',
            'menunggu_pencairan',
            'cair',
            'menunggu_lpj',
            'lpj_submitted',
            'lpj_ditolak',
            'lpj_disetujui',
            'menunggu_refund',
            'refund_ditolak',
            'selesai',
            'ditolak',
            'rejected',
            'disetujui',
            'approved',
            'dicairkan',
            'cancelled'
        ) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pengajuan_danas MODIFY COLUMN status ENUM(
            'draft',
            'menunggu_approval',
            'menunggu_pencairan',
            'cair',
            'selesai',
            'ditolak',
            'rejected',
            'disetujui',
            'approved',
            'dicairkan',
            'cancelled'
        ) DEFAULT 'draft'");
    }
};
