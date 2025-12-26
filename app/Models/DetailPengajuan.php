<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_dana_id',
        'sub_program_id',
        'detail_anggaran_id',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'volume' => 'decimal:2',
    ];

    /**
     * Get the pengajuan dana that owns the detail.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the sub program for the detail.
     */
    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class);
    }

    /**
     * Get the detail anggaran for the detail.
     */
    public function detailAnggaran()
    {
        return $this->belongsTo(DetailAnggaran::class);
    }

    /**
     * Calculate subtotal before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->subtotal = $detail->volume * $detail->harga_satuan;
        });
    }
}
