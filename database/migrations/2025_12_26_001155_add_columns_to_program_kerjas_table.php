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
        Schema::table('program_kerjas', function (Blueprint $table) {
            // Check and add columns that don't exist
            if (!Schema::hasColumn('program_kerjas', 'deskripsi')) {
                $table->text('deskripsi')->nullable();
            }
            if (!Schema::hasColumn('program_kerjas', 'pagu_anggaran')) {
                $table->decimal('pagu_anggaran', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('program_kerjas', 'target_output')) {
                $table->string('target_output')->nullable();
            }
            if (!Schema::hasColumn('program_kerjas', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            }
            if (!Schema::hasColumn('program_kerjas', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable();
            }
            if (!Schema::hasColumn('program_kerjas', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable();
            }
            if (!Schema::hasColumn('program_kerjas', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'deskripsi',
                'pagu_anggaran',
                'target_output',
                'status',
                'tanggal_mulai',
                'tanggal_selesai',
                'created_by',
            ]);
        });
    }
};
