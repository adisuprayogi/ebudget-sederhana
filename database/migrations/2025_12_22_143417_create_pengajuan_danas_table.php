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
        Schema::create('pengajuan_danas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 50)->unique();
            $table->date('tanggal_pengajuan');
            $table->foreignId('divisi_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->nullable();
            $table->enum('jenis_pengajuan', ['kegiatan', 'pengadaan', 'pembayaran', 'honorarium', 'sewa', 'konsumsi', 'lainnya']);
            $table->enum('penerima_manfaat_type', ['pengaju', 'pic_kegiatan', 'pegawai', 'vendor', 'non_pegawai', 'internal', 'external']);
            $table->bigInteger('penerima_manfaat_id')->nullable();
            $table->string('penerima_manfaat_name')->nullable();
            $table->json('penerima_manfaat_detail')->nullable();
            $table->string('judul_pengajuan');
            $table->text('deskripsi')->nullable();
            $table->decimal('total_pengajuan', 20, 2);
            $table->enum('status', ['draft', 'menunggu_approval', 'revisi', 'disetujui', 'ditolak', 'dicairkan', 'selesai'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'divisi_id']);
            $table->index(['created_by', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_danas');
    }
};
