<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing user roles to user_roles pivot table
        $users = DB::table('users')->whereNotNull('role_id')->get();
        foreach ($users as $user) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate existing user divisions to user_divisis pivot table
        $usersWithDivisi = DB::table('users')->whereNotNull('divisi_id')->get();
        foreach ($usersWithDivisi as $user) {
            DB::table('user_divisis')->insert([
                'user_id' => $user->id,
                'divisi_id' => $user->divisi_id,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear pivot tables
        DB::table('user_roles')->truncate();
        DB::table('user_divisis')->truncate();
    }
};
