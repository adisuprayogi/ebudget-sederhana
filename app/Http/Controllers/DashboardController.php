<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Divisi;
use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\Refund;
use App\Models\Approval;
use App\Models\PeriodeAnggaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Superadmin has its own simple dashboard
        if ($user->hasRole('superadmin')) {
            return view('dashboard.superadmin');
        }

        if ($user->hasRole('direktur_utama')) {
            $data = $this->getDirekturUtamaData();
            return view('dashboard.direktur-utama', compact('data'));
        }

        if ($user->hasRole('direktur_keuangan')) {
            $data = $this->getDirekturKeuanganData();
            return view('dashboard.direktur-keuangan', compact('data'));
        }

        if ($user->hasRole('kepala_divisi')) {
            $data = $this->getKepalaDivisiData($user);
            return view('dashboard.kepala-divisi', compact('data'));
        }

        if ($user->hasRole('staff_divisi')) {
            $data = $this->getStaffDivisiData($user);
            return view('dashboard.staff-divisi', compact('data'));
        }

        if ($user->hasRole('staff_keuangan')) {
            $data = $this->getStaffKeuanganData();
            return view('dashboard.staff-keuangan', compact('data'));
        }

        return view('dashboard.default');
    }

    /**
     * Get data for Direktur Utama dashboard
     */
    public function getDirekturUtamaData(): array
    {
        // Get active periode anggaran
        $activePeriode = PeriodeAnggaran::active()->first();

        // Pagu anggaran statistics
        $totalPagu = Divisi::sum('total_pagu');
        $terpakai = Divisi::sum('terpakai');
        $sisaPagu = $totalPagu - $terpakai;

        // Pengajuan statistics
        $pengajuanMenunggu = PengajuanDana::where('status', 'menunggu_approval')->count();
        $pengajuanDisetujui = PengajuanDana::where('status', 'disetujui')->count();
        $pengajuanDitolak = PengajuanDana::where('status', 'ditolak')->count();
        $pengajuanTotal = PengajuanDana::count();

        // Pencairan statistics
        $pencairanPending = PencairanDana::where('status', 'menunggu_verifikasi')->count();
        $pencairanApproved = PencairanDana::where('status', 'approved')->count();
        $pencairanProcessed = PencairanDana::where('status', 'processed')->count();
        $pencairanTotal = PencairanDana::count();

        // LPJ statistics
        $lpjPending = LaporanPertanggungJawaban::where('status', 'menunggu_verifikasi')->count();
        $lpjApproved = LaporanPertanggungJawaban::where('status', 'approved')->count();
        $lpjRevisi = LaporanPertanggungJawaban::where('status', 'revisi')->count();
        $lpjTotal = LaporanPertanggungJawaban::count();

        // Refund statistics
        $refundPending = Refund::where('status', 'menunggu_approval')->count();
        $refundApproved = Refund::where('status', 'approved')->count();
        $refundProcessed = Refund::where('status', 'processed')->count();

        // High value pengajuan (above threshold)
        $highValuePengajuan = PengajuanDana::where('total_pengajuan', '>', 50000000)
            ->with(['divisi', 'createdBy'])
            ->latest()
            ->take(5)
            ->get();

        // Pending approvals by role
        $pendingApprovals = Approval::with(['pengajuanDana.divisi', 'approver'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        // Recent activities
        $recentPengajuan = PengajuanDana::with(['divisi', 'createdBy'])
            ->latest()
            ->take(5)
            ->get();

        $divisis = Divisi::withCount([
            'pengajuanDana' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }
        ])->get();

        // Monthly statistics
        $monthlyPengajuan = PengajuanDana::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_pengajuan) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return compact(
            'activePeriode',
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanTotal',
            'pencairanPending',
            'pencairanApproved',
            'pencairanProcessed',
            'pencairanTotal',
            'lpjPending',
            'lpjApproved',
            'lpjRevisi',
            'lpjTotal',
            'refundPending',
            'refundApproved',
            'refundProcessed',
            'highValuePengajuan',
            'pendingApprovals',
            'recentPengajuan',
            'divisis',
            'monthlyPengajuan'
        );
    }

    /**
     * Get data for Direktur Keuangan dashboard
     */
    private function getDirekturKeuanganData(): array
    {
        $activePeriode = PeriodeAnggaran::active()->first();

        // Pagu statistics from PenetapanPagu for active periode
        $penetapanPaguQuery = \App\Models\PenetapanPagu::query();

        if ($activePeriode) {
            $penetapanPaguQuery->where('periode_anggaran_id', $activePeriode->id);
        }

        $penetapanPagus = $penetapanPaguQuery->with('divisi')->get();

        $totalPagu = $penetapanPagus->sum('jumlah_pagu');

        // Calculate real-time terpakai from approved pengajuan
        $terpakaiQuery = PengajuanDana::where('status', 'disetujui');

        // If there's an active periode, filter by it (if pengajuan_danas has periode_anggaran_id)
        // For now, we'll use all approved pengajuan
        $terpakai = $terpakaiQuery->sum('total_pengajuan');
        $sisaPagu = $totalPagu - $terpakai;

        // Pengajuan statistics
        $pengajuanMenunggu = PengajuanDana::where('status', 'menunggu_approval')->count();
        $pengajuanDisetujui = PengajuanDana::where('status', 'disetujui')->count();
        $pengajuanDitolak = PengajuanDana::where('status', 'ditolak')->count();
        $pengajuanTotal = PengajuanDana::count();

        // Pencairan statistics
        $pencairanPending = PencairanDana::where('status', 'menunggu_verifikasi')->count();
        $pencairanProcessed = PencairanDana::where('status', 'processed')->count();
        $totalPencairanHariIni = PencairanDana::where('status', 'processed')
            ->whereDate('processed_at', today())
            ->sum('jumlah_pencairan');

        // LPJ statistics for verification
        $lpjPending = LaporanPertanggungJawaban::where('status', 'menunggu_verifikasi')->count();
        $lpjRevisi = LaporanPertanggungJawaban::where('status', 'revisi')->count();

        // Refund statistics for verification
        $refundPending = Refund::where('status', 'menunggu_approval')->count();

        // Pencairan that need verification
        $pencairanNeedVerification = PencairanDana::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy'])
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->take(10)
            ->get();

        // LPJ that need verification
        $lpjNeedVerification = LaporanPertanggungJawaban::with([
            'pencairanDana',
            'pencairanDana.pengajuanDana.divisi',
            'createdBy'
        ])
        ->where('status', 'menunggu_verifikasi')
        ->latest()
        ->take(10)
            ->get();

        // Refund that need verification
        $refundNeedVerification = Refund::with([
            'lpj',
            'lpj.pencairanDana',
            'lpj.pencairanDana.pengajuanDana.divisi',
            'createdBy'
        ])
        ->where('status', 'menunggu_approval')
        ->latest()
        ->take(10)
            ->get();

        // Recent pengajuan
        $recentPengajuan = PengajuanDana::with(['divisi', 'createdBy'])
            ->latest()
            ->take(5)
            ->get();

        // Get divisis with their pagu from PenetapanPagu for the active period
        $divisis = Divisi::with(['penetapanPagus' => function($query) use ($activePeriode) {
            if ($activePeriode) {
                $query->where('periode_anggaran_id', $activePeriode->id);
            }
        }])->withCount(['pengajuanDana' => function($query) {
            $query->whereMonth('created_at', now()->month);
        }])->get();

        // Add pagu and real-time terpakai to each divisi for overview
        $divisisWithData = $divisis->map(function ($divisi) use ($activePeriode) {
            // Get pagu from PenetapanPagu for active period
            $penetapanPagu = $divisi->penetapanPagus->first();
            $divisi->pagu_periode = $penetapanPagu ? $penetapanPagu->jumlah_pagu : 0;

            // Calculate real-time terpakai from approved pengajuan for this divisi
            $divisi->real_time_terpakai = PengajuanDana::where('divisi_id', $divisi->id)
                ->where('status', 'disetujui')
                ->sum('total_pengajuan');

            return $divisi;
        });

        return [
            'activePeriode' => $activePeriode,
            'totalPagu' => $totalPagu,
            'terpakai' => $terpakai,
            'sisaPagu' => $sisaPagu,
            'pengajuanMenunggu' => $pengajuanMenunggu,
            'pengajuanDisetujui' => $pengajuanDisetujui,
            'pengajuanDitolak' => $pengajuanDitolak,
            'pengajuanTotal' => $pengajuanTotal,
            'pencairanPending' => $pencairanPending,
            'pencairanProcessed' => $pencairanProcessed,
            'totalPencairanHariIni' => $totalPencairanHariIni,
            'lpjPending' => $lpjPending,
            'lpjRevisi' => $lpjRevisi,
            'refundPending' => $refundPending,
            'pencairanNeedVerification' => $pencairanNeedVerification,
            'lpjNeedVerification' => $lpjNeedVerification,
            'refundNeedVerification' => $refundNeedVerification,
            'recentPengajuan' => $recentPengajuan,
            'divisis' => $divisisWithData
        ];
    }

    /**
     * Get data for Kepala Divisi dashboard
     */
    private function getKepalaDivisiData($user): array
    {
        $divisi = $user->divisi;

        if (!$divisi) {
            return [];
        }

        $activePeriode = PeriodeAnggaran::active()->first();

        // Pagu statistics for this divisi
        $totalPagu = $divisi->total_pagu;
        $terpakai = $divisi->terpakai;
        $sisaPagu = $divisi->sisa_pagu;

        // Pengajuan statistics for this divisi
        $pengajuanMenunggu = PengajuanDana::where('divisi_id', $divisi->id)
            ->where('status', 'menunggu_approval')
            ->count();
        $pengajuanDisetujui = PengajuanDana::where('divisi_id', $divisi->id)
            ->where('status', 'disetujui')
            ->count();
        $pengajuanDitolak = PengajuanDana::where('divisi_id', $divisi->id)
            ->where('status', 'ditolak')
            ->count();
        $pengajuanTotal = PengajuanDana::where('divisi_id', $divisi->id)->count();

        // Pencairan pending for this divisi
        $pencairanMenunggu = PencairanDana::whereHas('pengajuanDana', function($q) use ($divisi) {
            $q->where('divisi_id', $divisi->id);
        })->where('status', 'menunggu_verifikasi')->count();

        // LPJ statistics for this divisi
        $lpjBelumDibuat = PencairanDana::whereHas('pengajuanDana', function($q) use ($divisi) {
            $q->where('divisi_id', $divisi->id);
        })->where('status', 'processed')
            ->whereDoesntHave('lpjs')
            ->count();

        $lpjMenungguVerifikasi = LaporanPertanggungJawaban::whereHas('pencairanDana.pengajuanDana', function($q) use ($divisi) {
            $q->where('divisi_id', $divisi->id);
        })->where('status', 'menunggu_verifikasi')->count();

        // Refund notifications for this divisi
        $lpjNeedRefund = LaporanPertanggungJawaban::whereHas('pencairanDana.pengajuanDana', function($q) use ($divisi) {
            $q->where('divisi_id', $divisi->id);
        })->where('status', 'approved')
            ->where('sisa_dana', '>', 0)
            ->whereDoesntHave('refunds')
            ->count();

        // Recent pengajuan for this divisi
        $pengajuanDivisi = PengajuanDana::where('divisi_id', $divisi->id)
            ->with(['approvals'])
            ->latest()
            ->take(10)
            ->get();

        // My pengajuan (created by user)
        $myPengajuan = PengajuanDana::where('created_by', $user->id)
            ->with(['approvals'])
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'activePeriode',
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanTotal',
            'pencairanMenunggu',
            'lpjBelumDibuat',
            'lpjMenungguVerifikasi',
            'lpjNeedRefund',
            'pengajuanDivisi',
            'myPengajuan',
            'divisi'
        );
    }

    /**
     * Get data for Staff Divisi dashboard
     */
    private function getStaffDivisiData($user): array
    {
        $divisi = $user->divisi;

        if (!$divisi) {
            return [];
        }

        $activePeriode = PeriodeAnggaran::active()->first();

        // Pagu statistics for this divisi
        $totalPagu = $divisi->total_pagu;
        $terpakai = $divisi->terpakai;
        $sisaPagu = $divisi->sisa_pagu;

        // My pengajuan statistics
        $myPengajuan = PengajuanDana::where('created_by', $user->id)
            ->with(['approvals', 'divisi'])
            ->latest()
            ->take(10)
            ->get();

        $pengajuanCount = $myPengajuan->count();
        $pengajuanDraft = $myPengajuan->where('status', 'draft')->count();
        $pengajuanMenunggu = $myPengajuan->where('status', 'menunggu_approval')->count();
        $pengajuanDisetujui = $myPengajuan->where('status', 'disetujui')->count();
        $pengajuanDitolak = $myPengajuan->where('status', 'ditolak')->count();

        // Pencairan that need LPJ
        $pencairanNeedLpj = PencairanDana::where('created_by', $user->id)
            ->where('status', 'processed')
            ->whereDoesntHave('lpjs')
            ->with(['pengajuanDana'])
            ->get();

        // LPJ notifications
        $lpjMenungguVerifikasi = LaporanPertanggungJawaban::where('created_by', $user->id)
            ->where('status', 'menunggu_verifikasi')
            ->count();

        $lpjRevisi = LaporanPertanggungJawaban::where('created_by', $user->id)
            ->where('status', 'revisi')
            ->count();

        // Refund notifications
        $lpjNeedRefund = LaporanPertanggungJawaban::whereHas('pencairanDana.pengajuanDana', function($q) use ($divisi) {
            $q->where('divisi_id', $divisi->id);
        })->where('status', 'approved')
            ->where('sisa_dana', '>', 0)
            ->whereDoesntHave('refunds')
            ->count();

        return compact(
            'activePeriode',
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanCount',
            'pengajuanDraft',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'myPengajuan',
            'pencairanNeedLpj',
            'lpjMenungguVerifikasi',
            'lpjRevisi',
            'lpjNeedRefund',
            'divisi'
        );
    }

    /**
     * Get data for Staff Keuangan dashboard
     */
    private function getStaffKeuanganData(): array
    {
        $activePeriode = PeriodeAnggaran::active()->first();

        // Pencairan statistics
        $pencairanPending = PencairanDana::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy'])
            ->where('status', 'menunggu_verifikasi')
            ->get();

        $pencairanProcessedToday = PencairanDana::where('status', 'processed')
            ->whereDate('processed_at', today())
            ->count();

        $totalPencairanHariIni = PencairanDana::where('status', 'processed')
            ->whereDate('processed_at', today())
            ->sum('jumlah_pencairan');

        // Pengajuan that need processing (approved but no pencairan)
        $pengajuanNeedProcessing = PengajuanDana::where('status', 'disetujui')
            ->whereDoesntHave('pencairanDana')
            ->with(['divisi', 'createdBy'])
            ->get();

        // Total amount of pengajuan that need processing
        $totalPengajuanNeedProcessing = $pengajuanNeedProcessing->sum('total_pengajuan');
        $countPengajuanNeedProcessing = $pengajuanNeedProcessing->count();

        // LPJ verification statistics
        $lpjPending = LaporanPertanggungJawaban::where('status', 'menunggu_verifikasi')
            ->with(['pencairanDana', 'pencairanDana.pengajuanDana.divisi', 'createdBy'])
            ->latest()
            ->take(10)
            ->get();

        $lpjRevisi = LaporanPertanggungJawaban::where('status', 'revisi')->count();

        // Refund verification statistics
        $refundPending = Refund::where('status', 'menunggu_approval')
            ->with(['lpj', 'lpj.pencairanDana.pengajuanDana.divisi', 'createdBy'])
            ->latest()
            ->take(10)
            ->get();

        // Total statistics
        $totalPagu = Divisi::sum('total_pagu');
        $totalTerpakai = Divisi::sum('terpakai');

        return compact(
            'activePeriode',
            'pencairanPending',
            'pencairanProcessedToday',
            'totalPencairanHariIni',
            'pengajuanNeedProcessing',
            'totalPengajuanNeedProcessing',
            'countPengajuanNeedProcessing',
            'lpjPending',
            'lpjRevisi',
            'refundPending',
            'totalPagu',
            'totalTerpakai'
        );
    }
}
