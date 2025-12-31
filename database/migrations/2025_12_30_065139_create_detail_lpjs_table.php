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
        Schema::create('detail_lpjs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_pertanggung_jawaban_id')->constrained()->onDelete('cascade');
            $table->foreignId('detail_pencairan_id')->nullable()->constrained()->onDelete('set null');
            $table->string('uraian')->nullable();
            $table->decimal('volume_realisasi', 10, 2)->default(0);
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('subtotal_realisasi', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_lpjs');
    }
};
