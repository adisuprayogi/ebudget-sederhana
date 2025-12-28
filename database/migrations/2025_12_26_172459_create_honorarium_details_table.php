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
        Schema::create('honorarium_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_dana_id')->constrained()->onDelete('cascade');
            $table->enum('penerima_manfaat_type', ['karyawan', 'non_karyawan']);
            $table->unsignedBigInteger('penerima_manfaat_id')->nullable(); // user_id jika karyawan
            $table->string('penerima_manfaat_name')->nullable(); // nama jika non-karyawan
            $table->string('jabatan')->nullable();
            $table->decimal('jumlah_honor', 15, 2);
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lampiran')->nullable(); // path file lampiran
            $table->string('lampiran_filename')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honorarium_details');
    }
};
