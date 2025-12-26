<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SumberDana extends Model
{
    protected $fillable = [
        'kode_sumber',
        'nama_sumber',
        'deskripsi',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the sumber dana.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter only active sumber dana.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all perencanaan penerimaan that use this sumber dana.
     */
    public function perencanaanPenerimaans()
    {
        return $this->hasMany(PerencanaanPenerimaan::class, 'sumber_dana_id');
    }

    /**
     * Get all pencatatan penerimaan that use this sumber dana.
     */
    public function pencatatanPenerimaans()
    {
        return $this->hasMany(PencatatanPenerimaan::class, 'sumber_dana_id');
    }
}
