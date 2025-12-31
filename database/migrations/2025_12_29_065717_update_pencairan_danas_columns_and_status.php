<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pencairan_danas', function (Blueprint $table) {
            // Drop old foreign keys if they exist
            $table->dropForeign(['approved_by']);

            // Add processing columns
            $table->foreignId('processed_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable()->after('processed_by');
            $table->string('bukti_pencairan')->nullable()->after('processed_at');

            // Add verification columns
            $table->foreignId('verified_by')->nullable()->after('bukti_pencairan')->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');

            // Add cancellation columns
            $table->foreignId('cancelled_by')->nullable()->after('verification_notes')->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');

            // Drop the old approved_by column if still needed, we can keep it for backward compatibility
            // $table->dropColumn('approved_by');
            // $table->dropColumn('approved_at');
        });

        // Update status enum - MySQL doesn't support direct enum modification, need to recreate
        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('pending', 'processed', 'completed', 'cancelled', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencairan_danas', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['cancelled_by']);

            $table->dropColumn([
                'processed_by',
                'processed_at',
                'bukti_pencairan',
                'verified_by',
                'verified_at',
                'verification_notes',
                'cancelled_by',
                'cancelled_at',
                'cancellation_reason',
            ]);

            // Restore old foreign key
            $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
        });

        // Revert status enum
        DB::statement("ALTER TABLE pencairan_danas MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
