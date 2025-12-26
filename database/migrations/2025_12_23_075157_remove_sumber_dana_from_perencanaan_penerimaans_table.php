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
        Schema::table('perencanaan_penerimaans', function (Blueprint $table) {
            if (Schema::hasColumn('perencanaan_penerimaans', 'sumber_dana')) {
                $table->dropColumn('sumber_dana');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perencanaan_penerimaans', function (Blueprint $table) {
            $table->string('sumber_dana')->nullable()->after('uraian');
        });
    }
};
