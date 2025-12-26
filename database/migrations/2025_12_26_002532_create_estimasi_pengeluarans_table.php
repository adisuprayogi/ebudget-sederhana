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
        Schema::create('estimasi_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_anggaran_id')->constrained()->onDelete('cascade');
            $table->integer('urutan_periode'); // urutan: 1, 2, 3, ... 12
            $table->date('tanggal_rencana_realisasi'); // estimasi tanggal pengeluaran
            $table->decimal('nominal_rencana', 15, 2)->default(0); // nominal rencana
            $table->decimal('nominal_realisasi', 15, 2)->nullable()->default(0); // realisasi aktual (diisi saat realisasi)
            $table->date('tanggal_realisasi')->nullable(); // tanggal realisasi aktual
            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('detail_anggaran_id');
            $table->index('tanggal_rencana_realisasi');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimasi_pengeluarans');
    }
};
