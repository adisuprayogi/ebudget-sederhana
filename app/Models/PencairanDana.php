<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pencairan',
        'pengajuan_dana_id',
        'tanggal_pencairan',
        'total_pencairan',
        'status',
        'approved_by',
        'approved_at',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pencairan' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the pengajuan dana that owns the pencairan.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the user who approved the pencairan.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who created the pencairan.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the details for the pencairan.
     */
    public function details()
    {
        return $this->hasMany(DetailPencairan::class);
    }

    /**
     * Scope a query to only include pencairan with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending pencairan.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved pencairan.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
