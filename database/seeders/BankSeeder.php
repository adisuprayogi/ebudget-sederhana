<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['kode_bank' => 'BCA', 'nama_bank' => 'Bank Central Asia', 'singkatan' => 'BCA'],
            ['kode_bank' => 'MANDIRI', 'nama_bank' => 'Bank Mandiri', 'singkatan' => 'Mandiri'],
            ['kode_bank' => 'BNI', 'nama_bank' => 'Bank Negara Indonesia', 'singkatan' => 'BNI'],
            ['kode_bank' => 'BRI', 'nama_bank' => 'Bank Rakyat Indonesia', 'singkatan' => 'BRI'],
            ['kode_bank' => 'CIMB', 'nama_bank' => 'CIMB Niaga', 'singkatan' => 'CIMB Niaga'],
            ['kode_bank' => 'BII', 'nama_bank' => 'Bank Maybank Indonesia', 'singkatan' => 'Maybank'],
            ['kode_bank' => 'PERMATA', 'nama_bank' => 'Bank Permata', 'singkatan' => 'Permata'],
            ['kode_bank' => 'DANAMON', 'nama_bank' => 'Bank Danamon', 'singkatan' => 'Danamon'],
            ['kode_bank' => 'UOB', 'nama_bank' => 'Bank UOB Indonesia', 'singkatan' => 'UOB'],
            ['kode_bank' => 'OCBC', 'nama_bank' => 'Bank OCBC NISP', 'singkatan' => 'OCBC NISP'],
            ['kode_bank' => 'PANIN', 'nama_bank' => 'Bank Panin', 'singkatan' => 'Panin'],
            ['kode_bank' => 'BTPN', 'nama_bank' => 'Bank BTPN', 'singkatan' => 'BTPN'],
            ['kode_bank' => 'BPD_JATENG', 'nama_bank' => 'Bank Jateng', 'singkatan' => 'Bank Jateng'],
            ['kode_bank' => 'BPD_JATIM', 'nama_bank' => 'Bank Jatim', 'singkatan' => 'Bank Jatim'],
            ['kode_bank' => 'BPD_JABAR', 'nama_bank' => 'Bank BJB', 'singkatan' => 'Bank BJB'],
            ['kode_bank' => 'DASHBOARD', 'nama_bank' => 'Bank DKI', 'singkatan' => 'Bank DKI'],
            ['kode_bank' => 'BPD_BALI', 'nama_bank' => 'Bank BPD Bali', 'singkatan' => 'Bank BPD Bali'],
            ['kode_bank' => 'BSI', 'nama_bank' => 'Bank Syariah Indonesia', 'singkatan' => 'BSI'],
            ['kode_bank' => 'MUAMALAT', 'nama_bank' => 'Bank Muamalat', 'singkatan' => 'Muamalat'],
            ['kode_bank' => 'MEGA', 'nama_bank' => 'Bank Mega', 'singkatan' => 'Mega'],
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(
                ['kode_bank' => $bank['kode_bank']],
                [
                    'nama_bank' => $bank['nama_bank'],
                    'singkatan' => $bank['singkatan'],
                    'is_active' => true,
                ]
            );
        }
    }
}
