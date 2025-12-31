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
        // First, update any 'submitted' to 'menunggu_verifikasi'
        \DB::statement("UPDATE laporan_pertanggung_jawabans SET status = 'menunggu_verifikasi' WHERE status = 'submitted'");

        // Then modify the enum
        \DB::statement("ALTER TABLE laporan_pertanggung_jawabans MODIFY COLUMN status ENUM('draft', 'menunggu_verifikasi', 'approved', 'rejected', 'revisi') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'menunggu_verifikasi' back to 'submitted'
        \DB::statement("UPDATE laporan_pertanggung_jawabans SET status = 'submitted' WHERE status = 'menunggu_verifikasi'");

        // Then modify the enum back
        \DB::statement("ALTER TABLE laporan_pertanggung_jawabans MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'rejected', 'revisi') DEFAULT 'draft'");
    }
};
