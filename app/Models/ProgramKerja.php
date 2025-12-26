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
        return $this->pagu_anggaran - $this->total_detail_anggaran;
    }

    /**
     * Get percentage of pagu used.
     */
    public function getPersentaseTerpakaiAttribute()
    {
        if ($this->pagu_anggaran > 0) {
            return round(($this->total_detail_anggaran / $this->pagu_anggaran) * 100, 1);
        }
        return 0;
    }
}
