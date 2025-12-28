<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonorariumDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_dana_id',
        'penerima_manfaat_type',
        'penerima_manfaat_id',
        'penerima_manfaat_name',
        'jabatan',
        'jumlah_honor',
        'nomor_rekening',
        'periode_mulai',
        'periode_selesai',
        'deskripsi',
        'lampiran',
        'lampiran_filename',
    ];

    protected $casts = [
        'jumlah_honor' => 'decimal:2',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    /**
     * Get the pengajuan dana that owns the honorarium detail.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the user (karyawan) for this honorarium.
     */
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'penerima_manfaat_id');
    }

    /**
     * Get full name of the penerima manfaat.
     */
    public function getPenerimaNamaAttribute()
    {
        if ($this->penerima_manfaat_type === 'karyawan' && $this->karyawan) {
            return $this->karyawan->name;
        }
        return $this->penerima_manfaat_name;
    }
}

