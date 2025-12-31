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
        Schema::table('refunds', function (Blueprint $table) {
            $table->string('nomor_refund')->unique()->after('id');
            $table->foreignId('pencairan_dana_id')->nullable()->after('nomor_refund')->constrained('pencairan_danas')->nullOnDelete();
            $table->foreignId('pengajuan_dana_id')->nullable()->after('pencairan_dana_id')->constrained('pengajuan_danas')->nullOnDelete();
            $table->foreignId('periode_anggaran_id')->nullable()->after('pengajuan_dana_id')->constrained('periode_anggaran')->nullOnDelete();
            $table->date('tanggal_refund')->nullable()->after('periode_anggaran_id');
            $table->decimal('jumlah_refund', 15, 2)->default(0)->after('tanggal_refund');
            $table->text('alasan_refund')->nullable()->after('jumlah_refund');
            $table->enum('jenis_refund', ['kelebihan', 'dana_kembali', 'batal', 'pengembalian lainnya'])->default('dana_kembali')->after('alasan_refund');
            $table->string('rekening_tujuan')->nullable()->after('jenis_refund');
            $table->string('bukti_transfer')->nullable()->after('rekening_tujuan');
            $table->enum('status', ['draft', 'menunggu_approval', 'approved', 'rejected', 'processed'])->default('draft')->after('bukti_transfer');
            $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('created_by');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->text('catatan_approval')->nullable()->after('approved_by');
            $table->date('tanggal_transfer')->nullable()->after('catatan_approval');
            $table->timestamp('processed_at')->nullable()->after('tanggal_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign(['pencairan_dana_id']);
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropForeign(['periode_anggaran_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'nomor_refund',
                'pencairan_dana_id',
                'pengajuan_dana_id',
                'periode_anggaran_id',
                'tanggal_refund',
                'jumlah_refund',
                'alasan_refund',
                'jenis_refund',
                'rekening_tujuan',
                'bukti_transfer',
                'status',
                'created_by',
                'approved_at',
                'approved_by',
                'catatan_approval',
                'tanggal_transfer',
                'processed_at',
            ]);
        });
    }
};
