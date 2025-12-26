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
        Schema::create('periode_anggaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_periode', 20)->unique();
            $table->string('nama_periode', 200);
            $table->year('tahun_anggaran');

            // Fase Perencanaan Anggaran
            $table->date('tanggal_mulai_perencanaan_anggaran');
            $table->date('tanggal_selesai_perencanaan_anggaran');

            // Fase Penggunaan Anggaran
            $table->date('tanggal_mulai_penggunaan_anggaran');
            $table->date('tanggal_selesai_penggunaan_anggaran');

            // Status dan approval
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_anggaran');
    }
};