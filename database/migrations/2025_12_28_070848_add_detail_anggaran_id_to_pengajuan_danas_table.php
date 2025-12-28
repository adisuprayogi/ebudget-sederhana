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
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->foreignId('detail_anggaran_id')->nullable()->after('jenis_pengajuan')->constrained('detail_anggarans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['detail_anggaran_id']);
            $table->dropColumn('detail_anggaran_id');
        });
    }
};
