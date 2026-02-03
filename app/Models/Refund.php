<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'nomor_refund',
        'lpj_id',
        'pencairan_dana_id',
        'pengajuan_dana_id',
        'periode_anggaran_id',
        'tanggal_refund',
        'jumlah_refund',
        'alasan_refund',
        'jenis_refund',
        'metode_refund',
        'rekening_perusahaan_id',
        'rekening_pengirim',
        'nama_pengirim',
        'bukti_transfer',
        'status',
        'created_by',
        'approved_at',
        'approved_by',
        'catatan_approval',
        'tanggal_transfer',
        'processed_at',
    ];

    protected $casts = [
        'tanggal_refund' => 'date',
        'jumlah_refund' => 'decimal:2',
        'approved_at' => 'datetime',
        'tanggal_transfer' => 'date',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the LPJ associated with the refund.
     */
    public function lpj()
    {
        return $this->belongsTo(LaporanPertanggungJawaban::class, 'lpj_id');
    }

    /**
     * Get the pencairan dana associated with the refund.
     */
    public function pencairanDana()
    {
        return $this->belongsTo(PencairanDana::class);
    }

    /**
     * Get the pengajuan dana associated with the refund.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the periode anggaran associated with the refund.
     */
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    /**
     * Get the rekening perusahaan for this refund.
     */
    public function rekeningPerusahaan()
    {
        return $this->belongsTo(RekeningPerusahaan::class);
    }

    /**
     * Get the user who created the refund.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved the refund.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the refund details for this refund.
     */
    public function refundDetails()
    {
        return $this->hasMany(RefundDetail::class);
    }

    /**
     * Get all LPJs through refund details.
     */
    public function lpjs()
    {
        return $this->hasManyThrough(
            LaporanPertanggungJawaban::class,
            RefundDetail::class,
            'refund_id',
            'id',
            'id',
            'lpj_id'
        );
    }

    /**
     * Scope a query to only include refunds with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending refunds.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu_approval');
    }

    /**
     * Scope a query to only include approved refunds.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include processed refunds.
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Check if refund is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'menunggu_approval';
    }

    /**
     * Check if refund is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if refund is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if refund is processed.
     */
    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }
}
