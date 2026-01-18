<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for production.
     * Hanya membuat 1 user superadmin dengan password default.
     */
    public function run(): void
    {
        // 1. Create Roles terlebih dahulu
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Superadmin - Akses penuh ke seluruh sistem',
                'permissions' => ['*'],
            ],
            [
                'name' => 'direktur_utama',
                'description' => 'Direktur Utama - Approval dan laporan tingkat tertinggi',
                'permissions' => [
                    'pengajuan_dana.approve',
                    'pengajuan_dana.approve_all',
                    'pencairan_dana.approve',
                    'report.view',
                    'report.view_all',
                    'report.generate_all',
                    'report.export',
                    'approval.view_all',
                ],
            ],
            [
                'name' => 'direktur_keuangan',
                'description' => 'Direktur Keuangan - Menangani perencanaan, alokasi pagu, dan oversight',
                'permissions' => [
                    'perencanaan_penerimaan.create',
                    'perencanaan_penerimaan.read',
                    'perencanaan_penerimaan.update',
                    'perencanaan_penerimaan.delete',
                    'penetapan_pagu.create',
                    'penetapan_pagu.read',
                    'penetapan_pagu.update',
                    'penetapan_pagu.delete',
                    'pengajuan_dana.approve',
                    'pencairan_dana.approve',
                    'report.view_all',
                    'user.manage',
                    'setting.manage',
                ],
            ],
            [
                'name' => 'kepala_divisi',
                'description' => 'Kepala Divisi - Merencanakan program kerja dan mengelola anggaran divisi',
                'permissions' => [
                    'program_kerja.create',
                    'program_kerja.read',
                    'program_kerja.update',
                    'program_kerja.delete',
                    'pengajuan_dana.create',
                    'pengajuan_dana.read',
                    'pengajuan_dana.update',
                    'pengajuan_dana.approve_divisi',
                    'pencairan_dana.read',
                    'lpj.create',
                    'lpj.read',
                    'lpj.update',
                    'lpj.approve',
                    'refund.create',
                    'refund.read',
                    'refund.update',
                    'report.divisi',
                ],
            ],
            [
                'name' => 'staff_divisi',
                'description' => 'Staff Divisi - Membuat pengajuan dan melaksanakan program kerja',
                'permissions' => [
                    'pengajuan_dana.create',
                    'pengajuan_dana.read',
                    'pengajuan_dana.update',
                    'pencairan_dana.read',
                    'lpj.create',
                    'lpj.read',
                    'lpj.update',
                    'refund.create',
                    'refund.read',
                    'refund.update',
                    'report.divisi_limited',
                ],
            ],
            [
                'name' => 'staff_keuangan',
                'description' => 'Staff Keuangan/Kasir - Menangani pencatatan penerimaan dan pencairan',
                'permissions' => [
                    'pencatatan_penerimaan.create',
                    'pencatatan_penerimaan.read',
                    'pencatatan_penerimaan.update',
                    'pencairan_dana.create',
                    'pencairan_dana.read',
                    'pencairan_dana.update',
                    'pencairan_dana.approve',
                    'lpj.read',
                    'lpj.verify',
                    'refund.process',
                    'report.finance',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'description' => $role['description'],
                    'permissions' => $role['permissions'],
                ]
            );
        }

        // 2. Create 1 User Superadmin saja
        $superadminRole = Role::where('name', 'superadmin')->first();

        // Hapus user superadmin yang lama jika ada (untuk fresh install)
        User::where('username', 'admin')->delete();

        User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@ebudget.system',
            'full_name' => 'Superadmin Sistem',
            'password' => Hash::make('Admin@123'), // Password default - UBAH setelah login pertama!
            'role_id' => $superadminRole->id,
            'divisi_id' => null,
            'is_active' => true,
        ]);

        $this->command->info('========================================');
        $this->command->info('  PRODUCTION SEEDER COMPLETED');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('User Superadmin telah dibuat:');
        $this->command->info('  Username: admin');
        $this->command->info('  Password: Admin@123');
        $this->command->info('  Email: admin@ebudget.system');
        $this->command->info('');
        $this->command->newLine();
        $this->command->warn('PENTING: Silakan ganti password segera setelah login pertama!');
        $this->command->info('========================================');
    }
}
