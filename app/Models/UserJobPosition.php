<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserJobPosition extends Model
{
    protected $fillable = [
        'user_id',
        'job_position_id',
        'is_primary',
        'assigned_at',
        'ended_at',
        'catatan',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'assigned_at' => 'date',
        'ended_at' => 'date',
    ];

    /**
     * Get the user that owns the job position.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job position that belongs to the user.
     */
    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    /**
     * Get the divisi through job position.
     */
    public function divisi()
    {
        return $this->hasOneThrough(Divisi::class, JobPosition::class, 'id', 'id', 'job_position_id', 'divisi_id');
    }

    /**
     * Scope to get only active job positions (not ended).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    /**
     * Scope to get only primary job positions.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
