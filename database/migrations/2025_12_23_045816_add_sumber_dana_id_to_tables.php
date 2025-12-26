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
        // Add sumber_dana_id to perencanaan_penerimaans table
        Schema::table('perencanaan_penerimaans', function (Blueprint $table) {
            $table->foreignId('sumber_dana_id')->nullable()->constrained('sumber_danas')->nullOnDelete();
        });

        // Create default sumber dana records
        \DB::statement("INSERT INTO sumber_danas (kode_sumber, nama_sumber, deskripsi, is_active, created_at, updated_at) VALUES
            ('APBD', 'Anggaran Pendapatan dan Belanja Daerah', 'APBD', 1, NOW(), NOW()),
            ('APBN', 'Anggaran Pendapatan dan Belanja Nasional', 'APBN', 1, NOW(), NOW()),
            ('lainnya', 'Lainnya', 'Sumber dana lainnya', 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE kode_sumber=VALUES(kode_sumber)
        ");

        // Add sumber_dana_id to pencatatan_penerimaans table
        // Note: pencatatan_penerimaans doesn't have sumber_dana column, so we just add the new column
        Schema::table('pencatatan_penerimaans', function (Blueprint $table) {
            $table->foreignId('sumber_dana_id')->nullable()->constrained('sumber_danas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencatatan_penerimaans', function (Blueprint $table) {
            $table->dropForeign(['sumber_dana_id']);
            $table->dropColumn('sumber_dana_id');
        });

        Schema::table('perencanaan_penerimaans', function (Blueprint $table) {
            $table->dropForeign(['sumber_dana_id']);
            $table->dropColumn('sumber_dana_id');
        });
    }
};
