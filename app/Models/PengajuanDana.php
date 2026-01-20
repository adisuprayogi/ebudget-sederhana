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
        'sub_program_id',
        'jenis_pengajuan',
        'detail_anggaran_id',
        'penerima_manfaat_type',
        'penerima_manfaat_id',
        'penerima_manfaat_name',
        'penerima_manfaat_detail',
        'judul_pengajuan',
        'deskripsi',
        'nama_bank',
        'rekening_tujuan',
        'total_pengajuan',
        'status',
        'catatan',
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
     * Get the sub program for this pengajuan.
     */
    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class, 'sub_program_id');
    }

    /**
     * Get the detail anggaran for this pengajuan.
     */
    public function detailAnggaran()
    {
        return $this->belongsTo(DetailAnggaran::class, 'detail_anggaran_id');
    }

    /**
     * Get the user who created the pengajuan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who created the pengajuan (alias).
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
     * Get all pencairans for the pengajuan.
     */
    public function pencairans()
    {
        return $this->hasMany(PencairanDana::class);
    }

    /**
     * Get the latest active (non-cancelled, non-revisi) pencairan for the pengajuan.
     */
    public function activePencairan()
    {
        return $this->hasOne(PencairanDana::class)
            ->whereNotIn('status', ['cancelled', 'revisi'])
            ->latest();
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
     * Get the honorarium details for the pengajuan.
     */
    public function honorariumDetails()
    {
        return $this->hasMany(HonorariumDetail::class);
    }

    /**
     * Get the laporan pertanggung jawaban for the pengajuan.
     */
    public function laporanPertanggungJawabans()
    {
        return $this->hasMany(LaporanPertanggungJawaban::class, 'pengajuan_dana_id');
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
