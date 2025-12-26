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
        Schema::create('user_job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_position_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false)->comment('Jabatan utama jika user punya banyak jabatan');
            $table->date('assigned_at')->default(now());
            $table->date('ended_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'job_position_id', 'assigned_at'], 'unique_user_position_date');
            $table->index(['user_id', 'is_primary']);
            $table->index('job_position_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_job_positions');
    }
};
