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
        Schema::table('detail_pencairans', function (Blueprint $table) {
            $table->unsignedBigInteger('honorarium_detail_id')->nullable()->after('detail_pengajuan_id');
            $table->foreign('honorarium_detail_id')->references('id')->on('honorarium_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pencairans', function (Blueprint $table) {
            $table->dropForeign(['honorarium_detail_id']);
            $table->dropColumn('honorarium_detail_id');
        });
    }
};
