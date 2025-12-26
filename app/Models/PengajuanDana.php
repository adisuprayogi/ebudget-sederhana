<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_anggaran_id',
        'nomor_pengajuan',
        'tanggal_pengajuan',
        'divisi_id',
        'program_kerja_id',
        'jenis_pengajuan',
        'penerima_manfaat_type',
        'penerima_manfaat_id',
        'penerima_manfaat_name',
        'penerima_manfaat_detail',
        'judul_pengajuan',
        'deskripsi',
        'total_pengajuan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'penerima_manfaat_detail' => 'array',
        'tanggal_pengajuan' => 'date',
    ];

    /**
     * Get the periode anggaran for this pengajuan.
     */
    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    /**
     * Get the divisi that owns the pengajuan.
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Get the program kerja for this pengajuan.
     */
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'program_kerja_id');
    }

    /**
     * Get the user who created the pengajuan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who created the pengajuan.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the details for the pengajuan.
     */
    public function details()
    {
        return $this->hasMany(DetailPengajuan::class);
    }

    /**
     * Get the approvals for the pengajuan.
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Get the pencairan for the pengajuan.
     */
    public function pencairanDana()
    {
        return $this->hasOne(PencairanDana::class);
    }

    /**
     * Get the latest approval status.
     */
    public function getLatestApprovalAttribute()
    {
        return $this->approvals()->latest()->first();
    }

    /**
     * Get the attachments for the pengajuan.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Scope a query to only include pengajuan with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pengajuan from specific divisi.
     */
    public function scopeDivisi($query, $divisiId)
    {
        return $query->where('divisi_id', $divisiId);
    }
}
