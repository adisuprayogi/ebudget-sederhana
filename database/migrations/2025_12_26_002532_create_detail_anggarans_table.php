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
        Schema::create('detail_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_program_id')->constrained()->onDelete('cascade');
            $table->string('nama_detail');
            $table->text('deskripsi')->nullable();
            $table->enum('frekuensi', ['bulanan', 'triwulan', 'semesteran', 'tahunan', 'sekali'])->default('sekali');
            $table->integer('jumlah_periode')->default(1); // jumlah bulan/triwulan/semester/tahun
            $table->decimal('nominal_per_periode', 15, 2)->default(0);
            $table->decimal('total_nominal', 15, 2)->default(0); // jumlah_periode × nominal_per_periode
            $table->string('satuan')->nullable(); // e.g., 'bulan', 'tahun', 'kali'
            $table->enum('status', ['active', 'inactive', 'selesai'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('sub_program_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_anggarans');
    }
};
