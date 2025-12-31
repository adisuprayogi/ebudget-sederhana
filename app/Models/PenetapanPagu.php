<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenetapanPagu extends Model
{
    protected $fillable = [
        'divisi_id',
        'periode_anggaran_id',
        'jumlah_pagu',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'jumlah_pagu' => 'decimal:2',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUsedAmountAttribute()
    {
        // Calculate real-time used amount from Program Kerja pagu for this divisi and periode
        return \App\Models\ProgramKerja::where('divisi_id', $this->divisi_id)
            ->where('periode_anggaran_id', $this->periode_anggaran_id)
            ->sum('pagu_anggaran');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->jumlah_pagu - $this->used_amount;
    }

    public function getUsagePercentageAttribute()
    {
        if ($this->jumlah_pagu == 0) return 0;
        return ($this->used_amount / $this->jumlah_pagu) * 100;
    }
}
