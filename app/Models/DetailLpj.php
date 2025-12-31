<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailLpj extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_pertanggung_jawaban_id',
        'detail_pencairan_id',
        'uraian',
        'volume_realisasi',
        'satuan',
        'harga_satuan',
        'subtotal_realisasi',
        'keterangan',
        'file_lampiran',
        'tanggal_realisasi',
    ];

    protected $casts = [
        'volume_realisasi' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'subtotal_realisasi' => 'decimal:2',
        'tanggal_realisasi' => 'date',
    ];

    /**
     * Get the LPJ that owns the detail.
     */
    public function laporanPertanggungJawaban()
    {
        return $this->belongsTo(LaporanPertanggungJawaban::class, 'laporan_pertanggung_jawaban_id');
    }

    /**
     * Get the detail pencairan for this detail LPJ.
     */
    public function detailPencairan()
    {
        return $this->belongsTo(DetailPencairan::class);
    }

    /**
     * Scope a query to calculate subtotal.
     */
    public function scopeCalculateSubtotal($query)
    {
        return $this->volume_realisasi * $this->harga_satuan;
    }
}
