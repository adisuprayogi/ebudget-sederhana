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
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            $table->foreignId('pengajuan_dana_id')->after('id')->constrained('pengajuan_danas')->cascadeOnDelete();
            $table->foreignId('sub_program_id')->nullable()->after('pengajuan_dana_id')->constrained('sub_programs')->nullOnDelete();
            $table->foreignId('detail_anggaran_id')->nullable()->after('sub_program_id')->constrained('detail_anggarans')->nullOnDelete();
            $table->string('uraian')->after('detail_anggaran_id');
            $table->decimal('volume', 10, 2)->after('uraian');
            $table->string('satuan')->after('volume');
            $table->decimal('harga_satuan', 15, 2)->after('satuan');
            $table->decimal('subtotal', 15, 2)->after('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            $table->dropForeign(['detail_anggaran_id']);
            $table->dropForeign(['sub_program_id']);
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropColumn(['pengajuan_dana_id', 'sub_program_id', 'detail_anggaran_id', 'uraian', 'volume', 'satuan', 'harga_satuan', 'subtotal']);
        });
    }
};
