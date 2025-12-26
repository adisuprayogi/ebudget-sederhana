<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\JobPosition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = Divisi::all();

        if ($divisis->isEmpty()) {
            $this->command->warn('No divisi found. Please seed divisi first.');
            return;
        }

        // Get first divisi for company-wide positions (or create a general divisi)
        $firstDivisi = $divisis->first();

        // Company-wide positions
        $companyPositions = [
            [
                'kode_jabatan' => 'DIRUT',
                'nama_jabatan' => 'Direktur Utama',
                'deskripsi' => 'Pimpinan tertinggi perusahaan',
                'level' => 1,
                'divisi_id' => $firstDivisi->id,
            ],
            [
                'kode_jabatan' => 'DIRKEU',
                'nama_jabatan' => 'Direktur Keuangan',
                'deskripsi' => 'Pimpinan bidang keuangan',
                'level' => 2,
                'divisi_id' => $firstDivisi->id,
            ],
        ];

        foreach ($companyPositions as $position) {
            JobPosition::firstOrCreate(
                ['kode_jabatan' => $position['kode_jabatan']],
                [
                    'nama_jabatan' => $position['nama_jabatan'],
                    'deskripsi' => $position['deskripsi'],
                    'level' => $position['level'],
                    'divisi_id' => $position['divisi_id'],
                    'is_active' => true,
                ]
            );
            $this->command->info("Created/Updated position {$position['nama_jabatan']}");
        }

        // Division-specific positions
        $divisionPositionTemplates = [
            [
                'kode_jabatan' => 'KADIV',
                'nama_jabatan' => 'Kepala Divisi',
                'deskripsi' => 'Pimpinan divisi',
                'level' => 3,
            ],
            [
                'kode_jabatan' => 'STAFDIV',
                'nama_jabatan' => 'Staff Divisi',
                'deskripsi' => 'Staf pelaksana divisi',
                'level' => 4,
            ],
        ];

        foreach ($divisis as $divisi) {
            foreach ($divisionPositionTemplates as $template) {
                $kodeJabatan = "{$template['kode_jabatan']}_{$divisi->kode_divisi}";

                JobPosition::firstOrCreate(
                    ['kode_jabatan' => $kodeJabatan],
                    [
                        'divisi_id' => $divisi->id,
                        'nama_jabatan' => $template['nama_jabatan'],
                        'deskripsi' => $template['deskripsi'],
                        'level' => $template['level'],
                        'is_active' => true,
                    ]
                );
            }
            $this->command->info("Created division positions for {$divisi->nama_divisi}");
        }

        // Finance department specific staff
        $keuanganDivisi = Divisi::where('kode_divisi', 'like', '%keuangan%')
            ->orWhere('nama_divisi', 'like', '%keuangan%')
            ->first();

        if ($keuanganDivisi) {
            JobPosition::firstOrCreate(
                ['kode_jabatan' => 'STAFKEU'],
                [
                    'divisi_id' => $keuanganDivisi->id,
                    'nama_jabatan' => 'Staff Keuangan',
                    'deskripsi' => 'Staf administrasi keuangan',
                    'level' => 4,
                    'is_active' => true,
                ]
            );
            $this->command->info("Created STAFKEU position for {$keuanganDivisi->nama_divisi}");
        }
    }
}
