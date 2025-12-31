<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    protected $fillable = [
        'kode_program',
        'nama_program',
        'deskripsi',
        'divisi_id',
        'periode_anggaran_id',
        'pagu_anggaran',
        'target_output',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected $with = ['divisi', 'periodeAnggaran'];

    /**
     * Get the divisi that owns the program kerja.
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Get the periode anggaran for this program kerja.
     */
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class, 'periode_anggaran_id');
    }

    /**
     * Get the sub programs for this program kerja.
     */
    public function subPrograms()
    {
        return $this->hasMany(SubProgram::class);
    }

    /**
     * Get the user who created the program kerja.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the pengajuans for this program kerja.
     */
    public function pengajuans()
    {
        return $this->hasMany(\App\Models\PengajuanDana::class);
    }

    /**
     * Get all detail anggarans through sub programs.
     */
    public function detailAnggarans()
    {
        return $this->hasManyThrough(DetailAnggaran::class, SubProgram::class);
    }

    /**
     * Get calculated pagu from all detail anggarans through sub programs.
     * This is the real-time pagu based on actual detail anggaran.
     */
    public function getCalculatedPaguAttribute()
    {
        return $this->detailAnggarans()->sum('total_nominal');
    }

    /**
     * Get total pagu from all sub programs.
     */
    public function getTotalSubProgramPaguAttribute()
    {
        return $this->subPrograms()->sum('pagu_anggaran');
    }

    /**
     * Get total detail anggaran from all sub programs.
     */
    public function getTotalDetailAnggaranAttribute()
    {
        return $this->detailAnggarans()->sum('total_nominal');
    }

    /**
     * Calculate sisa pagu.
     */
    public function getSisaPaguAttribute()
    {
        return $this->calculated_pagu - $this->total_detail_anggaran;
    }

    /**
     * Get percentage of pagu used.
     */
    public function getPersentaseTerpakaiAttribute()
    {
        $penetapanPagu = \App\Models\PenetapanPagu::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->first();

        if ($penetapanPagu && $penetapanPagu->jumlah_pagu > 0) {
            return round(($this->calculated_pagu / $penetapanPagu->jumlah_pagu) * 100, 1);
        }
        return 0;
    }

    /**
     * Check if adding a new detail anggaran would exceed penetapan pagu.
     */
    public function canAddDetailAnggaran($nominal)
    {
        $penetapanPagu = \App\Models\PenetapanPagu::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->first();

        if (!$penetapanPagu) {
            return false;
        }

        // Calculate total for all programs in this divisi and periode
        $totalAllPrograms = \App\Models\ProgramKerja::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->where('id', '!=', $this->id)
            ->with('detailAnggarans')
            ->get()
            ->sum(function ($program) {
                return $program->detailAnggarans->sum('total_nominal');
            });

        // Add current program's calculated pagu and new nominal
        $newTotal = $totalAllPrograms + $this->calculated_pagu + $nominal;

        return $newTotal <= $penetapanPagu->jumlah_pagu;
    }

    /**
     * Get remaining pagu from penetapan pagu for this divisi and periode.
     */
    public function getRemainingPaguAttribute()
    {
        $penetapanPagu = \App\Models\PenetapanPagu::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->first();

        if (!$penetapanPagu) {
            return 0;
        }

        // Calculate total for all programs in this divisi and periode
        $totalAllPrograms = \App\Models\ProgramKerja::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->with('detailAnggarans')
            ->get()
            ->sum(function ($program) {
                return $program->detailAnggarans->sum('total_nominal');
            });

        return $penetapanPagu->jumlah_pagu - $totalAllPrograms;
    }
}
