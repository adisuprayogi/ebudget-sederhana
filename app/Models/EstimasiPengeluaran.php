<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimasiPengeluaran extends Model
{
    protected $fillable = [
        'detail_anggaran_id',
        'urutan_periode',
        'tanggal_rencana_realisasi',
        'nominal_rencana',
        'nominal_realisasi',
        'tanggal_realisasi',
        'status',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_rencana_realisasi' => 'date',
        'tanggal_realisasi' => 'date',
        'nominal_rencana' => 'decimal:2',
        'nominal_realisasi' => 'decimal:2',
    ];

    /**
     * Get the detail anggaran that owns the estimasi.
     */
    public function detailAnggaran()
    {
        return $this->belongsTo(DetailAnggaran::class);
    }

    /**
     * Get the user who last updated the estimasi.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include pending estimasi.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed estimasi.
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Calculate selisih between rencana and realisasi.
     */
    public function getSelisihAttribute()
    {
        return $this->nominal_rencana - ($this->nominal_realisasi ?? 0);
    }

    /**
     * Check if estimasi is overdue.
     */
    public function isOverdue()
    {
        return $this->tanggal_rencana_realisasi->isPast() && $this->status === 'pending';
    }
}
