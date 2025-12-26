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
        // First, migrate data from old pivot tables to new structure
        $existingRoles = DB::table('user_roles')->get();
        $existingDivisis = DB::table('user_divisis')->get();

        // Drop old pivot tables
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('user_divisis');

        // Create new unified pivot table
        Schema::create('user_divisi_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false)->comment('Primary combination for display');
            $table->timestamps();

            $table->unique(['user_id', 'divisi_id', 'role_id'], 'unique_user_divisi_role');
            $table->index('is_primary');
        });

        // Migrate existing data
        // For each user, match their role with their division
        foreach ($existingRoles as $userRole) {
            // Find matching division for this user
            $matchingDivisi = $existingDivisis->firstWhere('user_id', $userRole->user_id);

            if ($matchingDivisi) {
                // Has both role and division - create combined entry
                DB::table('user_divisi_role')->insert([
                    'user_id' => $userRole->user_id,
                    'divisi_id' => $matchingDivisi->divisi_id,
                    'role_id' => $userRole->role_id,
                    'is_primary' => $userRole->is_primary && $matchingDivisi->is_primary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Has role but no specific division - create without division
                DB::table('user_divisi_role')->insert([
                    'user_id' => $userRole->user_id,
                    'divisi_id' => null,
                    'role_id' => $userRole->role_id,
                    'is_primary' => $userRole->is_primary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Handle users who have division but no role entry (shouldn't happen, but just in case)
        foreach ($existingDivisis as $userDivisi) {
            $exists = DB::table('user_divisi_role')
                ->where('user_id', $userDivisi->user_id)
                ->where('divisi_id', $userDivisi->divisi_id)
                ->exists();

            if (!$exists) {
                // Get user's current role_id from users table
                $user = DB::table('users')->where('id', $userDivisi->user_id)->first();
                if ($user && $user->role_id) {
                    DB::table('user_divisi_role')->insert([
                        'user_id' => $userDivisi->user_id,
                        'divisi_id' => $userDivisi->divisi_id,
                        'role_id' => $user->role_id,
                        'is_primary' => $userDivisi->is_primary,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate old pivot tables
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('user_divisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'divisi_id']);
        });

        // Migrate data back from user_divisi_role
        $entries = DB::table('user_divisi_role')->get();

        foreach ($entries as $entry) {
            if ($entry->divisi_id) {
                DB::table('user_divisis')->insert([
                    'user_id' => $entry->user_id,
                    'divisi_id' => $entry->divisi_id,
                    'is_primary' => $entry->is_primary,
                    'created_at' => $entry->created_at,
                    'updated_at' => $entry->updated_at,
                ]);
            }

            // Only insert role if not duplicate
            $exists = DB::table('user_roles')
                ->where('user_id', $entry->user_id)
                ->where('role_id', $entry->role_id)
                ->exists();

            if (!$exists) {
                DB::table('user_roles')->insert([
                    'user_id' => $entry->user_id,
                    'role_id' => $entry->role_id,
                    'is_primary' => $entry->is_primary,
                    'created_at' => $entry->created_at,
                    'updated_at' => $entry->updated_at,
                ]);
            }
        }

        // Drop new pivot table
        Schema::dropIfExists('user_divisi_role');
    }
};
