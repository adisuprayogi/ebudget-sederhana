<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalConfig extends Model
{
    protected $fillable = [
        'jenis_pengajuan',
        'minimal_nominal',
        'level',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'minimal_nominal' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk filter berdasarkan jenis pengajuan
     */
    public function scopeJenisPengajuan($query, $jenis)
    {
        return $query->where('jenis_pengajuan', $jenis);
    }

    /**
     * Scope untuk mendapatkan config yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan nominal
     */
    public function scopeNominal($query, $nominal)
    {
        return $query->where('minimal_nominal', '<=', $nominal);
    }

    /**
     * Urutkan berdasarkan urutan
     */
    public function scopeOrderByUrutan($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
