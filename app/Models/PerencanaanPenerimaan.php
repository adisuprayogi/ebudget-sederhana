<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerencanaanPenerimaan extends Model
{
    protected $fillable = [
        'periode_anggaran_id',
        'divisi_id',
        'kode_rekening',
        'uraian',
        'sumber_dana_id',
        'jumlah_estimasi',
        'perkiraan_bulanan',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'jumlah_estimasi' => 'decimal:2',
        'perkiraan_bulanan' => 'array',
    ];

    protected $appends = [
        'total_bulanan',
        'realisasi',
        'sisa_estimasi',
        'persentase_realisasi',
    ];

    public function periodeAnggaran()
    {
        return $this->belongsTo(PeriodeAnggaran::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'sumber_dana_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pencatatanPenerimaans()
    {
        return $this->hasMany(PencatatanPenerimaan::class, 'perencanaan_penerimaan_id');
    }

    public function getTotalBulananAttribute()
    {
        if (empty($this->perkiraan_bulanan)) {
            return 0;
        }
        return array_sum(array_values($this->perkiraan_bulanan));
    }

    public function getRealisasiAttribute()
    {
        return $this->pencatatanPenerimaans()->sum('jumlah_diterima');
    }

    public function getSisaEstimasiAttribute()
    {
        return $this->jumlah_estimasi - $this->realisasi;
    }

    public function getPersentaseRealisasiAttribute()
    {
        if ($this->jumlah_estimasi == 0) return 0;
        return ($this->realisasi / $this->jumlah_estimasi) * 100;
    }

    /**
     * Get list of months based on periode anggaran
     */
    public function getBulanListAttribute()
    {
        if (!$this->periodeAnggaran) {
            return [];
        }

        $startDate = \Carbon\Carbon::parse($this->periodeAnggaran->tanggal_mulai_penggunaan_anggaran);
        $endDate = \Carbon\Carbon::parse($this->periodeAnggaran->tanggal_selesai_penggunaan_anggaran);

        $months = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $key = $current->format('Y-m');
            $label = $current->translatedFormat('F Y');
            $months[$key] = $label;
            $current->addMonth();
        }

        return $months;
    }

    /**
     * Get estimated amount for a specific month
     */
    public function getEstimasiBulan($key)
    {
        return $this->perkiraan_bulanan[$key] ?? 0;
    }
}
