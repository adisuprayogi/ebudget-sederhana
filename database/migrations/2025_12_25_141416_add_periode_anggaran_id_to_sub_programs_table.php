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
        Schema::table('sub_programs', function (Blueprint $table) {
            $table->foreignId('periode_anggaran_id')->nullable()->after('id')->constrained('periode_anggaran')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_programs', function (Blueprint $table) {
            $table->dropForeign(['periode_anggaran_id']);
            $table->dropColumn('periode_anggaran_id');
        });
    }
};
