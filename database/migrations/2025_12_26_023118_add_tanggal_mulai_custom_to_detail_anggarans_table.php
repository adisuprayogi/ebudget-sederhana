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
        Schema::table('detail_anggarans', function (Blueprint $table) {
            $table->date('tanggal_mulai_custom')->nullable()->after('satuan');
            $table->index('tanggal_mulai_custom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_anggarans', function (Blueprint $table) {
            $table->dropIndex(['tanggal_mulai_custom']);
            $table->dropColumn('tanggal_mulai_custom');
        });
    }
};
