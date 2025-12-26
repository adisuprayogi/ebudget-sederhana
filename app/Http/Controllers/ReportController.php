<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengajuanExport;
use App\Exports\PencairanExport;
use App\Exports\LpjExport;
use App\Exports\RefundExport;

class ReportController extends Controller
{
    /**
     * Display the main reports page.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        // Get quick statistics for current year
        $currentYear = date('Y');
        $dashboardStats = ReportService::getDashboardStatistics(
            "$currentYear-01-01",
            "$currentYear-12-31",
            $user->hasPermission('report.view_all') ? null : $user->divisi_id
        );

        return view('reports.index', [
            'dashboardStats' => $dashboardStats,
            'currentYear' => $currentYear,
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display pengajuan reports.
     */
    public function pengajuan(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : $user->divisi_id;

        // Get comprehensive pengajuan report data
        $startDate = $request->tanggal_mulai ?? "$tahun-01-01";
        $endDate = $request->tanggal_selesai ?? "$tahun-12-31";

        $statistics = ReportService::getDashboardStatistics($startDate, $endDate, $divisiId);
        $monthlyTrend = ReportService::getMonthlyTrend($tahun, $divisiId);
        $jenisAnalysis = ReportService::getJenisPengajuanAnalysis($tahun);
        $divisionComparison = $user->hasPermission('report.view_all') ?
            ReportService::getDivisionComparison($tahun) : [];
        $highValueTransactions = ReportService::getHighValueTransactions(
            $request->threshold ?? 100000000,
            $startDate,
            $endDate
        );

        // Get filter options
        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();
        $years = range(date('Y') - 5, date('Y'));

        return view('reports.pengajuan', [
            'statistics' => $statistics,
            'monthlyTrend' => $monthlyTrend,
            'jenisAnalysis' => $jenisAnalysis,
            'divisionComparison' => $divisionComparison,
            'highValueTransactions' => $highValueTransactions,
            'filters' => $request->only(['tahun', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai', 'threshold']),
            'filterOptions' => [
                'divisis' => $divisis,
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display pencairan reports.
     */
    public function pencairan(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : $user->divisi_id;

        $startDate = $request->tanggal_mulai ?? "$tahun-01-01";
        $endDate = $request->tanggal_selesai ?? "$tahun-12-31";

        $statistics = ReportService::getDashboardStatistics($startDate, $endDate, $divisiId);
        $monthlyTrend = ReportService::getMonthlyTrend($tahun, $divisiId);
        $divisionComparison = $user->hasPermission('report.view_all') ?
            ReportService::getDivisionComparison($tahun) : [];

        // Get pencairan-specific data
        $pencairanStats = \App\Services\PencairanService::getPencairanStatistics($startDate, $endDate, $divisiId);
        $upcomingPencairans = \App\Services\PencairanService::getUpcomingPencairan();

        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();
        $years = range(date('Y') - 5, date('Y'));

        return view('reports.pencairan', [
            'statistics' => $statistics,
            'pencairanStats' => $pencairanStats,
            'monthlyTrend' => $monthlyTrend,
            'divisionComparison' => $divisionComparison,
            'upcomingPencairans' => $upcomingPencairans,
            'filters' => $request->only(['tahun', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'divisis' => $divisis,
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display LPJ reports.
     */
    public function lpj(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : $user->divisi_id;

        $startDate = $request->tanggal_mulai ?? "$tahun-01-01";
        $endDate = $request->tanggal_selesai ?? "$tahun-12-31";

        $statistics = ReportService::getDashboardStatistics($startDate, $endDate, $divisiId);
        $lpjStats = \App\Services\LpjService::getLpjStatistics($startDate, $endDate, $divisiId);
        $monthlyTrend = ReportService::getMonthlyTrend($tahun, $divisiId);
        $divisionComparison = $user->hasPermission('report.view_all') ?
            ReportService::getDivisionComparison($tahun) : [];

        // Get LPJ-specific data
        $overdueLpj = \App\Services\LpjService::getOverdueLpj();

        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();
        $years = range(date('Y') - 5, date('Y'));

        return view('reports.lpj', [
            'statistics' => $statistics,
            'lpjStats' => $lpjStats,
            'monthlyTrend' => $monthlyTrend,
            'divisionComparison' => $divisionComparison,
            'overdueLpj' => $overdueLpj,
            'filters' => $request->only(['tahun', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'divisis' => $divisis,
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display refund reports.
     */
    public function refund(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : $user->divisi_id;

        $startDate = $request->tanggal_mulai ?? "$tahun-01-01";
        $endDate = $request->tanggal_selesai ?? "$tahun-12-31";

        $statistics = ReportService::getDashboardStatistics($startDate, $endDate, $divisiId);
        $monthlyTrend = ReportService::getMonthlyTrend($tahun, $divisiId);
        $divisionComparison = $user->hasPermission('report.view_all') ?
            ReportService::getDivisionComparison($tahun) : [];

        // Get refund-specific data
        $refunds = \App\Models\Refund::with(['pengajuanDana.divisi'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($divisiId, function ($query) use ($divisiId) {
                $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                    $q->where('divisi_id', $divisiId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();
        $years = range(date('Y') - 5, date('Y'));

        return view('reports.refund', [
            'statistics' => $statistics,
            'refunds' => $refunds,
            'monthlyTrend' => $monthlyTrend,
            'divisionComparison' => $divisionComparison,
            'filters' => $request->only(['tahun', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'divisis' => $divisis,
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display budget realization reports.
     */
    public function budgetRealization(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : null;

        $budgetRealization = ReportService::getBudgetRealization($tahun, $divisiId);
        $divisionComparison = $user->hasPermission('report.view_all') ?
            ReportService::getDivisionComparison($tahun) : [];

        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();
        $years = range(date('Y') - 5, date('Y'));

        return view('reports.budget-realization', [
            'budgetRealization' => $budgetRealization,
            'divisionComparison' => $divisionComparison,
            'filters' => $request->only(['tahun', 'divisi_id']),
            'filterOptions' => [
                'divisis' => $divisis,
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display approval performance reports.
     */
    public function approvalPerformance(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $startDate = $request->tanggal_mulai ?? date('Y-m-01');
        $endDate = $request->tanggal_selesai ?? date('Y-m-d');

        $approvalPerformance = ReportService::getApprovalPerformance($startDate, $endDate);

        return view('reports.approval-performance', [
            'approvalPerformance' => $approvalPerformance,
            'filters' => $request->only(['tanggal_mulai', 'tanggal_selesai']),
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display executive summary.
     */
    public function executiveSummary(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        if (!$user->hasPermission('report.executive_summary')) {
            abort(403);
        }

        $tahun = $request->tahun ?? date('Y');
        $executiveSummary = ReportService::getExecutiveSummary($tahun);

        $years = range(date('Y') - 5, date('Y'));

        return view('reports.executive-summary', [
            'executiveSummary' => $executiveSummary,
            'filters' => $request->only(['tahun']),
            'filterOptions' => [
                'years' => $years,
            ],
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Display pending items report.
     */
    public function pendingItems()
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $pendingItems = ReportService::getPendingItemsReport();

        return view('reports.pending-items', [
            'pendingItems' => $pendingItems,
            'permissions' => [
                'view_all' => $user->hasPermission('report.view_all'),
                'export' => $user->hasPermission('report.export'),
            ],
        ]);
    }

    /**
     * Export data to Excel.
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.export')) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:pengajuan,pencairan,lpj,refund,budget_realization',
            'format' => 'required|in:excel,pdf',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'divisi_id' => 'nullable|exists:divisis,id',
            'tahun' => 'nullable|integer|digits:4',
        ]);

        $type = $request->type;
        $format = $request->format;
        $filters = $request->only(['tanggal_mulai', 'tanggal_selesai', 'divisi_id', 'tahun']);

        try {
            switch ($type) {
                case 'pengajuan':
                    return $this->exportPengajuan($filters, $format);
                case 'pencairan':
                    return $this->exportPencairan($filters, $format);
                case 'lpj':
                    return $this->exportLpj($filters, $format);
                case 'refund':
                    return $this->exportRefund($filters, $format);
                case 'budget_realization':
                    return $this->exportBudgetRealization($filters, $format);
                default:
                    throw new \Exception('Invalid export type');
            }
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal mengekspor data. ' . $e->getMessage());
        }
    }

    /**
     * Export pengajuan data
     */
    private function exportPengajuan($filters, $format)
    {
        $data = ReportService::exportReport('pengajuan', $filters);

        if ($format === 'excel') {
            $filename = 'pengajuan-' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PengajuanExport($data), $filename);
        }

        // PDF export implementation
        return response()->json(['message' => 'PDF export coming soon']);
    }

    /**
     * Export pencairan data
     */
    private function exportPencairan($filters, $format)
    {
        $data = ReportService::exportReport('pencairan', $filters);

        if ($format === 'excel') {
            $filename = 'pencairan-' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PencairanExport($data), $filename);
        }

        return response()->json(['message' => 'PDF export coming soon']);
    }

    /**
     * Export LPJ data
     */
    private function exportLpj($filters, $format)
    {
        $data = ReportService::exportReport('lpj', $filters);

        if ($format === 'excel') {
            $filename = 'lpj-' . date('Y-m-d') . '.xlsx';
            return Excel::download(new LpjExport($data), $filename);
        }

        return response()->json(['message' => 'PDF export coming soon']);
    }

    /**
     * Export refund data
     */
    private function exportRefund($filters, $format)
    {
        $data = ReportService::exportReport('refund', $filters);

        if ($format === 'excel') {
            $filename = 'refund-' . date('Y-m-d') . '.xlsx';
            return Excel::download(new RefundExport($data), $filename);
        }

        return response()->json(['message' => 'PDF export coming soon']);
    }

    /**
     * Export budget realization data
     */
    private function exportBudgetRealization($filters, $format)
    {
        $data = ReportService::exportReport('budget_realization', $filters);

        if ($format === 'excel') {
            $filename = 'budget-realization-' . ($filters['tahun'] ?? date('Y')) . '.xlsx';
            return Excel::download(new \App\Exports\BudgetRealizationExport($data), $filename);
        }

        return response()->json(['message' => 'PDF export coming soon']);
    }

    /**
     * Get report statistics API
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('report.view')) {
            abort(403);
        }

        $type = $request->type ?? 'dashboard';
        $startDate = $request->tanggal_mulai;
        $endDate = $request->tanggal_selesai;
        $divisiId = $user->hasPermission('report.view_all') ? $request->divisi_id : $user->divisi_id;

        switch ($type) {
            case 'dashboard':
                $data = ReportService::getDashboardStatistics($startDate, $endDate, $divisiId);
                break;
            case 'budget_realization':
                $tahun = $request->tahun ?? date('Y');
                $data = ReportService::getBudgetRealization($tahun, $divisiId);
                break;
            case 'approval_performance':
                $data = ReportService::getApprovalPerformance($startDate, $endDate);
                break;
            default:
                $data = ['error' => 'Invalid report type'];
        }

        return response()->json($data);
    }
}