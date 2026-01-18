<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PerencanaanPenerimaan;

class PerencanaanPenerimaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodes = [1, 2];
        $sumbers = [1, 2, 3];
        $divisis = [1, 2, 3, 4, 5];
        $sumberDanaLabels = [
            1 => 'APBD',
            2 => 'APBN',
            3 => 'Lainnya'
        ];

        $urasians = [
            'Pendapatan dari sewa mobil operasional',
            'Pendapatan dari sewa gedung aula',
            'Pendapatan dari jasa konsultasi',
            'Pendapatan dari hibah pemerintah',
            'Pendapatan dari sumbangan donatur',
            'Pendapatan dari kerjasama BUMN',
            'Pendapatan dari investasi deposito',
            'Pendapatan dari bunga bank',
            'Pendapatan dari sewa peralatan',
            'Pendapatan dari penjualan aset',
            'Pendapatan dari biaya pendaftaran',
            'Pendapatan dari kursus pelatihan',
            'Pendapatan dari seminar workshop',
            'Pendapatan dari royalti hak cipta',
            'Pendapatan dari sewa lahan parkir',
            'Pendapatan dari jasa administrasi',
            'Pendapatan dari penjualan publikasi',
            'Pendapatan dari lisensi software',
            'Pendapatan dari sponsorship event',
            'Pendapatan dari kontrak proyek',
            'Pendapatan dari fee layanan',
            'Pendapatan dari dividen saham',
            'Pendapatan dari sewa ruang kantor',
            'Pendapatan dari jasa audit internal',
            'Pendapatan dari program kolaborasi',
            'Pendapatan dari penjualan merchandise',
        ];

        for ($i = 0; $i < 25; $i++) {
            $jumlah = rand(5000000, 50000000);
            $sumberDanaId = $sumbers[array_rand($sumbers)];
            $perkiraan = [];

            for ($m = 0; $m < 12; $m++) {
                $date = now()->startOfYear()->addMonths($m);
                $bulan = $date->format('Y-m');
                $perkiraan[$bulan] = floor($jumlah / 12);
            }

            PerencanaanPenerimaan::create([
                'periode_anggaran_id' => $periodes[array_rand($periodes)],
                'divisi_id' => $divisis[array_rand($divisis)],
                'sumber_dana_id' => $sumberDanaId,
                'kode_rekening' => '4.' . rand(100, 999) . '.' . rand(100, 999),
                'uraian' => $urasians[$i],
                'jumlah_estimasi' => $jumlah,
                'perkiraan_bulanan' => $perkiraan,
                'sumber_dana' => $sumberDanaLabels[$sumberDanaId],
                'tanggal_rencana' => now()->addDays(rand(1, 30)),
                'catatan' => 'Catatan untuk ' . $urasians[$i],
                'created_by' => 1,
            ]);
        }

        $this->command->info('Berhasil menambahkan 25 data Perencanaan Penerimaan');
    }
}
