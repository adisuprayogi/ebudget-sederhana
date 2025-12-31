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
            // Drop the old string column
            $table->dropColumn('rekening_tujuan');
        });

        Schema::table('refunds', function (Blueprint $table) {
            // Add new foreign key column after jenis_refund
            $table->foreignId('rekening_perusahaan_id')
                ->nullable()
                ->after('jenis_refund')
                ->constrained('rekening_perusahaans')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign(['rekening_perusahaan_id']);
            $table->dropColumn('rekening_perusahaan_id');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->string('rekening_tujuan')->nullable()->after('jenis_refund');
        });
    }
};
