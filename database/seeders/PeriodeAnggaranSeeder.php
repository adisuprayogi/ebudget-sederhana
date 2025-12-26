<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeAnggaran;
use App\Models\User;
use Carbon\Carbon;

class PeriodeAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first active user or create default admin
        $admin = User::where('is_active', true)->first();

        if (!$admin) {
            // Create default admin user if none exists
            $admin = User::create([
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
        }

        // Create periode for current year (penggunaan phase)
        $currentYear = date('Y');
        $periode = PeriodeAnggaran::create([
            'kode_periode' => 'PA' . $currentYear . '01',
            'nama_periode' => 'Anggaran Tahun ' . $currentYear,
            'tahun_anggaran' => $currentYear,
            'tanggal_mulai_perencanaan_anggaran' => Carbon::create($currentYear - 1, 10, 1),
            'tanggal_selesai_perencanaan_anggaran' => Carbon::create($currentYear - 1, 12, 31),
            'tanggal_mulai_penggunaan_anggaran' => Carbon::create($currentYear, 1, 1),
            'tanggal_selesai_penggunaan_anggaran' => Carbon::create($currentYear, 12, 31),
            'status' => 'active',
            'deskripsi' => 'Periode anggaran tahun ' . $currentYear . ' untuk semua operasional perusahaan',
            'approved_by' => $admin?->id,
            'approved_at' => now(),
            'created_by' => $admin?->id,
        ]);

        echo "Created periode anggaran: " . $periode->kode_periode . "\n";

        // Create periode for next year (perencangan phase)
        $nextYear = $currentYear + 1;
        $nextPeriode = PeriodeAnggaran::create([
            'kode_periode' => 'PA' . $nextYear . '01',
            'nama_periode' => 'Anggaran Tahun ' . $nextYear,
            'tahun_anggaran' => $nextYear,
            'tanggal_mulai_perencanaan_anggaran' => Carbon::create($currentYear, 10, 1),
            'tanggal_selesai_perencanaan_anggaran' => Carbon::create($nextYear, 1, 31),
            'tanggal_mulai_penggunaan_anggaran' => Carbon::create($nextYear, 2, 1),
            'tanggal_selesai_penggunaan_anggaran' => Carbon::create($nextYear, 12, 31),
            'status' => 'draft',
            'deskripsi' => 'Perencanaan anggaran tahun ' . $nextYear . ' untuk perencanaan program dan alokasi dana',
            'created_by' => $admin?->id,
        ]);

        echo "Created planning periode: " . $nextPeriode->kode_periode . "\n";

        // Create periode for previous year (closed)
        $previousYear = $currentYear - 1;
        if ($previousYear >= 2020) {
            $prevPeriode = PeriodeAnggaran::create([
                'kode_periode' => 'PA' . $previousYear . '01',
                'nama_periode' => 'Anggaran Tahun ' . $previousYear,
                'tahun_anggaran' => $previousYear,
                'tanggal_mulai_perencanaan_anggaran' => Carbon::create($previousYear - 1, 10, 1),
                'tanggal_selesai_perencanaan_anggaran' => Carbon::create($previousYear - 1, 12, 31),
                'tanggal_mulai_penggunaan_anggaran' => Carbon::create($previousYear, 1, 1),
                'tanggal_selesai_penggunaan_anggaran' => Carbon::create($previousYear, 12, 31),
                'status' => 'closed',
                'deskripsi' => 'Periode anggaran tahun ' . $previousYear . ' (ditutup)',
                'approved_by' => $admin?->id,
                'approved_at' => now(),
                'created_by' => $admin?->id,
            ]);

            echo "Created closed periode: " . $prevPeriode->kode_periode . "\n";
        }
    }
}
