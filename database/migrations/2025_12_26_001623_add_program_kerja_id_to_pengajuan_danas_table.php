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
            if (!Schema::hasColumn('pengajuan_danas', 'program_kerja_id')) {
                $table->foreignId('program_kerja_id')->nullable()->after('program_id')->constrained('program_kerjas')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['program_kerja_id']);
            $table->dropColumn('program_kerja_id');
        });
    }
};
