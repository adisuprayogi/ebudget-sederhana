<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\Refund;
use App\Models\Divisi;
use App\Models\User;
use App\Models\PenetapanPagu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportService
{
    /**
     * Get comprehensive dashboard statistics
     */
    public static function getDashboardStatistics($startDate = null, $endDate = null, $divisiId = null)
    {
        $queryPengajuan = PengajuanDana::query();
        $queryPencairan = PencairanDana::query();
        $queryLpj = LaporanPertanggungJawaban::query();
        $queryRefund = Refund::query();

        // Apply date filters
        if ($startDate) {
            $queryPengajuan->whereDate('created_at', '>=', $startDate);
            $queryPencairan->whereDate('tanggal_pencairan', '>=', $startDate);
            $queryLpj->whereDate('tanggal_lpj', '>=', $startDate);
            $queryRefund->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $queryPengajuan->whereDate('created_at', '<=', $endDate);
            $queryPencairan->whereDate('tanggal_pencairan', '<=', $endDate);
            $queryLpj->whereDate('tanggal_lpj', '<=', $endDate);
            $queryRefund->whereDate('created_at', '<=', $endDate);
        }

        // Apply divisi filter
        if ($divisiId) {
            $queryPengajuan->where('divisi_id', $divisiId);
            $queryPencairan->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
            $queryLpj->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
            $queryRefund->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        return [
            'pengajuan' => [
                'total' => $queryPengajuan->count(),
                'total_nominal' => $queryPengajuan->sum('total_pengajuan'),
                'by_status' => $queryPengajuan->get()->groupBy('status')->map->count(),
                'by_jenis' => $queryPengajuan->get()->groupBy('jenis_pengajuan')->map->count(),
                'by_divisi' => $queryPengajuan->with('divisi')->get()
                    ->groupBy('divisi.nama_divisi')
                    ->map(function ($group) {
                        return [
                            'count' => $group->count(),
                            'total' => $group->sum('total_pengajuan')
                        ];
                    }),
            ],
            'pencairan' => [
                'total' => $queryPencairan->count(),
                'total_nominal' => $queryPencairan->sum('total_pencairan'),
                'by_status' => $queryPencairan->get()->groupBy('status')->map->count(),
                'by_cara' => $queryPencairan->get()->groupBy('cara_pencairan')->map->count(),
            ],
            'lpj' => [
                'total' => $queryLpj->count(),
                'total_digunakan' => $queryLpj->sum('total_digunakan'),
                'total_sisa' => $queryLpj->sum('sisa_dana'),
                'by_status' => $queryLpj->get()->groupBy('status')->map->count(),
            ],
            'refund' => [
                'total' => $queryRefund->count(),
                'total_nominal' => $queryRefund->sum('nominal_refund'),
                'by_jenis' => $queryRefund->get()->groupBy('jenis_refund')->map->count(),
                'by_status' => $queryRefund->get()->groupBy('status')->map->count(),
            ],
        ];
    }

    /**
     * Get budget realization report
     */
    public static function getBudgetRealization($tahun = null, $divisiId = null)
    {
        $tahun = $tahun ?? now()->year;

        $query = PenetapanPagu::with(['divisi', 'programKerjas'])
            ->where('tahun', $tahun);

        if ($divisiId) {
            $query->where('divisi_id', $divisiId);
        }

        $paguPerDivisi = $query->get();

        $report = [];

        foreach ($paguPerDivisi as $pagu) {
            // Get total pengajuan for this divisi
            $totalPengajuan = PengajuanDana::where('divisi_id', $pagu->divisi_id)
                ->whereYear('created_at', $tahun)
                ->where('status', '!=', 'ditolak')
                ->sum('total_pengajuan');

            // Get total pencairan for this divisi
            $totalPencairan = PencairanDana::whereHas('pengajuanDana', function ($q) use ($pagu, $tahun) {
                $q->where('divisi_id', $pagu->divisi_id)
                  ->whereYear('created_at', $tahun);
            })->sum('total_pencairan');

            // Get total LPJ used
            $totalDigunakan = LaporanPertanggungJawaban::whereHas('pengajuanDana', function ($q) use ($pagu, $tahun) {
                $q->where('divisi_id', $pagu->divisi_id)
                  ->whereYear('created_at', $tahun);
            })->sum('total_digunakan');

            $report[] = [
                'divisi' => $pagu->divisi->nama_divisi,
                'pagu' => $pagu->jumlah_pagu,
                'total_pengajuan' => $totalPengajuan,
                'total_pencairan' => $totalPencairan,
                'total_digunakan' => $totalDigunakan,
                'sisa_pagu' => $pagu->jumlah_pagu - $totalPengajuan,
                'persentase_pengajuan' => $pagu->jumlah_pagu > 0
                    ? ($totalPengajuan / $pagu->jumlah_pagu) * 100
                    : 0,
                'persentase_pencairan' => $pagu->jumlah_pagu > 0
                    ? ($totalPencairan / $pagu->jumlah_pagu) * 100
                    : 0,
                'persentase_realisasi' => $totalPengajuan > 0
                    ? ($totalDigunakan / $totalPengajuan) * 100
                    : 0,
            ];
        }

        return $report;
    }

    /**
     * Get monthly trend report
     */
    public static function getMonthlyTrend($tahun = null, $divisiId = null)
    {
        $tahun = $tahun ?? now()->year;

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create($tahun, $i, 1);
            $months[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->format('F'),
                'pengajuan_count' => PengajuanDana::whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->when($divisiId, function ($q) use ($divisiId) {
                        return $q->where('divisi_id', $divisiId);
                    })
                    ->count(),
                'pengajuan_total' => PengajuanDana::whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->when($divisiId, function ($q) use ($divisiId) {
                        return $q->where('divisi_id', $divisiId);
                    })
                    ->sum('total_pengajuan'),
                'pencairan_count' => PencairanDana::whereYear('tanggal_pencairan', $tahun)
                    ->whereMonth('tanggal_pencairan', $i)
                    ->when($divisiId, function ($q) use ($divisiId) {
                        return $q->whereHas('pengajuanDana', function ($subQ) use ($divisiId) {
                            return $subQ->where('divisi_id', $divisiId);
                        });
                    })
                    ->count(),
                'pencairan_total' => PencairanDana::whereYear('tanggal_pencairan', $tahun)
                    ->whereMonth('tanggal_pencairan', $i)
                    ->when($divisiId, function ($q) use ($divisiId) {
                        return $q->whereHas('pengajuanDana', function ($subQ) use ($divisiId) {
                            return $subQ->where('divisi_id', $divisiId);
                        });
                    })
                    ->sum('total_pencairan'),
            ];
        }

        return $months;
    }

    /**
     * Get approval performance report
     */
    public static function getApprovalPerformance($startDate = null, $endDate = null)
    {
        $query = PengajuanDana::with(['approvals.approver'])
            ->where('status', '!=', 'draft');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $pengajuans = $query->get();

        $approvalTimes = [];
        $approverStats = [];

        foreach ($pengajuans as $pengajuan) {
            foreach ($pengajuan->approvals as $approval) {
                if ($approval->approved_at) {
                    $approvalTime = $approval->created_at->diffInHours($approval->approved_at);
                    $approvalTimes[] = $approvalTime;

                    $approverName = $approval->approver->full_name;
                    if (!isset($approverStats[$approverName])) {
                        $approverStats[$approverName] = [
                            'total_approvals' => 0,
                            'total_time' => 0,
                            'avg_time' => 0,
                        ];
                    }

                    $approverStats[$approverName]['total_approvals']++;
                    $approverStats[$approverName]['total_time'] += $approvalTime;
                    $approverStats[$approverName]['avg_time'] =
                        $approverStats[$approverName]['total_time'] /
                        $approverStats[$approverName]['total_approvals'];
                }
            }
        }

        return [
            'avg_approval_time' => count($approvalTimes) > 0
                ? array_sum($approvalTimes) / count($approvalTimes)
                : 0,
            'total_approvals' => count($approvalTimes),
            'approver_stats' => $approverStats,
        ];
    }

    /**
     * Get division comparison report
     */
    public static function getDivisionComparison($tahun = null)
    {
        $tahun = $tahun ?? now()->year;

        $divisis = Divisi::with(['pengajuanDan' => function ($q) use ($tahun) {
            $q->whereYear('created_at', $tahun);
        }])->get();

        $comparison = [];

        foreach ($divisis as $divisi) {
            $pengajuanCount = $divisi->pengajuanDan->count();
            $totalPengajuan = $divisi->pengajuanDan->sum('total_pengajuan');
            $approvedCount = $divisi->pengajuanDan->where('status', 'disetujui')->count();
            $rejectedCount = $divisi->pengajuanDan->where('status', 'ditolak')->count();

            // Get pencairan data
            $pencairanTotal = PencairanDana::whereHas('pengajuanDana', function ($q) use ($divisi, $tahun) {
                $q->where('divisi_id', $divisi->id)
                  ->whereYear('created_at', $tahun);
            })->sum('total_pencairan');

            $comparison[] = [
                'divisi' => $divisi->nama_divisi,
                'pengajuan_count' => $pengajuanCount,
                'total_pengajuan' => $totalPengajuan,
                'approved_count' => $approvedCount,
                'rejected_count' => $rejectedCount,
                'approval_rate' => $pengajuanCount > 0
                    ? ($approvedCount / $pengajuanCount) * 100
                    : 0,
                'pencairan_total' => $pencairanTotal,
                'pencairan_rate' => $totalPengajuan > 0
                    ? ($pencairanTotal / $totalPengajuan) * 100
                    : 0,
                'avg_pengajuan' => $pengajuanCount > 0
                    ? $totalPengajuan / $pengajuanCount
                    : 0,
            ];
        }

        // Sort by total pengajuan descending
        usort($comparison, function ($a, $b) {
            return $b['total_pengajuan'] <=> $a['total_pengajuan'];
        });

        return $comparison;
    }

    /**
     * Get jenis pengajuan analysis
     */
    public static function getJenisPengajuanAnalysis($tahun = null)
    {
        $tahun = $tahun ?? now()->year;

        $pengajuanByJenis = PengajuanDana::whereYear('created_at', $tahun)
            ->with(['pencairanDana', 'laporanPertanggungJawaban'])
            ->get()
            ->groupBy('jenis_pengajuan');

        $analysis = [];

        foreach ($pengajuanByJenis as $jenis => $pengajuans) {
            $totalPengajuan = $pengajuans->sum('total_pengajuan');
            $totalPencairan = $pengajuans->sum(function ($p) {
                return $p->pencairanDana?->total_pencairan ?? 0;
            });
            $totalDigunakan = $pengajuans->sum(function ($p) {
                return $p->laporanPertanggungJawaban?->total_digunakan ?? 0;
            });
            $avgProcessingTime = $pengajuans->filter(function ($p) {
                return $p->approved_at && $p->created_at;
            })->avg(function ($p) {
                return $p->created_at->diffInDays($p->approved_at);
            });

            $analysis[$jenis] = [
                'count' => $pengajuans->count(),
                'total_pengajuan' => $totalPengajuan,
                'total_pencairan' => $totalPencairan,
                'total_digunakan' => $totalDigunakan,
                'avg_nominal' => $pengajuans->count() > 0
                    ? $totalPengajuan / $pengajuans->count()
                    : 0,
                'pencairan_rate' => $totalPengajuan > 0
                    ? ($totalPencairan / $totalPengajuan) * 100
                    : 0,
                'realisasi_rate' => $totalPencairan > 0
                    ? ($totalDigunakan / $totalPencairan) * 100
                    : 0,
                'avg_processing_time' => round($avgProcessingTime ?? 0, 1),
            ];
        }

        return $analysis;
    }

    /**
     * Generate executive summary report
     */
    public static function getExecutiveSummary($tahun = null)
    {
        $tahun = $tahun ?? now()->year;

        $stats = self::getDashboardStatistics(
            Carbon::create($tahun, 1, 1),
            Carbon::create($tahun, 12, 31)
        );

        $budgetRealization = self::getBudgetRealization($tahun);
        $monthlyTrend = self::getMonthlyTrend($tahun);
        $divisionComparison = self::getDivisionComparison($tahun);
        $approvalPerformance = self::getApprovalPerformance(
            Carbon::create($tahun, 1, 1),
            Carbon::create($tahun, 12, 31)
        );

        return [
            'tahun' => $tahun,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'key_metrics' => [
                'total_pengajuan' => $stats['pengajuan']['total'],
                'total_nominal_pengajuan' => $stats['pengajuan']['total_nominal'],
                'total_pencairan' => $stats['pencairan']['total'],
                'total_nominal_pencairan' => $stats['pencairan']['total_nominal'],
                'approval_rate' => $stats['pengajuan']['by_status']['disetujui'] ?? 0,
                'avg_approval_time' => $approvalPerformance['avg_approval_time'],
            ],
            'budget_realization' => $budgetRealization,
            'monthly_trend' => $monthlyTrend,
            'division_comparison' => $divisionComparison,
            'jenis_pengajuan_analysis' => self::getJenisPengajuanAnalysis($tahun),
        ];
    }

    /**
     * Export report to array format
     */
    public static function exportReport($type, $filters = [])
    {
        switch ($type) {
            case 'dashboard':
                return self::getDashboardStatistics(
                    $filters['start_date'] ?? null,
                    $filters['end_date'] ?? null,
                    $filters['divisi_id'] ?? null
                );

            case 'budget_realization':
                return self::getBudgetRealization(
                    $filters['tahun'] ?? null,
                    $filters['divisi_id'] ?? null
                );

            case 'monthly_trend':
                return self::getMonthlyTrend(
                    $filters['tahun'] ?? null,
                    $filters['divisi_id'] ?? null
                );

            case 'division_comparison':
                return self::getDivisionComparison(
                    $filters['tahun'] ?? null
                );

            case 'executive_summary':
                return self::getExecutiveSummary(
                    $filters['tahun'] ?? null
                );

            default:
                throw new \Exception("Unknown report type: {$type}");
        }
    }

    /**
     * Get high value transactions report
     */
    public static function getHighValueTransactions($threshold = 100000000, $startDate = null, $endDate = null)
    {
        $query = PengajuanDana::with(['divisi', 'createdBy', 'approvals.approver'])
            ->where('total_pengajuan', '>=', $threshold);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->orderBy('total_pengajuan', 'desc')->get();
    }

    /**
     * Get pending items report
     */
    public static function getPendingItemsReport()
    {
        return [
            'pending_approvals' => PengajuanDana::with(['divisi', 'createdBy'])
                ->where('status', 'menunggu_approval')
                ->orderBy('created_at', 'asc')
                ->get(),

            'pending_pencairan' => PencairanDana::with(['pengajuanDana.divisi'])
                ->where('status', 'pending')
                ->orderBy('tanggal_pencairan', 'asc')
                ->get(),

            'overdue_lpj' => LaporanPertanggungJawaban::with(['pengajuanDana.divisi'])
                ->whereHas('pengajuanDana', function ($q) {
                    $q->where('status', 'dicairkan')
                      ->where('dicairkan_at', '<', now()->subDays(30));
                })
                ->whereDoesntHave('laporanPertanggungJawaban')
                ->get(),

            'pending_refund' => Refund::with(['pengajuanDana.divisi'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get(),
        ];
    }
}