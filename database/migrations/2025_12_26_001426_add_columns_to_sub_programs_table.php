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
            // Add program_kerja_id foreign key
            if (!Schema::hasColumn('sub_programs', 'program_kerja_id')) {
                $table->foreignId('program_kerja_id')->nullable()->after('id')->constrained('program_kerjas')->nullOnDelete();
            }

            // Add other columns
            if (!Schema::hasColumn('sub_programs', 'kode_sub_program')) {
                $table->string('kode_sub_program', 50)->nullable()->after('program_kerja_id');
            }
            if (!Schema::hasColumn('sub_programs', 'nama_sub_program')) {
                $table->string('nama_sub_program')->nullable()->after('kode_sub_program');
            }
            if (!Schema::hasColumn('sub_programs', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_sub_program');
            }
            if (!Schema::hasColumn('sub_programs', 'pagu_anggaran')) {
                $table->decimal('pagu_anggaran', 15, 2)->default(0)->after('deskripsi');
            }
            if (!Schema::hasColumn('sub_programs', 'target_output')) {
                $table->string('target_output')->nullable()->after('pagu_anggaran');
            }
            if (!Schema::hasColumn('sub_programs', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('target_output');
            }
            if (!Schema::hasColumn('sub_programs', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_programs', function (Blueprint $table) {
            $table->dropForeign(['program_kerja_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'program_kerja_id',
                'kode_sub_program',
                'nama_sub_program',
                'deskripsi',
                'pagu_anggaran',
                'target_output',
                'status',
                'created_by',
            ]);
        });
    }
};
