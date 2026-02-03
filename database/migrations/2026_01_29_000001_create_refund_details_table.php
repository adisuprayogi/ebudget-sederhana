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
        Schema::create('refund_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_id')->constrained()->onDelete('cascade');
            $table->foreignId('lpj_id')->constrained('laporan_pertanggung_jawabans')->onDelete('cascade');
            $table->decimal('jumlah_refund', 20, 2)->default(0);
            $table->timestamps();

            $table->index(['refund_id', 'lpj_id']);
            $table->index('lpj_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_details');
    }
};
