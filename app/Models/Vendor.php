<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'jenis_vendor',
        'npwp',
        'alamat',
        'kota',
        'propinsi',
        'kode_pos',
        'negara',
        'telepon',
        'email',
        'kontak_person',
        'nomor_rekening',
        'nama_bank',
        'status',
        'rating',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    /**
     * Get the user who created the vendor.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the vendor.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active vendors.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive vendors.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for blacklisted vendors.
     */
    public function scopeBlacklisted($query)
    {
        return $query->where('status', 'blacklisted');
    }

    /**
     * Scope for vendors by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('jenis_vendor', $type);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'blacklisted' => 'Blacklist',
            default => 'Unknown',
        };
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->jenis_vendor) {
            'supplier' => 'Supplier',
            'kontraktor' => 'Kontraktor',
            'konsultan' => 'Konsultan',
            'lainnya' => 'Lainnya',
            default => 'Unknown',
        };
    }
}
