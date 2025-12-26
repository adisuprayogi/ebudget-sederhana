<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = [
            [
                'kode_divisi' => 'IT',
                'nama_divisi' => 'Divisi Teknologi Informasi',
                'description' => 'Divisi yang menangani infrastruktur IT dan pengembangan sistem',
                'total_pagu' => 500000000,
                'terpakai' => 0,
                'sisa_pagu' => 500000000,
                'is_active' => true,
            ],
            [
                'kode_divisi' => 'HR',
                'nama_divisi' => 'Divisi Sumber Daya Manusia',
                'description' => 'Divisi yang menangani pengelolaan SDM dan kepegawaian',
                'total_pagu' => 300000000,
                'terpakai' => 0,
                'sisa_pagu' => 300000000,
                'is_active' => true,
            ],
            [
                'kode_divisi' => 'FIN',
                'nama_divisi' => 'Divisi Keuangan',
                'description' => 'Divisi yang menangani keuangan dan akuntansi',
                'total_pagu' => 400000000,
                'terpakai' => 0,
                'sisa_pagu' => 400000000,
                'is_active' => true,
            ],
            [
                'kode_divisi' => 'OPS',
                'nama_divisi' => 'Divisi Operasional',
                'description' => 'Divisi yang menangani operasional perusahaan',
                'total_pagu' => 600000000,
                'terpakai' => 0,
                'sisa_pagu' => 600000000,
                'is_active' => true,
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }
    }
}
