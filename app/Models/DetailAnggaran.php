<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAnggaran extends Model
{
    protected $fillable = [
        'sub_program_id',
        'nama_detail',
        'deskripsi',
        'frekuensi',
        'jumlah_periode',
        'nominal_per_periode',
        'total_nominal',
        'satuan',
        'tanggal_mulai_custom',
        'status',
        'created_by',
    ];

    protected $casts = [
        'nominal_per_periode' => 'decimal:2',
        'total_nominal' => 'decimal:2',
        'tanggal_mulai_custom' => 'date',
    ];

    /**
     * Get the sub program that owns the detail anggaran.
     */
    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class);
    }

    /**
     * Get the estimasi pengeluarans for this detail anggaran.
     */
    public function estimasiPengeluarans()
    {
        return $this->hasMany(EstimasiPengeluaran::class)->orderBy('urutan_periode');
    }

    /**
     * Get the user who created the detail anggaran.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate total nominal from periode.
     */
    public function calculateTotalNominal()
    {
        return $this->jumlah_periode * $this->nominal_per_periode;
    }

    /**
     * Generate estimasi pengeluarans based on frekuensi and jumlah_periode.
     */
    public function generateEstimasiPengeluarans()
    {
        // Delete existing estimasi
        $this->estimasiPengeluarans()->delete();

        $estimasi = [];

        // Get start date from custom date or from periode anggaran
        if ($this->tanggal_mulai_custom) {
            $startDate = \Carbon\Carbon::parse($this->tanggal_mulai_custom)->startOfMonth();
        } else {
            // Get periode anggaran from sub program -> program kerja
            $periodeAnggaran = $this->subProgram?->programKerja?->periodeAnggaran;
            if ($periodeAnggaran && $periodeAnggaran->tanggal_mulai_penggunaan_anggaran) {
                $startDate = \Carbon\Carbon::parse($periodeAnggaran->tanggal_mulai_penggunaan_anggaran)->startOfMonth();
            } else {
                $startDate = now()->startOfMonth();
            }
        }

        for ($i = 1; $i <= $this->jumlah_periode; $i++) {
            $tanggal = $this->calculateTanggal($startDate, $i);

            $estimasi[] = [
                'detail_anggaran_id' => $this->id,
                'urutan_periode' => $i,
                'tanggal_rencana_realisasi' => $tanggal,
                'nominal_rencana' => $this->nominal_per_periode,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        EstimasiPengeluaran::insert($estimasi);
    }

    /**
     * Calculate tanggal based on frekuensi.
     */
    private function calculateTanggal($startDate, $periode)
    {
        return match($this->frekuensi) {
            'bulanan' => $startDate->copy()->addMonths($periode - 1),
            'triwulan' => $startDate->copy()->addMonths(($periode - 1) * 3),
            'semesteran' => $startDate->copy()->addMonths(($periode - 1) * 6),
            'tahunan' => $startDate->copy()->addYears($periode - 1),
            'sekali' => $startDate,
            default => $startDate,
        };
    }
}
