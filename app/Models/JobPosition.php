<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosition extends Model
{
    protected $fillable = [
        'divisi_id',
        'kode_jabatan',
        'nama_jabatan',
        'deskripsi',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * Get the divisi that owns the job position.
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Get the user job positions for this job position.
     */
    public function userJobPositions(): HasMany
    {
        return $this->hasMany(UserJobPosition::class);
    }

    /**
     * Get users that have this job position.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_job_positions')
            ->withPivot(['is_primary', 'assigned_at', 'ended_at', 'catatan'])
            ->whereNull('user_job_positions.ended_at')
            ->orderBy('user_job_positions.is_primary', 'desc');
    }

    /**
     * Scope to get only active job positions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by level (highest first).
     */
    public function scopeOrderByLevel($query)
    {
        return $query->orderBy('level', 'asc');
    }
}
