<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Divisi;
use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\Approval;
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
        $totalPagu = Divisi::sum('total_pagu');
        $terpakai = Divisi::sum('terpakai');
        $sisaPagu = $totalPagu - $terpakai;

        $pengajuanMenunggu = PengajuanDana::where('status', 'menunggu_approval')->count();
        $pengajuanDisetujui = PengajuanDana::where('status', 'disetujui')->count();
        $pengajuanDitolak = PengajuanDana::where('status', 'ditolak')->count();
        $pencairanPending = PencairanDana::where('status', 'pending')->count();

        // High value pengajuan (above threshold)
        $highValuePengajuan = PengajuanDana::where('total_pengajuan', '>', 50000000)
            ->with(['divisi', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Pending approvals by role
        $pendingApprovals = Approval::with(['pengajuanDana.divisi', 'approver'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $divisis = Divisi::withCount([
            'pengajuanDana' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }
        ])->get();

        return compact(
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pencairanPending',
            'highValuePengajuan',
            'pendingApprovals',
            'divisis'
        );
    }

    /**
     * Get data for Direktur Keuangan dashboard
     */
    private function getDirekturKeuanganData(): array
    {
        $totalPagu = Divisi::sum('total_pagu');
        $terpakai = Divisi::sum('terpakai');
        $sisaPagu = $totalPagu - $terpakai;

        $pengajuanMenunggu = PengajuanDana::where('status', 'menunggu_approval')->count();
        $pengajuanDisetujui = PengajuanDana::where('status', 'disetujui')->count();
        $pencairanPending = PencairanDana::where('status', 'pending')->count();

        $recentPengajuan = PengajuanDana::with(['divisi', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $divisis = Divisi::withCount(['pengajuanDana' => function($query) {
            $query->whereMonth('created_at', now()->month);
        }])->get();

        return compact(
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pencairanPending',
            'recentPengajuan',
            'divisis'
        );
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

        $totalPagu = $divisi->total_pagu;
        $terpakai = $divisi->terpakai;
        $sisaPagu = $divisi->sisa_pagu;

        $pengajuanMenunggu = PengajuanDana::where('divisi_id', $divisi->id)
            ->where('status', 'menunggu_approval')
            ->count();

        $pengajuanDivisi = PengajuanDana::where('divisi_id', $divisi->id)
            ->with(['user', 'approvals'])
            ->latest()
            ->take(10)
            ->get();

        return compact(
            'totalPagu',
            'terpakai',
            'sisaPagu',
            'pengajuanMenunggu',
            'pengajuanDivisi',
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

        $myPengajuan = PengajuanDana::where('created_by', $user->id)
            ->with(['approvals'])
            ->latest()
            ->take(10)
            ->get();

        $pengajuanCount = $myPengajuan->count();
        $pengajuanApproved = $myPengajuan->where('status', 'disetujui')->count();
        $pengajuanPending = $myPengajuan->where('status', 'menunggu_approval')->count();

        $sisaPaguDivisi = $divisi->sisa_pagu;

        return compact(
            'pengajuanCount',
            'pengajuanApproved',
            'pengajuanPending',
            'myPengajuan',
            'sisaPaguDivisi',
            'divisi'
        );
    }

    /**
     * Get data for Staff Keuangan dashboard
     */
    private function getStaffKeuanganData(): array
    {
        $pencairanPending = PencairanDana::where('status', 'pending')
            ->with(['pengajuanDana.divisi'])
            ->get();

        $pencairanApproved = PencairanDana::where('status', 'approved')
            ->whereDate('approved_at', today())
            ->count();

        $totalPencairanHariIni = PencairanDana::where('status', 'approved')
            ->whereDate('approved_at', today())
            ->sum('total_pencairan');

        $pengajuanNeedProcessing = PengajuanDana::where('status', 'disetujui')
            ->whereDoesntHave('pencairanDana')
            ->with(['divisi'])
            ->get();

        return compact(
            'pencairanPending',
            'pencairanApproved',
            'totalPencairanHariIni',
            'pengajuanNeedProcessing'
        );
    }
}
