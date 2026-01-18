<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SumberDana;

class SumberDanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sumberDanas = [
            [
                'kode_sumber' => 'Dana bagi Hasil Pajak',
                'nama_sumber' => 'Dana Bagi Hasil Pajak dari Pusat',
                'deskripsi' => 'Dana bagi hasil pajak yang diterima dari pemerintah pusat',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Dana bagi Hasil SDA',
                'nama_sumber' => 'Dana Bagi Hasil Sumber Daya Alam',
                'deskripsi' => 'Dana bagi hasil sumber daya alam dari pemerintah pusat',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Dana Alokasi Umum',
                'nama_sumber' => 'Dana Alokasi Umum (DAU)',
                'deskripsi' => 'Dana alokasi umum dari pemerintah pusat untuk daerah',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Dana Alokasi Khusus',
                'nama_sumber' => 'Dana Alokasi Khusus (DAK)',
                'deskripsi' => 'Dana alokasi khusus untuk pembangunan infrastruktur',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Pinjaman Daerah',
                'nama_sumber' => 'Pinjaman Daerah',
                'deskripsi' => 'Dana pinjaman yang diperoleh dari lembaga keuangan',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Lelang Aset',
                'nama_sumber' => 'Hasil Lelang Aset Daerah',
                'deskripsi' => 'Pendapatan dari pelelangan aset milik daerah',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Sewa Aset',
                'nama_sumber' => 'Pendapatan Sewa Aset',
                'deskripsi' => 'Pendapatan dari penyewaan aset milik daerah',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Jasa Layanan',
                'nama_sumber' => 'Pendapatan Jasa Layanan',
                'deskripsi' => 'Pendapatan dari jasa layanan yang diberikan',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Hibah Provinsi',
                'nama_sumber' => 'Hibah dari Pemerintah Provinsi',
                'deskripsi' => 'Dana hibah yang diterima dari pemerintah provinsi',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Hibah Kota/Kab',
                'nama_sumber' => 'Hibah dari Pemerintah Kota/Kabupaten',
                'deskripsi' => 'Dana hibah yang diterima dari pemerintah kota/kabupaten',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Bantuan CSR',
                'nama_sumber' => 'Bantuan Corporate Social Responsibility',
                'deskripsi' => 'Dana bantuan dari program CSR perusahaan',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Sumbangan Donatur',
                'nama_sumber' => 'Sumbangan dari Donatur',
                'deskripsi' => 'Dana sumbangan dari para donatur',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Hasil Kerjasama',
                'nama_sumber' => 'Hasil Kerjasama dengan Pihak Ketiga',
                'deskripsi' => 'Pendapatan dari kerjasama dengan pihak ketiga',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Investasi Jangka Pendek',
                'nama_sumber' => 'Hasil Investasi Jangka Pendek',
                'deskripsi' => 'Pendapatan dari investasi jangka pendek',
                'is_active' => true,
            ],
            [
                'kode_sumber' => 'Investasi Jangka Panjang',
                'nama_sumber' => 'Hasil Investasi Jangka Panjang',
                'deskripsi' => 'Pendapatan dari investasi jangka panjang',
                'is_active' => true,
            ],
        ];

        foreach ($sumberDanas as $data) {
            SumberDana::create([
                'kode_sumber' => $data['kode_sumber'],
                'nama_sumber' => $data['nama_sumber'],
                'deskripsi' => $data['deskripsi'],
                'is_active' => $data['is_active'],
                'created_by' => 1,
            ]);
        }

        $this->command->info('Berhasil menambahkan 15 data Sumber Dana');
    }
}
