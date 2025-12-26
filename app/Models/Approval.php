<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_dana_id',
        'approver_id',
        'level',
        'status',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the pengajuan dana that owns the approval.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the user who approved.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Scope a query to only include pending approvals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved approvals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected approvals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
