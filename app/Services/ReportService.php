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
    public static function getDashboardStatistics($periodeAnggaranId = null, $divisiId = null)
    {
        $queryPengajuan = PengajuanDana::query();
        $queryPencairan = PencairanDana::query();
        $queryLpj = LaporanPertanggungJawaban::query();
        $queryRefund = Refund::query();

        // Apply periode anggaran filter through program_kerja / sub_program
        if ($periodeAnggaranId) {
            $queryPengajuan->where(function ($q) use ($periodeAnggaranId) {
                $q->whereHas('programKerja', function ($subQ) use ($periodeAnggaranId) {
                    $subQ->where('periode_anggaran_id', $periodeAnggaranId);
                })->orWhereHas('subProgram', function ($subQ) use ($periodeAnggaranId) {
                    $subQ->where('periode_anggaran_id', $periodeAnggaranId);
                });
            });

            $queryPencairan->whereHas('pengajuanDana', function ($q) use ($periodeAnggaranId) {
                $q->where(function ($subQ) use ($periodeAnggaranId) {
                    $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    });
                });
            });

            $queryLpj->whereHas('pengajuanDana', function ($q) use ($periodeAnggaranId) {
                $q->where(function ($subQ) use ($periodeAnggaranId) {
                    $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    });
                });
            });

            $queryRefund->whereHas('pengajuanDana', function ($q) use ($periodeAnggaranId) {
                $q->where(function ($subQ) use ($periodeAnggaranId) {
                    $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    });
                });
            });
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

        // Get counts by status
        $pengajuanByStatus = (clone $queryPengajuan)->get()->groupBy('status')->map->count();
        $lpjByStatus = (clone $queryLpj)->get()->groupBy('status')->map->count();

        return [
            'total_pengajuan' => $queryPengajuan->count(),
            'total_nominal_pengajuan' => (clone $queryPengajuan)->sum('total_pengajuan'),
            'pengajuan_approved' => $pengajuanByStatus['selesai'] ?? 0,
            'pengajuan_pending' => $pengajuanByStatus['menunggu_lpj'] ?? 0,
            'pengajuan_rejected' => $pengajuanByStatus['ditolak'] ?? 0,

            'total_pencairan' => $queryPencairan->count(),
            'total_nominal_pencairan' => $queryPencairan->sum('jumlah_pencairan'),

            'total_lpj' => $queryLpj->count(),
            'lpj_pending' => $lpjByStatus['pending'] ?? 0,
            'lpj_approved' => $lpjByStatus['approved'] ?? 0,

            'total_refund' => $queryRefund->count(),
            'total_nominal_refund' => $queryRefund->sum('jumlah_refund'),

            // Nested data for detailed analysis
            'pengajuan' => [
                'by_status' => $pengajuanByStatus,
                'by_jenis' => (clone $queryPengajuan)->get()->groupBy('jenis_pengajuan')->map->count(),
                'by_divisi' => (clone $queryPengajuan)->with('divisi')->get()
                    ->groupBy('divisi.nama_divisi')
                    ->map(function ($group) {
                        return [
                            'count' => $group->count(),
                            'total' => $group->sum('total_pengajuan')
                        ];
                    }),
            ],
            'pencairan' => [
                'by_status' => (clone $queryPencairan)->get()->groupBy('status')->map->count(),
                'by_metode' => (clone $queryPencairan)->get()->groupBy('metode_pencairan')->map->count(),
            ],
            'lpj' => [
                'total_digunakan' => (clone $queryLpj)->sum('total_digunakan'),
                'total_sisa' => (clone $queryLpj)->sum('sisa_dana'),
                'by_status' => $lpjByStatus,
            ],
            'refund' => [
                'by_jenis' => (clone $queryRefund)->get()->groupBy('jenis_refund')->map->count(),
                'by_status' => (clone $queryRefund)->get()->groupBy('status')->map->count(),
            ],
        ];
    }

    /**
     * Get budget realization report
     */
    public static function getBudgetRealization($periodeAnggaranId = null, $divisiId = null)
    {
        // Start with all divisis (or specific divisi if filtered)
        $divisis = Divisi::when($divisiId, function ($q) use ($divisiId) {
            return $q->where('id', $divisiId);
        })->get();

        // Get PenetapanPagu for the periode (indexed by divisi_id for easy lookup)
        $paguMap = PenetapanPagu::when($periodeAnggaranId, function ($q) use ($periodeAnggaranId) {
                return $q->where('periode_anggaran_id', $periodeAnggaranId);
            })
            ->when($divisiId, function ($q) use ($divisiId) {
                return $q->where('divisi_id', $divisiId);
            })
            ->get()
            ->keyBy('divisi_id');

        $report = [];

        foreach ($divisis as $divisi) {
            $pagu = $paguMap->get($divisi->id);
            $jumlahPagu = $pagu ? $pagu->jumlah_pagu : 0;

            // Get total pengajuan for this divisi and periode
            $totalPengajuan = PengajuanDana::where('divisi_id', $divisi->id)
                ->where('status', '!=', 'ditolak')
                ->when($periodeAnggaranId, function ($q) use ($periodeAnggaranId) {
                    return $q->where(function ($subQ) use ($periodeAnggaranId) {
                        $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        });
                    });
                })
                ->sum('total_pengajuan');

            // Get total pencairan for this divisi and periode
            $totalPencairan = PencairanDana::whereHas('pengajuanDana', function ($q) use ($divisi, $periodeAnggaranId) {
                $q->where('divisi_id', $divisi->id)
                  ->when($periodeAnggaranId, function ($subQ) use ($periodeAnggaranId) {
                      return $subQ->where(function ($ss) use ($periodeAnggaranId) {
                          $ss->whereHas('programKerja', function ($sss) use ($periodeAnggaranId) {
                              $sss->where('periode_anggaran_id', $periodeAnggaranId);
                          })->orWhereHas('subProgram', function ($sss) use ($periodeAnggaranId) {
                              $sss->where('periode_anggaran_id', $periodeAnggaranId);
                          });
                      });
                  });
            })->sum('jumlah_pencairan');

            // Get total LPJ used for this divisi and periode
            $totalDigunakan = LaporanPertanggungJawaban::whereHas('pengajuanDana', function ($q) use ($divisi, $periodeAnggaranId) {
                $q->where('divisi_id', $divisi->id)
                  ->when($periodeAnggaranId, function ($subQ) use ($periodeAnggaranId) {
                      return $subQ->where(function ($ss) use ($periodeAnggaranId) {
                          $ss->whereHas('programKerja', function ($sss) use ($periodeAnggaranId) {
                              $sss->where('periode_anggaran_id', $periodeAnggaranId);
                          })->orWhereHas('subProgram', function ($sss) use ($periodeAnggaranId) {
                              $sss->where('periode_anggaran_id', $periodeAnggaranId);
                          });
                      });
                  });
            })->sum('total_digunakan');

            $report[] = [
                'divisi' => $divisi->nama_divisi,
                'pagu' => $jumlahPagu,
                'total_pengajuan' => $totalPengajuan,
                'total_pencairan' => $totalPencairan,
                'total_digunakan' => $totalDigunakan,
                'sisa_pagu' => $jumlahPagu - $totalPengajuan,
                'persentase_pengajuan' => $jumlahPagu > 0
                    ? ($totalPengajuan / $jumlahPagu) * 100
                    : 0,
                'persentase_pencairan' => $jumlahPagu > 0
                    ? ($totalPencairan / $jumlahPagu) * 100
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
    public static function getMonthlyTrend($tahun = null, $divisiId = null, $periodeAnggaranId = null)
    {
        $tahun = $tahun ?? now()->year;

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create($tahun, $i, 1);

            // Build query for pengajuan
            $pengajuanQuery = PengajuanDana::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->when($divisiId, function ($q) use ($divisiId) {
                    return $q->where('divisi_id', $divisiId);
                })
                ->when($periodeAnggaranId, function ($q) use ($periodeAnggaranId) {
                    return $q->where(function ($subQ) use ($periodeAnggaranId) {
                        $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        });
                    });
                });

            // Build query for pencairan
            $pencairanQuery = PencairanDana::whereYear('tanggal_pencairan', $tahun)
                ->whereMonth('tanggal_pencairan', $i)
                ->when($divisiId, function ($q) use ($divisiId) {
                    return $q->whereHas('pengajuanDana', function ($subQ) use ($divisiId) {
                        return $subQ->where('divisi_id', $divisiId);
                    });
                })
                ->when($periodeAnggaranId, function ($q) use ($periodeAnggaranId) {
                    return $q->whereHas('pengajuanDana', function ($subQ) use ($periodeAnggaranId) {
                        return $subQ->where(function ($ss) use ($periodeAnggaranId) {
                            $ss->whereHas('programKerja', function ($sss) use ($periodeAnggaranId) {
                                $sss->where('periode_anggaran_id', $periodeAnggaranId);
                            })->orWhereHas('subProgram', function ($sss) use ($periodeAnggaranId) {
                                $sss->where('periode_anggaran_id', $periodeAnggaranId);
                            });
                        });
                    });
                });

            $months[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->format('F'),
                'pengajuan_count' => (clone $pengajuanQuery)->count(),
                'pengajuan_total' => (clone $pengajuanQuery)->sum('total_pengajuan'),
                'pencairan_count' => (clone $pencairanQuery)->count(),
                'pencairan_total' => (clone $pencairanQuery)->sum('jumlah_pencairan'),
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
    public static function getDivisionComparison($tahun = null, $periodeAnggaranId = null)
    {
        $tahun = $tahun ?? now()->year;

        // Build base query for pengajuan
        $pengajuanQuery = PengajuanDana::whereYear('created_at', $tahun)
            ->when($periodeAnggaranId, function ($q) use ($periodeAnggaranId) {
                return $q->where(function ($subQ) use ($periodeAnggaranId) {
                    $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    });
                });
            });

        // Get all divisis with their pengajuan
        $divisis = Divisi::with(['pengajuanDana' => function ($q) use ($tahun, $periodeAnggaranId) {
            $q->whereYear('created_at', $tahun)
                ->when($periodeAnggaranId, function ($query) use ($periodeAnggaranId) {
                    return $query->where(function ($subQ) use ($periodeAnggaranId) {
                        $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                            $ss->where('periode_anggaran_id', $periodeAnggaranId);
                        });
                    });
                });
        }])->get();

        $comparison = [];

        foreach ($divisis as $divisi) {
            $pengajuanCount = $divisi->pengajuanDana->count();
            $totalPengajuan = $divisi->pengajuanDana->sum('total_pengajuan');
            $approvedCount = $divisi->pengajuanDana->where('status', 'disetujui')->count();
            $rejectedCount = $divisi->pengajuanDana->where('status', 'ditolak')->count();

            // Get pencairan data with periode filter
            $pencairanQuery = PencairanDana::whereHas('pengajuanDana', function ($q) use ($divisi, $tahun, $periodeAnggaranId) {
                $q->where('divisi_id', $divisi->id)
                  ->whereYear('created_at', $tahun)
                  ->when($periodeAnggaranId, function ($query) use ($periodeAnggaranId) {
                      return $query->where(function ($subQ) use ($periodeAnggaranId) {
                          $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                              $ss->where('periode_anggaran_id', $periodeAnggaranId);
                          })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                              $ss->where('periode_anggaran_id', $periodeAnggaranId);
                          });
                      });
                  });
            });

            $pencairanTotal = $pencairanQuery->sum('jumlah_pencairan');

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
            ->with(['pencairanDana', 'laporanPertanggungJawabans'])
            ->get()
            ->groupBy('jenis_pengajuan');

        $analysis = [];

        foreach ($pengajuanByJenis as $jenis => $pengajuans) {
            $totalPengajuan = $pengajuans->sum('total_pengajuan');
            $totalPencairan = $pengajuans->sum(function ($p) {
                return $p->pencairanDana?->jumlah_pencairan ?? 0;
            });
            $totalDigunakan = $pengajuans->sum(function ($p) {
                return $p->laporanPertanggungJawabans?->first()?->total_digunakan ?? 0;
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
    public static function getExecutiveSummary($tahun = null, $periodeAnggaranId = null)
    {
        $tahun = $tahun ?? now()->year;

        $stats = self::getDashboardStatistics($periodeAnggaranId);

        $budgetRealization = self::getBudgetRealization($tahun);
        $monthlyTrend = self::getMonthlyTrend($tahun, null, $periodeAnggaranId);
        $divisionComparison = self::getDivisionComparison($tahun, $periodeAnggaranId);
        $approvalPerformance = self::getApprovalPerformance(
            Carbon::create($tahun, 1, 1),
            Carbon::create($tahun, 12, 31)
        );

        return [
            'tahun' => $tahun,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'key_metrics' => [
                'total_pengajuan' => $stats['total_pengajuan'],
                'total_nominal_pengajuan' => $stats['total_nominal_pengajuan'],
                'total_pencairan' => $stats['total_pencairan'],
                'total_nominal_pencairan' => $stats['total_nominal_pencairan'],
                'approval_rate' => $stats['pengajuan_approved'] ?? 0,
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
                    $filters['periode_anggaran_id'] ?? null,
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
                    $filters['divisi_id'] ?? null,
                    $filters['periode_anggaran_id'] ?? null
                );

            case 'division_comparison':
                return self::getDivisionComparison(
                    $filters['tahun'] ?? null,
                    $filters['periode_anggaran_id'] ?? null
                );

            case 'executive_summary':
                return self::getExecutiveSummary(
                    $filters['tahun'] ?? null,
                    $filters['periode_anggaran_id'] ?? null
                );

            default:
                throw new \Exception("Unknown report type: {$type}");
        }
    }

    /**
     * Get high value transactions report
     */
    public static function getHighValueTransactions($threshold = 100000000, $periodeAnggaranId = null)
    {
        $query = PengajuanDana::with(['divisi', 'createdBy', 'approvals.approver'])
            ->where('total_pengajuan', '>=', $threshold);

        // Apply periode anggaran filter through program_kerja / sub_program
        if ($periodeAnggaranId) {
            $query->where(function ($q) use ($periodeAnggaranId) {
                $q->whereHas('programKerja', function ($subQ) use ($periodeAnggaranId) {
                    $subQ->where('periode_anggaran_id', $periodeAnggaranId);
                })->orWhereHas('subProgram', function ($subQ) use ($periodeAnggaranId) {
                    $subQ->where('periode_anggaran_id', $periodeAnggaranId);
                });
            });
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

            'overdue_lpj' => PengajuanDana::with(['divisi'])
                ->where('status', 'dicairkan')
                ->where('dicairkan_at', '<', now()->subDays(30))
                ->whereDoesntHave('laporanPertanggungJawabans')
                ->get(),

            'pending_refund' => Refund::with(['pengajuanDana.divisi'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get(),
        ];
    }
}