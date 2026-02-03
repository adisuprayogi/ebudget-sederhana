<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefundDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_id',
        'lpj_id',
        'jumlah_refund',
    ];

    protected $casts = [
        'jumlah_refund' => 'decimal:2',
    ];

    /**
     * Get the refund that owns the detail.
     */
    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    /**
     * Get the LPJ that owns the detail.
     */
    public function lpj()
    {
        return $this->belongsTo(LaporanPertanggungJawaban::class, 'lpj_id');
    }

    /**
     * Get the pencairan dana through LPJ.
     */
    public function pencairanDana()
    {
        return $this->lpj?->pencairanDana();
    }

    /**
     * Get the pengajuan dana through LPJ.
     */
    public function pengajuanDana()
    {
        return $this->lpj?->pengajuanDana();
    }

    /**
     * Scope a query to only include details for specific refund.
     */
    public function scopeForRefund($query, $refundId)
    {
        return $query->where('refund_id', $refundId);
    }

    /**
     * Scope a query to only include details for specific LPJ.
     */
    public function scopeForLpj($query, $lpjId)
    {
        return $query->where('lpj_id', $lpjId);
    }
}
