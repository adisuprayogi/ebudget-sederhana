<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');

        $users = [
            [
                'username' => 'superadmin',
                'name' => 'Superadmin',
                'email' => 'superadmin@example.com',
                'full_name' => 'Superadmin Sistem',
                'password' => Hash::make('password'),
                'role_id' => $roles['superadmin']->id,
                'divisi_id' => null,
                'is_active' => true,
            ],
            [
                'username' => 'direktur_utama',
                'name' => 'Direktur Utama',
                'email' => 'direktur.utama@example.com',
                'full_name' => 'Direktur Utama Perusahaan',
                'password' => Hash::make('password'),
                'role_id' => $roles['direktur_utama']->id,
                'divisi_id' => null,
                'is_active' => true,
            ],
            [
                'username' => 'direktur_keuangan',
                'name' => 'Direktur Keuangan',
                'email' => 'direktur@example.com',
                'full_name' => 'Direktur Keuangan Perusahaan',
                'password' => Hash::make('password'),
                'role_id' => $roles['direktur_keuangan']->id,
                'divisi_id' => null,
                'is_active' => true,
            ],
            [
                'username' => 'kepala_it',
                'name' => 'Kepala Divisi IT',
                'email' => 'kepala.it@example.com',
                'full_name' => 'Kepala Divisi Teknologi Informasi',
                'password' => Hash::make('password'),
                'role_id' => $roles['kepala_divisi']->id,
                'divisi_id' => 1, // Asumsikan divisi IT ada di database
                'is_active' => true,
            ],
            [
                'username' => 'staff_it_1',
                'name' => 'Staff IT 1',
                'email' => 'staff1.it@example.com',
                'full_name' => 'Staff Divisi IT 1',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_divisi']->id,
                'divisi_id' => 1,
                'is_active' => true,
            ],
            [
                'username' => 'staff_keuangan_1',
                'name' => 'Staff Keuangan',
                'email' => 'staff.keuangan@example.com',
                'full_name' => 'Staff Bagian Keuangan',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_keuangan']->id,
                'divisi_id' => null,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
