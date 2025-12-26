<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Superadmin - Akses penuh ke seluruh sistem',
                'permissions' => [
                    // All permissions
                    '*',
                ],
            ],
            [
                'name' => 'direktur_utama',
                'description' => 'Direktur Utama - Approval dan laporan tingkat tertinggi',
                'permissions' => [
                    'pengajuan_dana.approve',
                    'pengajuan_dana.approve_all',
                    'pencairan_dana.approve',
                    'report.view_all',
                    'report.generate_all',
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
    }
}
