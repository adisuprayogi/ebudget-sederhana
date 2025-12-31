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
            // Add rekening_perusahaan_id as foreign key
            $table->foreignId('rekening_perusahaan_id')->nullable()->after('pengajuan_dana_id')->constrained('rekening_perusahaans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencairan_danas', function (Blueprint $table) {
            $table->dropForeign(['rekening_perusahaan_id']);
            $table->dropColumn('rekening_perusahaan_id');
        });
    }
};
