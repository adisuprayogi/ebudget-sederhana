<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanPertanggungJawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_lpj',
        'periode_anggaran_id',
        'pengajuan_dana_id',
        'pencairan_dana_id',
        'tanggal_lpj',
        'uraian_kegiatan',
        'total_digunakan',
        'sisa_dana',
        'catatan',
        'status',
        'created_by',
        'verified_by',
        'approved_by',
        'submitted_at',
        'verified_at',
        'approved_at',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'approval_notes',
    ];

    protected $casts = [
        'tanggal_lpj' => 'date',
        'total_digunakan' => 'decimal:2',
        'sisa_dana' => 'decimal:2',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the periode anggaran for this LPJ.
     */
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    /**
     * Get the pengajuan dana for this LPJ.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    /**
     * Get the pencairan dana for this LPJ.
     */
    public function pencairanDana()
    {
        return $this->belongsTo(PencairanDana::class);
    }

    /**
     * Get the user who created the LPJ.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified the LPJ.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the user who approved the LPJ.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected the LPJ.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the detail LPJs for this LPJ.
     */
    public function detailLpjs()
    {
        return $this->hasMany(DetailLpj::class, 'laporan_pertanggung_jawaban_id');
    }

    /**
     * Get the refunds for this LPJ.
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class, 'lpj_id');
    }

    /**
     * Get the refund details for this LPJ.
     */
    public function refundDetails()
    {
        return $this->hasMany(RefundDetail::class, 'lpj_id');
    }

    /**
     * Check if this LPJ has pending refund details (active refunds).
     */
    public function hasActiveRefundDetails(): bool
    {
        return $this->refundDetails()
            ->whereHas('refund', function ($query) {
                $query->whereNotIn('status', ['rejected']);
            })
            ->exists();
    }

    /**
     * Get total refunded amount through refund details.
     */
    public function getTotalRefundedAttribute(): float
    {
        return $this->refundDetails()
            ->whereHas('refund', function ($query) {
                $query->whereNotIn('status', ['rejected']);
            })
            ->sum('jumlah_refund');
    }

    /**
     * Get remaining sisa after refund details.
     */
    public function getSisaSetelahRefundAttribute(): float
    {
        return max(0, $this->sisa_dana - $this->total_refunded);
    }

    /**
     * Scope a query to only include LPJ with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include draft LPJ.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include menunggu verifikasi LPJ.
     */
    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status', 'menunggu_verifikasi');
    }

    /**
     * Scope a query to only include submitted LPJ.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'menunggu_verifikasi');
    }

    /**
     * Scope a query to only include approved LPJ.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if LPJ is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if LPJ is menunggu verifikasi.
     */
    public function isMenungguVerifikasi(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    /**
     * Check if LPJ is submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    /**
     * Check if LPJ is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if LPJ is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if LPJ needs revision.
     */
    public function needsRevision(): bool
    {
        return $this->status === 'revisi';
    }

    /**
     * Get the periode anggaran from pengajuan.
     */
    public function getPeriodeAnggaranAttribute()
    {
        return $this->pengajuanDana?->periodeAnggaran;
    }

    /**
     * Get the divisi from pengajuan.
     */
    public function getDivisiAttribute()
    {
        return $this->pengajuanDana?->divisi;
    }
}
