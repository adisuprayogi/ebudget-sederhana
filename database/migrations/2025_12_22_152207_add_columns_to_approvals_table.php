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
        Schema::table('approvals', function (Blueprint $table) {
            $table->foreignId('pengajuan_dana_id')->constrained()->after('id');
            $table->foreignId('approver_id')->nullable()->constrained('users')->after('pengajuan_dana_id');
            $table->enum('level', ['kepala_divisi', 'direktur_keuangan', 'direktur_utama'])->after('approver_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('level');
            $table->text('notes')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('notes');

            $table->index(['pengajuan_dana_id', 'status']);
            $table->index(['approver_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_dana_id']);
            $table->dropForeign(['approver_id']);
            $table->dropIndex(['pengajuan_dana_id', 'status']);
            $table->dropIndex(['approver_id', 'status']);
            $table->dropColumn(['pengajuan_dana_id', 'approver_id', 'level', 'status', 'notes', 'approved_at']);
        });
    }
};
