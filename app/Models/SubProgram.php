<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProgram extends Model
{
    protected $fillable = [
        'program_kerja_id',
        'kode_sub_program',
        'nama_sub_program',
        'deskripsi',
        'periode_anggaran_id',
        'pagu_anggaran',
        'target_output',
        'status',
        'created_by',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
    ];

    /**
     * Get the program kerja that owns the sub program.
     */
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    /**
     * Get the periode anggaran for this sub program.
     */
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    /**
     * Get the detail anggarans for this sub program.
     */
    public function detailAnggarans()
    {
        return $this->hasMany(DetailAnggaran::class);
    }

    /**
     * Get the user who created the sub program.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get total pagu from all detail anggarans.
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
}
