<?php

namespace App\Http\Controllers;

use App\Models\LaporanPertanggungJawaban;
use App\Models\PencairanDana;
use App\Services\LpjService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LpjController extends Controller
{
    /**
     * Display a listing of LPJ.
     */
    public function index(Request $request): View
    {
        $query = LaporanPertanggungJawaban::with(['pencairanDana', 'pencairanDana.pengajuanDana', 'createdBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by periode anggaran
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        // Filter by divisi
        if ($request->filled('divisi_id')) {
            $query->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        $lpjs = $query->orderBy('created_at', 'desc')->paginate(15);

        $statusOptions = [
            'draft' => 'Draft',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'menunggu_approval' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revisi' => 'Perlu Revisi',
        ];

        return view('lpj.index', compact('lpjs', 'statusOptions'));
    }

    /**
     * Show the form for creating a new LPJ.
     */
    public function create(Request $request): View
    {
        $pencairanId = $request->query('pencairan_id');
        $pencairan = null;

        if ($pencairanId) {
            $pencairan = PencairanDana::with(['pengajuanDana', 'detailPencairans'])
                ->findOrFail($pencairanId);

            // Check if LPJ already exists for this pencairan
            $existingLpj = LaporanPertanggungJawaban::where('pencairan_dana_id', $pencairanId)
                ->first();

            if ($existingLpj) {
                return redirect()
                    ->route('lpj.show', $existingLpj)
                    ->with('info', 'LPJ untuk pencairan ini sudah ada.');
            }
        }

        return view('lpj.create', compact('pencairan'));
    }

    /**
     * Store a newly created LPJ.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pencairan_dana_id' => 'required|exists:pencairan_danas,id',
            'tanggal_lpj' => 'required|date',
            'judul_lpj' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'file_lpj' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240',
            'tanggal_pelaksanaan' => 'nullable|date',
            'lokasi_pelaksanaan' => 'nullable|string|max:200',
        ]);

        try {
            $lpj = LpjService::createLpj([
                ...$validated,
                'created_by' => auth()->id(),
            ], $request->file('file_lpj'));

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', 'LPJ berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified LPJ.
     */
    public function show(LaporanPertanggungJawaban $lpj): View
    {
        $lpj->load([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'pencairanDana.detailPencairans',
            'detailLpjs',
            'createdBy',
            'verifiedBy',
            'approvedBy',
        ]);

        return view('lpj.show', compact('lpj'));
    }

    /**
     * Show the form for editing the specified LPJ.
     */
    public function edit(LaporanPertanggungJawaban $lpj): View
    {
        // Only allow editing draft LPJ or LPJ that needs revision
        if (!in_array($lpj->status, ['draft', 'revisi'])) {
            abort(403, 'Hanya LPJ dengan status draft atau revisi yang dapat diedit.');
        }

        $lpj->load(['pencairanDana', 'detailLpjs']);

        return view('lpj.edit', compact('lpj'));
    }

    /**
     * Update the specified LPJ.
     */
    public function update(Request $request, LaporanPertanggungJawaban $lpj): RedirectResponse
    {
        // Only allow updating draft LPJ or LPJ that needs revision
        if (!in_array($lpj->status, ['draft', 'revisi'])) {
            return back()->with('error', 'Hanya LPJ dengan status draft atau revisi yang dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal_lpj' => 'required|date',
            'judul_lpj' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'file_lpj' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240',
            'tanggal_pelaksanaan' => 'nullable|date',
            'lokasi_pelaksanaan' => 'nullable|string|max:200',
        ]);

        try {
            $lpj = LpjService::updateLpj($lpj->id, $validated, $request->file('file_lpj'));

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', 'LPJ berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Submit LPJ for verification.
     */
    public function submit(LaporanPertanggungJawaban $lpj): RedirectResponse
    {
        if ($lpj->status !== 'draft') {
            return back()->with('error', 'Hanya LPJ dengan status draft yang dapat disubmit.');
        }

        try {
            LpjService::submitLpj($lpj->id);

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', 'LPJ berhasil disubmit untuk verifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mensubmit LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Verify LPJ.
     */
    public function verify(Request $request, LaporanPertanggungJawaban $lpj): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_verifikasi' => 'required_if:status,rejected|string|max:1000',
        ]);

        try {
            LpjService::verifyLpj(
                $lpj->id,
                $validated['status'],
                $validated['catatan_verifikasi'] ?? null,
                auth()->user()
            );

            $message = $validated['status'] === 'approved'
                ? 'LPJ berhasil diverifikasi dan disetujui.'
                : 'LPJ ditolak dan dikembalikan untuk revisi.';

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memverifikasi LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Approve LPJ.
     */
    public function approve(Request $request, LaporanPertanggungJawaban $lpj): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_approval' => 'required_if:status,rejected|string|max:1000',
        ]);

        try {
            LpjService::approveLpj(
                $lpj->id,
                $validated['status'],
                $validated['catatan_approval'] ?? null,
                auth()->user()
            );

            $message = $validated['status'] === 'approved'
                ? 'LPJ berhasil disetujui.'
                : 'LPJ ditolak.';

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified LPJ.
     */
    public function destroy(LaporanPertanggungJawaban $lpj): RedirectResponse
    {
        // Only allow deleting draft LPJ
        if ($lpj->status !== 'draft') {
            return back()->with('error', 'Hanya LPJ dengan status draft yang dapat dihapus.');
        }

        try {
            LpjService::deleteLpj($lpj->id);

            return redirect()
                ->route('lpj.index')
                ->with('success', 'LPJ berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus LPJ: ' . $e->getMessage());
        }
    }

    /**
     * Get LPJ statistics.
     */
    public function statistics(Request $request)
    {
        $periodeId = $request->query('periode_anggaran_id');

        $stats = [
            'total' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->count(),
            'draft' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'draft')->count(),
            'menunggu_verifikasi' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'menunggu_verifikasi')->count(),
            'menunggu_approval' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'menunggu_approval')->count(),
            'approved' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'approved')->count(),
            'rejected' => LaporanPertanggungJawaban::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'rejected')->count(),
        ];

        return response()->json($stats);
    }
}
