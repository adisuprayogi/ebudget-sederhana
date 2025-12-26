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
        Schema::table('penetapan_pagus', function (Blueprint $table) {
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete()->after('id');
            $table->foreignId('periode_anggaran_id')->constrained('periode_anggaran')->cascadeOnDelete()->after('divisi_id');
            $table->decimal('jumlah_pagu', 15, 2)->default(0)->after('periode_anggaran_id');
            $table->text('catatan')->nullable()->after('jumlah_pagu');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('catatan');

            $table->unique(['divisi_id', 'periode_anggaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penetapan_pagus', function (Blueprint $table) {
            $table->dropForeign(['divisi_id']);
            $table->dropForeign(['periode_anggaran_id']);
            $table->dropForeign(['created_by']);
            $table->dropUnique(['divisi_id', 'periode_anggaran_id']);

            $table->dropColumn([
                'divisi_id',
                'periode_anggaran_id',
                'jumlah_pagu',
                'catatan',
                'created_by',
            ]);
        });
    }
};
