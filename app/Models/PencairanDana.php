<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pencairan',
        'pengajuan_dana_id',
        'rekening_perusahaan_id',
        'tanggal_pencairan',
        'jumlah_pencairan',
        'metode_pencairan',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'nama_bank_sumber',
        'nomor_rekening_sumber',
        'status',
        'catatan',
        'created_by',
        'processed_by',
        'bukti_pencairan',
    ];

    protected $casts = [
        'tanggal_pencairan' => 'date',
        'jumlah_pencairan' => 'decimal:2',
    ];

    /**
     * Get the pengajuan dana that owns the pencairan.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the rekening perusahaan for this pencairan.
     */
    public function rekeningPerusahaan()
    {
        return $this->belongsTo(RekeningPerusahaan::class);
    }

    /**
     * Get the user who created the pencairan.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who processed the pencairan.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the details for the pencairan.
     */
    public function detailPencairans()
    {
        return $this->hasMany(DetailPencairan::class);
    }

    /**
     * Get the lampirans for the pencairan.
     */
    public function lampirans()
    {
        return $this->hasMany(PencairanLampiran::class);
    }

    /**
     * Get the honorarium lampirans for the pencairan.
     */
    public function honorariumLampirans()
    {
        return $this->hasMany(HonorariumLampiran::class);
    }

    /**
     * Get the LPJ for this pencairan.
     */
    public function laporanPertanggungJawaban()
    {
        return $this->hasOne(LaporanPertanggungJawaban::class);
    }

    /**
     * Get the LPJs for this pencairan (alias for hasMany relationship).
     */
    public function lpjs()
    {
        return $this->hasMany(LaporanPertanggungJawaban::class, 'pencairan_dana_id');
    }

    /**
     * Scope a query to only include pencairan with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending pencairan.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include processing pencairan.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope a query to only include completed pencairan.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include menunggu_lpj pencairan.
     */
    public function scopeMenungguLpj($query)
    {
        return $query->where('status', 'menunggu_lpj');
    }

    /**
     * Scope a query to only include menunggu_verifikasi_lpj pencairan.
     */
    public function scopeMenungguVerifikasiLpj($query)
    {
        return $query->where('status', 'menunggu_verifikasi_lpj');
    }

    /**
     * Scope a query to only include menunggu_pengembalian pencairan.
     */
    public function scopeMenungguPengembalian($query)
    {
        return $query->where('status', 'menunggu_pengembalian');
    }

    /**
     * Scope a query to only include active (non-cancelled, non-revisi) pencairan.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'revisi']);
    }

    /**
     * Check if pencairan is menunggu LPJ.
     */
    public function isMenungguLpj(): bool
    {
        return $this->status === 'menunggu_lpj';
    }

    /**
     * Check if pencairan is menunggu verifikasi LPJ.
     */
    public function isMenungguVerifikasiLpj(): bool
    {
        return $this->status === 'menunggu_verifikasi_lpj';
    }

    /**
     * Check if pencairan is menunggu pengembalian.
     */
    public function isMenungguPengembalian(): bool
    {
        return $this->status === 'menunggu_pengembalian';
    }

    /**
     * Check if pencairan is selesai.
     */
    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Get total_pencairan alias for backward compatibility.
     */
    public function getTotalPencairanAttribute()
    {
        return $this->jumlah_pencairan;
    }

    /**
     * Set total_pencairan alias for backward compatibility.
     */
    public function setTotalPencairanAttribute($value)
    {
        $this->attributes['jumlah_pencairan'] = $value;
    }
}
