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
        // TODO: Calculate used amount from pengajuan_danas once the table structure is updated
        // For now, return 0
        return 0;
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
