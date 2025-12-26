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
        Schema::table('pencatatan_penerimaans', function (Blueprint $table) {
            $table->date('tanggal_penerimaan')->after('id');
            $table->text('uraian')->after('tanggal_penerimaan');
            $table->decimal('jumlah_diterima', 20, 2)->default(0)->after('uraian');
            $table->string('bukti_penerimaan')->nullable()->after('jumlah_diterima');
            $table->foreignId('perencanaan_penerimaan_id')->nullable()->constrained()->onDelete('set null')->after('sumber_dana_id');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('perencanaan_penerimaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencatatan_penerimaans', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['perencanaan_penerimaan_id']);
            $table->dropColumn(['created_by', 'perencanaan_penerimaan_id', 'bukti_penerimaan', 'jumlah_diterima', 'uraian', 'tanggal_penerimaan']);
        });
    }
};
