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
            if (!Schema::hasColumn('pengajuan_danas', 'sub_program_id')) {
                $table->foreignId('sub_program_id')->nullable()->after('program_kerja_id')->constrained('sub_programs')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_danas', function (Blueprint $table) {
            $table->dropForeign(['sub_program_id']);
            $table->dropColumn('sub_program_id');
        });
    }
};
