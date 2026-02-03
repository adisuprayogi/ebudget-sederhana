<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeAnggaran;
use App\Models\ProgramKerja;
use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\Refund;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Role;
use Carbon\Carbon;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds for simulation/demo purposes.
     * Creates sample data for screenshots and manual testing.
     */
    public function run(): void
    {
        $this->command->info('Starting simulation data seeding...');

        // Get or create users
        $staffKeuangan = User::where('email', 'staff.keuangan@example.com')->first();
        $direkturKeuangan = User::where('email', 'direktur@example.com')->first();
        $kepalaIT = User::where('email', 'kepala.it@example.com')->first();
        $staff1IT = User::where('email', 'staff1.it@example.com')->first();

        if (!$staffKeuangan || !$direkturKeuangan || !$kepalaIT || !$staff1IT) {
            $this->command->error('Required users not found. Please run UserSeeder first.');
            return;
        }

        $itDivisi = Divisi::where('kode_divisi', 'IT')->first();

        // Create Periode Anggaran for simulation
        $this->createPeriodeAnggaran($staffKeuangan);

        // Get active periode
        $activePeriode = PeriodeAnggaran::where('status', 'active')->first();

        if (!$activePeriode) {
            $this->command->error('No active periode found.');
            return;
        }

        // Create Program Kerja for IT Division
        $this->createProgramKerja($kepalaIT, $itDivisi, $activePeriode);

        // Create Pengajuan Dana with various statuses
        $this->createPengajuanDana($staff1IT, $itDivisi, $activePeriode, $kepalaIT);

        // Create Pencairan Dana
        $this->createPencairanDana($staffKeuangan);

        // Create LPJ
        $this->createLPJ($staff1IT);

        // Create Refund
        $this->createRefund($staff1IT);

        $this->command->info('Simulation data seeding completed!');
    }

    private function createPeriodeAnggaran($user): void
    {
        $this->command->info('Creating Periode Anggaran...');

        // Draft periode for next year
        PeriodeAnggaran::firstOrCreate(
            ['kode_periode' => 'PA202701'],
            [
                'nama_periode' => 'Anggaran Tahun 2027',
                'tahun_anggaran' => '2027',
                'tanggal_mulai_perencanaan_anggaran' => Carbon::create(2026, 10, 1),
                'tanggal_selesai_perencanaan_anggaran' => Carbon::create(2027, 1, 31),
                'tanggal_mulai_penggunaan_anggaran' => Carbon::create(2027, 2, 1),
                'tanggal_selesai_penggunaan_anggaran' => Carbon::create(2027, 12, 31),
                'status' => 'draft',
                'deskripsi' => 'Perencanaan anggaran tahun 2027 - masih dalam tahap perencanaan',
                'created_by' => $user->id,
            ]
        );

        // Active periode for current year
        PeriodeAnggaran::firstOrCreate(
            ['kode_periode' => 'PA202601'],
            [
                'nama_periode' => 'Anggaran Tahun 2026',
                'tahun_anggaran' => '2026',
                'tanggal_mulai_perencanaan_anggaran' => Carbon::create(2025, 10, 1),
                'tanggal_selesai_perencanaan_anggaran' => Carbon::create(2025, 12, 31),
                'tanggal_mulai_penggunaan_anggaran' => Carbon::create(2026, 1, 1),
                'tanggal_selesai_penggunaan_anggaran' => Carbon::create(2026, 12, 31),
                'status' => 'active',
                'deskripsi' => 'Periode anggaran tahun 2026 untuk operasional perusahaan',
                'approved_by' => $user->id,
                'approved_at' => Carbon::create(2026, 1, 1),
                'created_by' => $user->id,
            ]
        );

        // Closed periode for previous year
        PeriodeAnggaran::firstOrCreate(
            ['kode_periode' => 'PA202501'],
            [
                'nama_periode' => 'Anggaran Tahun 2025',
                'tahun_anggaran' => '2025',
                'tanggal_mulai_perencanaan_anggaran' => Carbon::create(2024, 10, 1),
                'tanggal_selesai_perencanaan_anggaran' => Carbon::create(2024, 12, 31),
                'tanggal_mulai_penggunaan_anggaran' => Carbon::create(2025, 1, 1),
                'tanggal_selesai_penggunaan_anggaran' => Carbon::create(2025, 12, 31),
                'status' => 'closed',
                'deskripsi' => 'Periode anggaran tahun 2025 (ditutup)',
                'approved_by' => $user->id,
                'approved_at' => Carbon::create(2025, 1, 1),
                'created_by' => $user->id,
            ]
        );

        $this->command->info('  - Created 3 periode anggaran (draft, active, closed)');
    }

    private function createProgramKerja($user, $divisi, $periode): void
    {
        $this->command->info('Creating Program Kerja...');

        $programs = [
            [
                'kode_program' => 'PROG-IT-001',
                'nama_program' => 'Pengembangan Sistem Informasi Enterprise',
                'target_output' => 'Sistem ERP terintegrasi',
                'status' => 'active',
            ],
            [
                'kode_program' => 'PROG-IT-002',
                'nama_program' => 'Maintenance Infrastruktur IT',
                'target_output' => 'Uptime 99.9%',
                'status' => 'active',
            ],
            [
                'kode_program' => 'PROG-IT-003',
                'nama_program' => 'Training & Development Staff IT',
                'target_output' => '15 staff tersertifikasi',
                'status' => 'active',
            ],
            [
                'kode_program' => 'PROG-IT-004',
                'nama_program' => 'Pengadaan Peralatan IT',
                'target_output' => '50 unit peralatan baru',
                'status' => 'inactive',
            ],
        ];

        foreach ($programs as $program) {
            ProgramKerja::firstOrCreate(
                ['kode_program' => $program['kode_program']],
                [
                    'nama_program' => $program['nama_program'],
                    'divisi_id' => $divisi->id,
                    'periode_anggaran_id' => $periode->id,
                    'target_output' => $program['target_output'],
                    'tanggal_mulai' => Carbon::create(2026, 1, 1),
                    'tanggal_selesai' => Carbon::create(2026, 12, 31),
                    'status' => $program['status'],
                    'deskripsi' => 'Program kerja untuk ' . $program['nama_program'],
                    'created_by' => $user->id,
                ]
            );
        }

        $this->command->info('  - Created 4 program kerja');
    }

    private function createPengajuanDana($user, $divisi, $periode, $kepala): void
    {
        $this->command->info('Creating Pengajuan Dana...');

        $program1 = ProgramKerja::where('kode_program', 'PROG-IT-001')->first();
        $program2 = ProgramKerja::where('kode_program', 'PROG-IT-002')->first();

        // Approved pengajuan (has pencairan)
        $pengajuan1 = PengajuanDana::firstOrCreate(
            ['nomor_pengajuan' => 'PNG-2026-0001'],
            [
                'judul_pengajuan' => 'Pembuatan Aplikasi ERP Modul Finance',
                'jenis_pengajuan' => 'pengadaan',
                'program_kerja_id' => $program1->id,
                'divisi_id' => $divisi->id,
                'periode_anggaran_id' => $periode->id,
                'total_pengajuan' => 50000000,
                'deskripsi' => 'Pengembangan aplikasi ERP modul Finance dengan fitur budgeting, cashflow, dan reporting',
                'penerima_manfaat_type' => 'external',
                'penerima_manfaat_name' => 'Departemen Keuangan',
                'status' => 'cair',
                'created_by' => $user->id,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 10),
            ]
        );

        // Add details to pengajuan 1
        if (!$pengajuan1->details()->count()) {
            $pengajuan1->details()->createMany([
                [
                    'uraian' => 'Jasa Pembuatan Aplikasi',
                    'volume' => 1,
                    'satuan' => 'project',
                    'harga_satuan' => 35000000,
                    'subtotal' => 35000000,
                ],
                [
                    'uraian' => 'Server Development',
                    'volume' => 1,
                    'satuan' => 'unit',
                    'harga_satuan' => 10000000,
                    'subtotal' => 10000000,
                ],
                [
                    'uraian' => 'Lisensi Software',
                    'volume' => 1,
                    'satuan' => 'license',
                    'harga_satuan' => 5000000,
                    'subtotal' => 5000000,
                ],
            ]);
        }

        // Pending approval pengajuan
        $pengajuan2 = PengajuanDana::firstOrCreate(
            ['nomor_pengajuan' => 'PNG-2026-0002'],
            [
                'judul_pengajuan' => 'Maintenance Server & Network',
                'jenis_pengajuan' => 'kegiatan',
                'program_kerja_id' => $program2->id,
                'divisi_id' => $divisi->id,
                'periode_anggaran_id' => $periode->id,
                'total_pengajuan' => 25000000,
                'deskripsi' => 'Maintenance rutin server dan network infrastruktur termasuk upgrade komponen',
                'penerima_manfaat_type' => 'internal',
                'penerima_manfaat_name' => 'Seluruh divisi',
                'status' => 'menunggu_approval',
                'created_by' => $user->id,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 25),
            ]
        );

        if (!$pengajuan2->details()->count()) {
            $pengajuan2->details()->createMany([
                [
                    'uraian' => 'Jasa Maintenance Vendor',
                    'volume' => 1,
                    'satuan' => 'kontrak',
                    'harga_satuan' => 15000000,
                    'subtotal' => 15000000,
                ],
                [
                    'uraian' => 'Sparepart Server',
                    'volume' => 10,
                    'satuan' => 'unit',
                    'harga_satuan' => 1000000,
                    'subtotal' => 10000000,
                ],
            ]);
        }

        // Rejected pengajuan
        $pengajuan3 = PengajuanDana::firstOrCreate(
            ['nomor_pengajuan' => 'PNG-2026-0003'],
            [
                'judul_pengajuan' => 'Pembelian Laptop Baru',
                'jenis_pengajuan' => 'pengadaan',
                'program_kerja_id' => $program1->id,
                'divisi_id' => $divisi->id,
                'periode_anggaran_id' => $periode->id,
                'total_pengajuan' => 150000000,
                'deskripsi' => 'Pembelian 10 unit laptop high-spec untuk tim development',
                'penerima_manfaat_type' => 'pengaju',
                'penerima_manfaat_name' => 'Tim Development IT',
                'status' => 'ditolak',
                'created_by' => $user->id,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 18),
            ]
        );

        // Draft pengajuan
        $pengajuan4 = PengajuanDana::firstOrCreate(
            ['nomor_pengajuan' => 'PNG-2026-0004'],
            [
                'judul_pengajuan' => 'Biaya Konsumsi Rapat Koordinasi',
                'jenis_pengajuan' => 'konsumi',
                'program_kerja_id' => $program2->id,
                'divisi_id' => $divisi->id,
                'periode_anggaran_id' => $periode->id,
                'total_pengajuan' => 3000000,
                'deskripsi' => 'Konsumsi untuk rapat koordinasi antar divisi',
                'penerima_manfaat_type' => 'internal',
                'penerima_manfaat_name' => 'Peserta rapat',
                'status' => 'draft',
                'created_by' => $user->id,
                'tanggal_pengajuan' => Carbon::create(2026, 2, 1),
            ]
        );

        // Completed pengajuan (has LPJ)
        $pengajuan5 = PengajuanDana::firstOrCreate(
            ['nomor_pengajuan' => 'PNG-2026-0005'],
            [
                'judul_pengajuan' => 'Training Sertifikasi AWS Solution Architect',
                'jenis_pengajuan' => 'kegiatan',
                'program_kerja_id' => $program1->id,
                'divisi_id' => $divisi->id,
                'periode_anggaran_id' => $periode->id,
                'total_pengajuan' => 15000000,
                'deskripsi' => 'Training dan sertifikasi AWS Solution Architect untuk 2 staff IT',
                'penerima_manfaat_type' => 'pegawai',
                'penerima_manfaat_name' => 'Staff IT (2 orang)',
                'status' => 'selesai',
                'created_by' => $user->id,
                'tanggal_pengajuan' => Carbon::create(2026, 1, 5),
            ]
        );

        if (!$pengajuan5->details()->count()) {
            $pengajuan5->details()->createMany([
                [
                    'uraian' => 'Biaya Kursus & Sertifikasi',
                    'volume' => 2,
                    'satuan' => 'orang',
                    'harga_satuan' => 6500000,
                    'subtotal' => 13000000,
                ],
                [
                    'uraian' => 'Akomodasi & Transport',
                    'volume' => 1,
                    'satuan' => 'paket',
                    'harga_satuan' => 2000000,
                    'subtotal' => 2000000,
                ],
            ]);
        }

        $this->command->info('  - Created 5 pengajuan dana (cair, pending, ditolak, draft, selesai)');
    }

    private function createPencairanDana($user): void
    {
        $this->command->info('Creating Pencairan Dana...');

        $pengajuan1 = PengajuanDana::where('nomor_pengajuan', 'PNG-2026-0001')->first();
        $pengajuan5 = PengajuanDana::where('nomor_pengajuan', 'PNG-2026-0005')->first();

        // Successful disbursement for pengajuan 1
        PencairanDana::firstOrCreate(
            ['nomor_pencairan' => 'PNC-2026-0001'],
            [
                'pengajuan_dana_id' => $pengajuan1->id,
                'jumlah_pencairan' => 50000000,
                'metode_pencairan' => 'transfer',
                'tanggal_pencairan' => Carbon::create(2026, 1, 20),
                'nama_bank' => 'BCA',
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'Vendor IT Solutions',
                'catatan' => 'Pembayaran DP proyek ERP - Tahap 1',
                'status' => 'completed',
                'bukti_pencairan' => 'bukti_transfer_erp.png',
                'created_by' => $user->id,
            ]
        );

        // Successful disbursement for pengajuan 5 (completed)
        PencairanDana::firstOrCreate(
            ['nomor_pencairan' => 'PNC-2026-0002'],
            [
                'pengajuan_dana_id' => $pengajuan5->id,
                'jumlah_pencairan' => 15000000,
                'metode_pencairan' => 'transfer',
                'tanggal_pencairan' => Carbon::create(2026, 1, 15),
                'nama_bank' => 'Mandiri',
                'nomor_rekening' => '0987654321',
                'atas_nama' => 'Staff IT 1',
                'catatan' => 'Biaya training AWS',
                'status' => 'completed',
                'bukti_pencairan' => 'bukti_transfer_training.png',
                'created_by' => $user->id,
            ]
        );

        $this->command->info('  - Created 2 pencairan dana');
    }

    private function createLPJ($user): void
    {
        $this->command->info('Creating LPJ...');

        $pengajuan5 = PengajuanDana::where('nomor_pengajuan', 'PNG-2026-0005')->first();
        $pencairan2 = PencairanDana::where('nomor_pencairan', 'PNC-2026-0002')->first();

        // Approved LPJ
        $lpj = LaporanPertanggungJawaban::firstOrCreate(
            ['nomor_lpj' => 'LPJ-2026-0001'],
            [
                'pengajuan_dana_id' => $pengajuan5->id,
                'pencairan_dana_id' => $pencairan2->id,
                'uraian_kegiatan' => 'Training AWS Solution Architect telah dilaksanakan pada 20-22 Januari 2026 di Jakarta. Dua orang staff IT mengikuti pelatihan intensif selama 3 hari dan berhasil mendapatkan sertifikasi AWS Solution Architect Associate.',
                'total_digunakan' => 14200000,
                'sisa_dana' => 800000,
                'catatan' => 'Sisa dana akan dikembalikan ke kas perusahaan',
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => Carbon::create(2026, 2, 1),
                'created_by' => $user->id,
            ]
        );

        // Add LPJ details
        if (!$lpj->detailLpjs()->count()) {
            $lpj->detailLpjs()->createMany([
                [
                    'uraian' => 'Biaya Kursus & Sertifikasi',
                    'volume_realisasi' => 2,
                    'satuan' => 'orang',
                    'harga_satuan' => 6500000,
                    'subtotal_realisasi' => 13000000,
                    'tanggal_realisasi' => Carbon::create(2026, 1, 20),
                ],
                [
                    'uraian' => 'Akomodasi Hotel (2 malam)',
                    'volume_realisasi' => 2,
                    'satuan' => 'malam',
                    'harga_satuan' => 600000,
                    'subtotal_realisasi' => 600000,
                    'tanggal_realisasi' => Carbon::create(2026, 1, 20),
                ],
            ]);
        }

        $this->command->info('  - Created 1 LPJ (approved)');
    }

    private function createRefund($user): void
    {
        $this->command->info('Creating Refund...');

        $lpj = LaporanPertanggungJawaban::where('nomor_lpj', 'LPJ-2026-0001')->first();
        $direkturKeuangan = User::where('email', 'direktur@example.com')->first();

        // Approved refund
        Refund::firstOrCreate(
            ['nomor_refund' => 'RFD-2026-0001'],
            [
                'lpj_id' => $lpj->id,
                'pengajuan_dana_id' => $lpj->pengajuan_dana_id,
                'pencairan_dana_id' => $lpj->pencairan_dana_id,
                'jumlah_refund' => 800000,
                'alasan_refund' => 'Sisa dana dari training AWS yang tidak terpakai',
                'metode_refund' => 'transfer',
                'rekening_pengirim' => '1234567890',
                'nama_pengirim' => 'Perusahaan',
                'status' => 'processed',
                'tanggal_transfer' => Carbon::create(2026, 2, 5),
                'bukti_transfer' => 'bukti_refund.png',
                'processed_at' => Carbon::create(2026, 2, 5),
                'approved_by' => $direkturKeuangan->id,
                'approved_at' => Carbon::create(2026, 2, 3),
                'created_by' => $user->id,
            ]
        );

        $this->command->info('  - Created 1 refund (processed)');
    }
}
