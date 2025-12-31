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
        Schema::table('detail_pencairans', function (Blueprint $table) {
            $table->foreignId('pencairan_dana_id')->nullable()->constrained('pencairan_danas')->onDelete('cascade');
            $table->foreignId('detail_pengajuan_id')->nullable()->constrained('detail_pengajuans')->onDelete('cascade');
            $table->string('uraian')->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();

            $table->index('pencairan_dana_id');
            $table->index('detail_pengajuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pencairans', function (Blueprint $table) {
            $table->dropForeign(['pencairan_dana_id']);
            $table->dropForeign(['detail_pengajuan_id']);
            $table->dropIndex(['pencairan_dana_id']);
            $table->dropIndex(['detail_pengajuan_id']);
            $table->dropColumn(['pencairan_dana_id', 'detail_pengajuan_id', 'uraian', 'volume', 'satuan', 'harga_satuan', 'subtotal']);
        });
    }
};
