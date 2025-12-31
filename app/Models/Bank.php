<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_bank',
        'nama_bank',
        'singkatan',
        'logo',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the rekening perusahaans for this bank.
     */
    public function rekeningPerusahaans()
    {
        return $this->hasMany(RekeningPerusahaan::class);
    }

    /**
     * Scope to only include active banks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full name with code.
     */
    public function getFullNameAttribute()
    {
        return "{$this->kode_bank} - {$this->nama_bank}";
    }
}
