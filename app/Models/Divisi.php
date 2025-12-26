<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_divisi',
        'nama_divisi',
        'description',
        'total_pagu',
        'terpakai',
        'sisa_pagu',
        'is_active',
    ];

    protected $casts = [
        'total_pagu' => 'decimal:2',
        'terpakai' => 'decimal:2',
        'sisa_pagu' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the divisi.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the job positions for the divisi.
     */
    public function jobPositions()
    {
        return $this->hasMany(JobPosition::class);
    }

    /**
     * Get all users that have job positions in this divisi.
     */
    public function staff()
    {
        return $this->belongsToMany(User::class, 'user_job_positions')
            ->through('jobPositions')
            ->whereNull('user_job_positions.ended_at')
            ->distinct();
    }

    /**
     * Get the pengajuan dana for the divisi.
     */
    public function pengajuanDana()
    {
        return $this->hasMany(PengajuanDana::class);
    }

    /**
     * Get the program kerja for the divisi.
     */
    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class);
    }

    /**
     * Update pagu usage.
     */
    public function updatePaguUsage()
    {
        $this->terpakai = $this->pengajuanDana()
            ->where('status', 'disetujui')
            ->sum('total_pengajuan');

        $this->sisa_pagu = $this->total_pagu - $this->terpakai;
        $this->save();
    }

    /**
     * Scope a query to only include active divisi.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
