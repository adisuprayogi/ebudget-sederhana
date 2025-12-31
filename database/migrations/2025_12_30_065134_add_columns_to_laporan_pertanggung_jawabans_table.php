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
        Schema::table('laporan_pertanggung_jawabans', function (Blueprint $table) {
            $table->string('nomor_lpj')->unique()->after('id');
            $table->foreignId('pengajuan_dana_id')->nullable()->constrained()->onDelete('cascade')->after('nomor_lpj');
            $table->foreignId('pencairan_dana_id')->nullable()->constrained()->onDelete('cascade')->after('pengajuan_dana_id');
            $table->date('tanggal_lpj')->nullable()->after('pencairan_dana_id');
            $table->string('uraian_kegiatan')->nullable()->after('tanggal_lpj');
            $table->decimal('total_digunakan', 15, 2)->default(0)->after('uraian_kegiatan');
            $table->decimal('sisa_dana', 15, 2)->default(0)->after('total_digunakan');
            $table->text('catatan')->nullable()->after('sisa_dana');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'revisi'])->default('draft')->after('catatan');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('verified_by');
            $table->timestamp('submitted_at')->nullable()->after('approved_by');
            $table->timestamp('verified_at')->nullable()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('verified_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
            $table->text('approval_notes')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_pertanggung_jawabans', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropForeign(['pencairan_dana_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'nomor_lpj',
                'pengajuan_dana_id',
                'pencairan_dana_id',
                'tanggal_lpj',
                'uraian_kegiatan',
                'total_digunakan',
                'sisa_dana',
                'catatan',
                'status',
                'created_by',
                'verified_by',
                'approved_by',
                'submitted_at',
                'verified_at',
                'approved_at',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
                'approval_notes',
            ]);
        });
    }
};
