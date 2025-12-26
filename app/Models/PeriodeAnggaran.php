<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodeAnggaran extends Model
{
    use HasFactory;

    protected $table = 'periode_anggaran';

    protected $fillable = [
        'kode_periode',
        'nama_periode',
        'tahun_anggaran',
        'tanggal_mulai_perencanaan_anggaran',
        'tanggal_selesai_perencanaan_anggaran',
        'tanggal_mulai_penggunaan_anggaran',
        'tanggal_selesai_penggunaan_anggaran',
        'status',
        'deskripsi',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai_perencanaan_anggaran' => 'date',
        'tanggal_selesai_perencanaan_anggaran' => 'date',
        'tanggal_mulai_penggunaan_anggaran' => 'date',
        'tanggal_selesai_penggunaan_anggaran' => 'date',
        'approved_at' => 'datetime',
    ];

    protected $appends = [
        'fase',
        'nama_fase',
        'nama_status',
        'is_active',
        'days_remaining',
        'days_remaining_formatted',
    ];

    // Relationships
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function perencanaanPenerimaans()
    {
        return $this->hasMany(PerencanaanPenerimaan::class);
    }

    public function pencatatanPenerimaans()
    {
        return $this->hasMany(PencatatanPenerimaan::class);
    }

    public function penetapanPagus()
    {
        return $this->hasMany(PenetapanPagu::class);
    }

    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }

    public function subPrograms()
    {
        return $this->hasMany(SubProgram::class);
    }

    public function pengajuanDan()
    {
        // TODO: add periode_anggaran_id to pengajuan_danas table
        // For now, return empty relationship to avoid errors
        return $this->hasMany(PengajuanDana::class, 'periode_anggaran_id')->where('id', '=', 0);
    }

    public function pencairanDan()
    {
        // TODO: add periode_anggaran_id to pencairan_danas table
        // For now, return empty relationship to avoid errors
        return $this->hasMany(PencairanDana::class, 'periode_anggaran_id')->where('id', '=', 0);
    }

    public function laporanPertanggungJawaban()
    {
        // TODO: add periode_anggaran_id to laporan_pertanggung_jawabans table
        // For now, return empty relationship to avoid errors
        return $this->hasMany(LaporanPertanggungJawaban::class, 'periode_anggaran_id')->where('id', '=', 0);
    }

    public function refunds()
    {
        // TODO: add periode_anggaran_id to refunds table
        // For now, return empty relationship to avoid errors
        return $this->hasMany(Refund::class, 'periode_anggaran_id')->where('id', '=', 0);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFase($query, $fase)
    {
        $today = now()->startOfDay();

        return $query->where(function ($q) use ($fase, $today) {
            if ($fase === 'perencangan') {
                $q->where('tanggal_mulai_perencanaan_anggaran', '<=', $today)
                    ->where('tanggal_selesai_perencanaan_anggaran', '>=', $today);
            } elseif ($fase === 'penggunaan') {
                $q->where('tanggal_mulai_penggunaan_anggaran', '<=', $today)
                    ->where('tanggal_selesai_penggunaan_anggaran', '>=', $today);
            } elseif ($fase === 'closed') {
                $q->where('status', 'closed')
                    ->orWhere(function ($q2) use ($today) {
                        $q2->where('tanggal_selesai_penggunaan_anggaran', '<', $today);
                    });
            }
        })->where('status', '!=', 'draft');
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }

    public function scopeCurrent($query)
    {
        $today = now()->startOfDay();

        return $query->where('status', 'active')
            ->where(function ($q) use ($today) {
                // Check if today is within perencanaan phase
                $q->where(function ($q2) use ($today) {
                    $q2->where('tanggal_mulai_perencanaan_anggaran', '<=', $today)
                        ->where('tanggal_selesai_perencanaan_anggaran', '>=', $today);
                })
                // OR within penggunaan phase
                ->orWhere(function ($q3) use ($today) {
                    $q3->where('tanggal_mulai_penggunaan_anggaran', '<=', $today)
                        ->where('tanggal_selesai_penggunaan_anggaran', '>=', $today);
                });
            });
    }

    // Accessors
    public function getFaseAttribute()
    {
        $today = now()->startOfDay();

        // Check if status is closed
        if ($this->status === 'closed') {
            return 'closed';
        }

        // Check if in perencanaan phase
        if ($this->tanggal_mulai_perencanaan_anggaran &&
            $this->tanggal_selesai_perencanaan_anggaran &&
            $today->between($this->tanggal_mulai_perencanaan_anggaran, $this->tanggal_selesai_perencanaan_anggaran)) {
            return 'perencangan';
        }

        // Check if in penggunaan phase
        if ($this->tanggal_mulai_penggunaan_anggaran &&
            $this->tanggal_selesai_penggunaan_anggaran &&
            $today->between($this->tanggal_mulai_penggunaan_anggaran, $this->tanggal_selesai_penggunaan_anggaran)) {
            return 'penggunaan';
        }

        // Default: if perencanaan phase is in the future, return perencangan
        if ($this->tanggal_mulai_perencanaan_anggaran && $today->lt($this->tanggal_mulai_perencanaan_anggaran)) {
            return 'perencangan';
        }

        // If penggunaan phase has ended, return closed
        if ($this->tanggal_selesai_penggunaan_anggaran && $today->gt($this->tanggal_selesai_penggunaan_anggaran)) {
            return 'closed';
        }

        // Default fallback
        return 'perencangan';
    }

    public function getNamaFaseAttribute()
    {
        $fase = $this->fase;
        return match($fase) {
            'perencangan' => 'Perencanaan Anggaran',
            'penggunaan' => 'Penggunaan Anggaran',
            'closed' => 'Ditutup',
            default => $fase,
        };
    }

    public function getNamaStatusAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'active' => 'Aktif',
            'closed' => 'Ditutup',
            default => $this->status,
        };
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
            $this->getCurrentPhaseStartDate() <= now() &&
            $this->getCurrentPhaseEndDate() >= now();
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->is_active) {
            return 0;
        }

        $currentPhaseEnd = $this->getCurrentPhaseEndDate();
        return now()->diffInDays($currentPhaseEnd, false);
    }

    public function getDaysRemainingFormattedAttribute()
    {
        if (!$this->is_active) {
            return '0 hari';
        }

        $currentPhaseEnd = $this->getCurrentPhaseEndDate();
        $now = now();

        if ($now->gt($currentPhaseEnd)) {
            return '0 hari';
        }

        $totalHours = $now->diffInHours($currentPhaseEnd);
        $days = intdiv($totalHours, 24);
        $hours = $totalHours % 24;

        if ($days > 0) {
            if ($hours > 0) {
                return "{$days} hari {$hours} jam";
            }
            return "{$days} hari";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        }

        return 'kurang dari 1 jam';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->fase === 'perencangan') {
            $startDate = $this->tanggal_mulai_perencanaan_anggaran;
            $endDate = $this->tanggal_selesai_perencanaan_anggaran;
        } elseif ($this->fase === 'penggunaan') {
            $startDate = $this->tanggal_mulai_penggunaan_anggaran;
            $endDate = $this->tanggal_selesai_penggunaan_anggaran;
        } else {
            return 100; // Closed phase is complete
        }

        if (!$startDate || !$endDate) {
            return 0;
        }

        $totalDays = $startDate->diffInDays($endDate);
        $elapsedDays = min($startDate->diffInDays(now()), $totalDays);

        return $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;
    }

    /**
     * Get current phase start date
     */
    public function getCurrentPhaseStartDate()
    {
        return $this->fase === 'perencangan'
            ? $this->tanggal_mulai_perencanaan_anggaran
            : $this->tanggal_mulai_penggunaan_anggaran;
    }

    /**
     * Get current phase end date
     */
    public function getCurrentPhaseEndDate()
    {
        return $this->fase === 'perencangan'
            ? $this->tanggal_selesai_perencanaan_anggaran
            : $this->tanggal_selesai_penggunaan_anggaran;
    }

    // Methods
    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            'draft' => ['active'],
            'active' => ['closed'],
            'closed' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    public function transitionTo(string $newStatus, ?User $approvedBy = null): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $this->status = $newStatus;

        if ($newStatus === 'active' || $newStatus === 'closed') {
            $this->approved_by = $approvedBy?->id;
            $this->approved_at = now();
        }

        return $this->save();
    }

    public function isFasePerencangan(): bool
    {
        return $this->fase === 'perencangan';
    }

    public function isFasePenggunaan(): bool
    {
        return $this->fase === 'penggunaan';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed' || $this->fase === 'closed';
    }

    public function getTotalPagu()
    {
        return $this->penetapanPagus()->sum('jumlah_pagu');
    }

    public function getTotalPenerimaan()
    {
        return $this->pencatatanPenerimaans()->sum('jumlah_diterima');
    }

    public function getTotalPengajuan()
    {
        // TODO: add periode_anggaran_id to pengajuan_danas table and update this method
        return 0;
    }

    public function getTotalPencairan()
    {
        // TODO: add periode_anggaran_id to pencairan_danas table and update this method
        return 0;
    }

    public function getRealisasiPercentage()
    {
        $totalPagu = $this->getTotalPagu();

        if ($totalPagu <= 0) {
            return 0;
        }

        $totalPencairan = $this->getTotalPencairan();

        return ($totalPencairan / $totalPagu) * 100;
    }

    public function getStatistics()
    {
        return [
            'total_pagu' => $this->getTotalPagu(),
            'total_penerimaan' => $this->getTotalPenerimaan(),
            'total_pengajuan' => $this->getTotalPengajuan(),
            'total_pencairan' => $this->getTotalPencairan(),
            'realisasi_percentage' => $this->getRealisasiPercentage(),
            'jumlah_program' => $this->programKerjas()->count(),
            'jumlah_pengajuan' => 0, // TODO: requires periode_anggaran_id in pengajuan_danas
            'jumlah_pencairan' => 0, // TODO: requires periode_anggaran_id in pencairan_danas
            'jumlah_lpj' => 0, // TODO: requires periode_anggaran_id in laporan_pertanggung_jawabans
        ];
    }

    public function getDivisiStatistics()
    {
        return $this->penetapanPagus()
            ->with('divisi')
            ->get()
            ->groupBy('divisi.nama_divisi')
            ->map(function ($pagus) {
                $divisiId = $pagus->first()->divisi_id;

                $totalPagu = $pagus->sum('jumlah_pagu');
                $totalPencairan = 0; // TODO: requires periode_anggaran_id in pencairan_danas

                return [
                    'divisi' => $pagus->first()->divisi->nama_divisi,
                    'total_pagu' => $totalPagu,
                    'total_pencairan' => $totalPencairan,
                    'sisa_pagu' => $totalPagu - $totalPencairan,
                    'persentase_realisasi' => $totalPagu > 0 ? ($totalPencairan / $totalPagu) * 100 : 0,
                    'jumlah_program' => $this->programKerjas()->where('divisi_id', $divisiId)->count(),
                    'jumlah_pengajuan' => 0, // TODO: requires periode_anggaran_id in pengajuan_danas
                ];
            });
    }

    public function getMonthlyTrend()
    {
        $startDate = $this->tanggal_mulai_penggunaan_anggaran;
        $endDate = $this->tanggal_selesai_penggunaan_anggaran;

        // TODO: requires periode_anggaran_id in pengajuan_danas and pencairan_danas
        $pengajuanData = collect();
        $pencairanData = collect();

        // Generate all months in the period
        $trend = [];

        if (!$startDate || !$endDate) {
            return $trend;
        }

        $current = $startDate->copy();

        while ($current <= $endDate) {
            $monthKey = $current->format('Y-m');
            $pengajuanMonth = $pengajuanData->where('month', $monthKey)->first();
            $pencairanMonth = $pencairanData->where('month', $monthKey)->first();

            $trend[] = [
                'month' => $monthKey,
                'month_name' => $current->format('F Y'),
                'pengajuan_count' => $pengajuanMonth?->count ?? 0,
                'pengajuan_total' => $pengajuanMonth?->total ?? 0,
                'pencairan_count' => $pencairanMonth?->count ?? 0,
                'pencairan_total' => $pencairanMonth?->total ?? 0,
            ];

            $current->addMonth();
        }

        return $trend;
    }

    public function close(): bool
    {
        if ($this->status === 'closed') {
            return false;
        }

        return $this->transitionTo('closed');
    }

    public function getWarnings()
    {
        $warnings = [];

        // Check for upcoming phase transition
        if ($this->fase === 'perencangan' && $this->daysRemaining <= 30) {
            $warnings[] = [
                'type' => 'phase_transition',
                'message' => "Fase perencanaan akan berakhir dalam {$this->daysRemaining} hari",
                'severity' => $this->daysRemaining <= 7 ? 'high' : 'medium',
            ];
        }

        // Check for programs without enough time
        if ($this->fase === 'penggunaan') {
            $incompletePrograms = $this->programKerjas()
                ->where('status', '!=', 'completed')
                ->where('durasi_selesai', '<', now()->addDays(30))
                ->count();

            if ($incompletePrograms > 0) {
                $warnings[] = [
                    'type' => 'program_deadline',
                    'message' => "{$incompletePrograms} program kerja mendekati deadline",
                    'severity' => 'high',
                ];
            }
        }

        // Check for budget utilization
        if ($this->fase === 'penggunaan' && $this->getRealisasiPercentage() > 90) {
            $warnings[] = [
                'type' => 'budget_utilization',
                'message' => 'Utilisasi anggaran sudah mencapai ' . round($this->getRealisasiPercentage()) . '%',
                'severity' => 'high',
            ];
        }

        return $warnings;
    }
}