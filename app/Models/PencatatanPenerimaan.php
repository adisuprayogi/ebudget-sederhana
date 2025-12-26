<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencatatanPenerimaan extends Model
{
    protected $fillable = [
        'periode_anggaran_id',
        'sumber_dana_id',
        'perencanaan_penerimaan_id',
        'tanggal_penerimaan',
        'uraian',
        'jumlah_diterima',
        'bukti_penerimaan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
        'jumlah_diterima' => 'decimal:2',
    ];

    protected $with = ['periodeAnggaran', 'sumberDana', 'perencanaanPenerimaan', 'createdBy'];

    // Relationships
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'sumber_dana_id');
    }

    public function perencanaanPenerimaan()
    {
        return $this->belongsTo(PerencanaanPenerimaan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePeriode($query, $periodeId)
    {
        return $query->where('periode_anggaran_id', $periodeId);
    }

    public function scopeSumberDana($query, $sumberDanaId)
    {
        return $query->where('sumber_dana_id', $sumberDanaId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
    }
}
