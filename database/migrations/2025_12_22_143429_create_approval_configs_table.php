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
        Schema::create('approval_configs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pengajuan')->default('pengajuan_dana'); // pengajuan_dana, lpj, refund, pencairan_dana
            $table->decimal('minimal_nominal', 15, 2)->default(0);
            $table->string('level'); // kepala_divisi, direktur_keuangan, direktur_utama
            $table->integer('urutan')->default(1); // urutan approval
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['jenis_pengajuan', 'minimal_nominal', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_configs');
    }
};
