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
            $table->string('nama_bank')->nullable()->after('deskripsi');
            $table->string('rekening_tujuan')->nullable()->after('nama_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'rekening_tujuan']);
        });
    }
};
