<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RekeningPerusahaan;

class RekeningPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rekenings = [
            [
                'bank_id' => 1, // BCA
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Jakarta Pusat',
                'mata_uang' => 'IDR',
                'saldo_awal' => 50000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 2, // Mandiri
                'nomor_rekening' => '987654321098',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Gedung Mandiri',
                'mata_uang' => 'IDR',
                'saldo_awal' => 75000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 3, // BNI
                'nomor_rekening' => '4567890123',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BNI Sudirman',
                'mata_uang' => 'IDR',
                'saldo_awal' => 60000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 4, // BRI
                'nomor_rekening' => '012345678901',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BRI Thamrin',
                'mata_uang' => 'IDR',
                'saldo_awal' => 80000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 5, // CIMB Niaga
                'nomor_rekening' => '8000123456',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC CIMB Niaga Plaza',
                'mata_uang' => 'IDR',
                'saldo_awal' => 45000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 6, // Maybank
                'nomor_rekening' => '5555666677',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Maybank Tower',
                'mata_uang' => 'IDR',
                'saldo_awal' => 55000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 7, // Permata
                'nomor_rekening' => '1111222233',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Permata Kuningan',
                'mata_uang' => 'IDR',
                'saldo_awal' => 40000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 8, // Danamon
                'nomor_rekening' => '9999888877',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Danamon Sudirman',
                'mata_uang' => 'IDR',
                'saldo_awal' => 70000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 9, // UOB
                'nomor_rekening' => '3333444455',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC UOB Plaza',
                'mata_uang' => 'IDR',
                'saldo_awal' => 65000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 10, // OCBC NISP
                'nomor_rekening' => '6666777788',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC OCBC NISP',
                'mata_uang' => 'IDR',
                'saldo_awal' => 52000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 11, // Panin
                'nomor_rekening' => '8888999900',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC Panin Tower',
                'mata_uang' => 'IDR',
                'saldo_awal' => 48000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 12, // BTPN
                'nomor_rekening' => '2222333344',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BTPN Sinaya',
                'mata_uang' => 'IDR',
                'saldo_awal' => 58000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 13, // BPD Jateng
                'nomor_rekening' => '01234567000',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BPD Jateng Semarang',
                'mata_uang' => 'IDR',
                'saldo_awal' => 35000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 14, // BPD Jatim
                'nomor_rekening' => '09876543210',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BPD Jatim Surabaya',
                'mata_uang' => 'IDR',
                'saldo_awal' => 42000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'bank_id' => 15, // BPD Jabar (BJB)
                'nomor_rekening' => '5555444433',
                'atas_nama' => 'PT E-Budget Indonesia',
                'cabang' => 'KC BJB Bandung',
                'mata_uang' => 'IDR',
                'saldo_awal' => 38000000,
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($rekenings as $data) {
            RekeningPerusahaan::create([
                'bank_id' => $data['bank_id'],
                'nomor_rekening' => $data['nomor_rekening'],
                'atas_nama' => $data['atas_nama'],
                'cabang' => $data['cabang'],
                'mata_uang' => $data['mata_uang'],
                'saldo_awal' => $data['saldo_awal'],
                'is_default' => $data['is_default'],
                'is_active' => $data['is_active'],
                'catatan' => 'Rekening operasional perusahaan',
                'created_by' => 'Superadmin Sistem',
                'updated_by' => 'Superadmin Sistem',
            ]);
        }

        $this->command->info('Berhasil menambahkan 15 data Rekening Perusahaan');
    }
}
