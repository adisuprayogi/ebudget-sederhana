<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPencairan extends Model
{
    protected $fillable = [
        'pencairan_dana_id',
        'detail_pengajuan_id',
        'honorarium_detail_id',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the pencairan dana that owns the detail.
     */
    public function pencairanDana()
    {
        return $this->belongsTo(PencairanDana::class);
    }

    /**
     * Get the detail pengajuan that owns the detail.
     */
    public function detailPengajuan()
    {
        return $this->belongsTo(DetailPengajuan::class);
    }

    /**
     * Get the honorarium detail that owns the detail.
     */
    public function honorariumDetail()
    {
        return $this->belongsTo(HonorariumDetail::class);
    }
}
