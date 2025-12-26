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
        Schema::create('sumber_danas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sumber')->unique()->comment('Kode sumber dana, misal: APBD, APBN');
            $table->string('nama_sumber')->comment('Nama lengkap sumber dana');
            $table->text('deskripsi')->nullable()->comment('Deskripsi sumber dana');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumber_danas');
    }
};
