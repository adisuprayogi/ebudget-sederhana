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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('kode_vendor', 50)->unique();
            $table->string('nama_vendor', 255);
            $table->string('jenis_vendor', 50)->default('supplier'); // supplier, kontraktor, konsultan, lainnya
            $table->string('npwp', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('propinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('negara', 100)->default('Indonesia');
            $table->string('telepon', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('kontak_person', 100)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->decimal('rating', 2, 1)->default(0); // 1-5 rating
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
