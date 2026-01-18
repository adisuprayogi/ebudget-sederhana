<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PencatatanPenerimaan;
use App\Models\PeriodeAnggaran;
use App\Models\SumberDana;
use App\Models\PerencanaanPenerimaan;

class PencatatanPenerimaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get data references
        $periodes = PeriodeAnggaran::pluck('id')->toArray();
        $sumberDanas = SumberDana::where('is_active', true)->pluck('id')->toArray();
        $perencanaans = PerencanaanPenerimaan::pluck('id')->toArray();

        // Various uraian templates
        $uraianTemplates = [
            'Penerimaan {jenis} dari {sumber}',
            'Pembayaran {jenis} bulan {bulan}',
            'Pendapatan {jenis} periode {periode}',
            'Hasil {jenis} dari {sumber}',
            'Setoran {jenis} tanggal {tanggal}',
            'Pemasukan {jenis} triwulan {triwulan}',
            'Dana {jenis} dari {sumber}',
            'Penerimaan {jenis} {keterangan}',
        ];

        $jenisPemasukan = [
            'Sewa Gedung', 'Sewa Mobil', 'Sewa Peralatan', 'Jasa Konsultasi',
            'Hibah Pemerintah', 'Sumbangan Donatur', 'Kerjasama BUMN',
            'Investasi Deposito', 'Bunga Bank', 'Dividen Saham',
            'Penjualan Aset', 'Iklan Digital', 'Kursus Pelatihan',
            'Seminar Workshop', 'Royalti Hak Cipta', 'Lisensi Software',
            'Sponsorship Event', 'Fee Layanan', 'Jasa Audit',
            'Jasa Kebersihan', 'Jasa Keamanan', 'Sewa Ruang Kantor',
            'Sewa LCD Proyektor', 'Sewa Sound System', 'Sewa AC Portable',
            'Sewa Genset', 'Sewa Tenda Pesta', 'Sewa Kursi Lipat',
            'Jasa Fotografi', 'Jasa Penerjemahan', 'Jasa Desain Grafis',
            'Penjualan Buku', 'Penjualan Merchandise', 'Penjualan Tiket',
            'Biaya Pendaftaran', 'Biaya Sertifikasi', 'Keanggotaan Asosiasi',
            'Bantuan CSR', 'Dana Bagi Hasil', 'Dana Alokasi',
            'Pinjaman Daerah', 'Hasil Lelang', 'Hasil Kerjasama',
        ];

        $sumberList = [
            'PT Mitra Sejahtera', 'CV Maju Jaya', 'UD Berkah Abadi',
            'Pemerintah Provinsi', 'Pemerintah Kota', 'Donatur Tetap',
            'Bank BCA', 'Bank Mandiri', 'Investor Luar Negeri',
            'Mitra Kerjasama', 'Sponsor Utama', 'PT Teknologi Indonesia',
            'CV Media Digital', 'Yayasan Amal', 'BUMN Persero',
            'PT Konsultasi Prima', 'CV Training Center', 'Event Organizer',
            'Masyarakat Umum', 'Alumni', 'Orang Tua Siswa',
        ];

        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $triwulanList = ['I', 'II', 'III', 'IV'];

        $createdCount = 0;
        $targetCount = 100;

        // Generate records for multiple years
        for ($year = 2024; $year <= 2026; $year++) {
            foreach ($bulanList as $index => $bulan) {
                if ($createdCount >= $targetCount) {
                    break 2;
                }

                // Create 3-4 records per month
                $recordsPerMonth = rand(3, 4);

                for ($i = 0; $i < $recordsPerMonth; $i++) {
                    if ($createdCount >= $targetCount) {
                        break 2;
                    }

                    // Generate random date within the month
                    $day = rand(1, 28);
                    $tanggalPenerimaan = sprintf('%04d-%02d-%02d', $year, $index + 1, $day);

                    // Select appropriate periode based on year
                    if ($year == 2026) {
                        $periodeId = 1; // Anggaran 2026
                    } elseif ($year == 2025) {
                        $periodeId = 2; // Anggaran 2025
                    } else {
                        $periodeId = $periodes[array_rand($periodes)];
                    }

                    // Generate uraian
                    $template = $uraianTemplates[array_rand($uraianTemplates)];
                    $jenis = $jenisPemasukan[array_rand($jenisPemasukan)];
                    $sumber = $sumberList[array_rand($sumberList)];
                    $triwulan = $triwulanList[array_rand($triwulanList)];

                    $uraian = str_replace(
                        ['{jenis}', '{sumber}', '{bulan}', '{periode}', '{tanggal}', '{triwulan}', '{keterangan}'],
                        [$jenis, $sumber, $bulan, $year, "$day $bulan", "ke-$triwulan", 'telah diterima penuh'],
                        $template
                    );

                    // Random amount between 5 million and 100 million
                    $jumlahDiterima = rand(5000000, 100000000);

                    // Randomly decide if this has a perencanaan reference (50% chance)
                    $perencanaanId = (rand(1, 2) == 1) ? $perencanaans[array_rand($perencanaans)] : null;

                    PencatatanPenerimaan::create([
                        'periode_anggaran_id' => $periodeId,
                        'sumber_dana_id' => $sumberDanas[array_rand($sumberDanas)],
                        'perencanaan_penerimaan_id' => $perencanaanId,
                        'tanggal_penerimaan' => $tanggalPenerimaan,
                        'uraian' => $uraian,
                        'jumlah_diterima' => $jumlahDiterima,
                        'bukti_penerimaan' => null,
                        'created_by' => 1,
                    ]);

                    $createdCount++;
                }
            }
        }

        $this->command->info("Berhasil menambahkan {$createdCount} data Pencatatan Penerimaan");
    }
}
