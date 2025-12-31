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
        Schema::table('pencairan_danas', function (Blueprint $table) {
            // Change total_pencairan to jumlah_pencairan
            $table->renameColumn('total_pencairan', 'jumlah_pencairan');

            // Add metode pencairan
            $table->enum('metode_pencairan', ['transfer', 'cash', 'reimburse'])->nullable()->after('jumlah_pencairan');

            // Rekening tujuan
            $table->string('nama_bank')->nullable()->after('catatan');
            $table->string('nomor_rekening')->nullable()->after('nama_bank');
            $table->string('atas_nama')->nullable()->after('nomor_rekening');

            // Rekening sumber
            $table->string('nama_bank_sumber')->nullable()->after('atas_nama');
            $table->string('nomor_rekening_sumber')->nullable()->after('nama_bank_sumber');

            // Update status enum
            $table->enum('status', ['pending', 'processing', 'processed', 'completed', 'failed', 'cancelled'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencairan_danas', function (Blueprint $table) {
            $table->renameColumn('jumlah_pencairan', 'total_pencairan');
            $table->dropColumn([
                'metode_pencairan',
                'nama_bank',
                'nomor_rekening',
                'atas_nama',
                'nama_bank_sumber',
                'nomor_rekening_sumber'
            ]);
            // Note: Reverting status enum change requires careful handling
            $table->enum('status', ['pending', 'approved', 'rejected'])->change();
        });
    }
};
