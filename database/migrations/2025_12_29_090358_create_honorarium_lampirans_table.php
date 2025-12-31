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
        Schema::create('honorarium_lampirans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pencairan_dana_id');
            $table->unsignedBigInteger('honorarium_detail_id')->nullable();
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file')->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('pencairan_dana_id')->references('id')->on('pencairan_danas')->onDelete('cascade');
            $table->foreign('honorarium_detail_id')->references('id')->on('honorarium_details')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honorarium_lampirans');
    }
};
