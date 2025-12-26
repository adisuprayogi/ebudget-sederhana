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
        // Drop foreign key constraints first
        Schema::table('user_job_positions', function (Blueprint $table) {
            $table->dropForeign(['job_position_id']);
            $table->dropForeign(['user_id']);
        });

        // Drop job_positions foreign key
        Schema::table('job_positions', function (Blueprint $table) {
            $table->dropForeign(['divisi_id']);
        });

        // Drop tables
        Schema::dropIfExists('user_job_positions');
        Schema::dropIfExists('job_positions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables (for rollback)
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->string('kode_jabatan', 50);
            $table->string('nama_jabatan', 100);
            $table->string('level', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_position_id')->constrained('job_positions')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->date('assigned_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
};
