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
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained()->onDelete('cascade');
            $table->string('kode_jabatan', 50)->unique();
            $table->string('nama_jabatan');
            $table->text('deskripsi')->nullable();
            $table->integer('level')->default(1)->comment('Level hierarki jabatan, 1 tertinggi');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['divisi_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_positions');
    }
};
