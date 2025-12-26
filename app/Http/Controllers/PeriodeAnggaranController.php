<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAnggaran;
use App\Services\PeriodeAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PeriodeAnggaranController extends Controller
{
    /**
     * Display a listing of the periode anggaran.
     */
    public function index(Request $request): View
    {
        $query = PeriodeAnggaran::with(['createdBy', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun_anggaran', $request->tahun);
        }

        // Filter by fase (computed)
        if ($request->filled('fase')) {
            $query->fase($request->fase);
        }

        $periodes = $query->orderBy('tahun_anggaran', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get current periode
        $currentPeriode = PeriodeAnggaranService::getCurrentPeriode();

        return view('periode-anggaran.index', compact('periodes', 'currentPeriode'));
    }

    /**
     * Show the form for creating a new periode anggaran.
     */
    public function create(): View
    {
        return view('periode-anggaran.create');
    }

    /**
     * Store a newly created periode anggaran.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:200',
            'tahun_anggaran' => 'required|integer|min:2020|max:2100|unique:periode_anggaran,tahun_anggaran',
            'tanggal_mulai_perencanaan_anggaran' => 'required|date|before:tanggal_selesai_perencanaan_anggaran',
            'tanggal_selesai_perencanaan_anggaran' => 'required|date|before:tanggal_mulai_penggunaan_anggaran',
            'tanggal_mulai_penggunaan_anggaran' => 'required|date|before:tanggal_selesai_penggunaan_anggaran',
            'tanggal_selesai_penggunaan_anggaran' => 'required|date|after:tanggal_mulai_penggunaan_anggaran',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        try {
            $periode = PeriodeAnggaranService::createPeriode([
                ...$validated,
                'created_by' => auth()->id(),
            ]);

            return redirect()
                ->route('periode-anggaran.show', $periode)
                ->with('success', 'Periode anggaran berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat periode anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified periode anggaran.
     */
    public function show(PeriodeAnggaran $periodeAnggaran): View
    {
        $periodeAnggaran->load(['createdBy', 'approvedBy']);

        // Get statistics
        $statistics = $periodeAnggaran->getStatistics();
        $divisiStatistics = $periodeAnggaran->getDivisiStatistics();
        $monthlyTrend = $periodeAnggaran->getMonthlyTrend();
        $warnings = $periodeAnggaran->getWarnings();

        return view('periode-anggaran.show', compact(
            'periodeAnggaran',
            'statistics',
            'divisiStatistics',
            'monthlyTrend',
            'warnings'
        ));
    }

    /**
     * Show the form for editing the specified periode anggaran.
     */
    public function edit(PeriodeAnggaran $periodeAnggaran): View
    {
        // Only allow editing draft periodes
        if ($periodeAnggaran->status !== 'draft') {
            abort(403, 'Hanya periode dengan status draft yang dapat diedit.');
        }

        return view('periode-anggaran.edit', compact('periodeAnggaran'));
    }

    /**
     * Update the specified periode anggaran.
     */
    public function update(Request $request, PeriodeAnggaran $periodeAnggaran): RedirectResponse
    {
        // Only allow updating draft periodes
        if ($periodeAnggaran->status !== 'draft') {
            return back()->with('error', 'Hanya periode dengan status draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'nama_periode' => 'required|string|max:200',
            'tanggal_mulai_perencanaan_anggaran' => 'required|date|before:tanggal_selesai_perencanaan_anggaran',
            'tanggal_selesai_perencanaan_anggaran' => 'required|date|before:tanggal_mulai_penggunaan_anggaran',
            'tanggal_mulai_penggunaan_anggaran' => 'required|date|before:tanggal_selesai_penggunaan_anggaran',
            'tanggal_selesai_penggunaan_anggaran' => 'required|date|after:tanggal_mulai_penggunaan_anggaran',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        try {
            $periodeAnggaran->update($validated);

            return redirect()
                ->route('periode-anggaran.show', $periodeAnggaran)
                ->with('success', 'Periode anggaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui periode anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified periode anggaran.
     */
    public function destroy(PeriodeAnggaran $periodeAnggaran): RedirectResponse
    {
        // Only allow deleting draft periodes with no related data
        if ($periodeAnggaran->status !== 'draft') {
            return back()->with('error', 'Hanya periode dengan status draft yang dapat dihapus.');
        }

        // Check for related data
        if ($periodeAnggaran->programKerjas()->count() > 0 ||
            $periodeAnggaran->pengajuanDan()->count() > 0 ||
            $periodeAnggaran->pencairanDan()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus periode yang sudah memiliki data terkait.');
        }

        try {
            $periodeAnggaran->delete();

            return redirect()
                ->route('periode-anggaran.index')
                ->with('success', 'Periode anggaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus periode anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Activate a periode anggaran.
     */
    public function activate(PeriodeAnggaran $periodeAnggaran): RedirectResponse
    {
        if ($periodeAnggaran->status !== 'draft') {
            return back()->with('error', 'Hanya periode dengan status draft yang dapat diaktifkan.');
        }

        try {
            PeriodeAnggaranService::transitionFase($periodeAnggaran->id, 'active', auth()->user());

            return redirect()
                ->route('periode-anggaran.show', $periodeAnggaran)
                ->with('success', 'Periode anggaran berhasil diaktifkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengaktifkan periode anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Close a periode anggaran.
     */
    public function close(PeriodeAnggaran $periodeAnggaran): RedirectResponse
    {
        if ($periodeAnggaran->status === 'closed') {
            return back()->with('error', 'Periode anggaran sudah ditutup.');
        }

        try {
            PeriodeAnggaranService::transitionFase($periodeAnggaran->id, 'closed', auth()->user());

            return redirect()
                ->route('periode-anggaran.show', $periodeAnggaran)
                ->with('success', 'Periode anggaran berhasil ditutup.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menutup periode anggaran: ' . $e->getMessage());
        }
    }

    /**
     * Get periode options for dropdown (AJAX).
     */
    public function options(Request $request)
    {
        $status = $request->query('status');
        $periodes = PeriodeAnggaranService::getPeriodeOptions($status);

        return response()->json($periodes);
    }

    /**
     * Get dashboard summary.
     */
    public function dashboardSummary()
    {
        $summary = PeriodeAnggaranService::getDashboardSummary();

        return response()->json($summary);
    }

    /**
     * Show statistics for a periode.
     */
    public function statistics(PeriodeAnggaran $periodeAnggaran)
    {
        $statistics = $periodeAnggaran->getStatistics();
        $divisiStatistics = $periodeAnggaran->getDivisiStatistics();
        $monthlyTrend = $periodeAnggaran->getMonthlyTrend();

        return response()->json([
            'statistics' => $statistics,
            'divisi_statistics' => $divisiStatistics,
            'monthly_trend' => $monthlyTrend,
        ]);
    }
}
