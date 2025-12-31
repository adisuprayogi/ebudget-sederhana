<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\PencairanDana;
use App\Models\PengajuanDana;
use App\Services\RefundService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RefundController extends Controller
{
    /**
     * Display a listing of refunds.
     */
    public function index(Request $request): View
    {
        // Base query function
        $baseQuery = function ($status) use ($request) {
            $query = Refund::with([
                'pencairanDana',
                'pencairanDana.pengajuanDana',
                'pencairanDana.pengajuanDana.divisi',
                'pengajuanDana',
                'pengajuanDana.divisi',
                'createdBy'
            ])->where('status', $status);

            // Filter by periode anggaran
            if ($request->filled('periode_anggaran_id')) {
                $query->where('periode_anggaran_id', $request->periode_anggaran_id);
            }

            // Filter by divisi
            if ($request->filled('divisi_id')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('pencairanDana.pengajuanDana', function ($sq) use ($request) {
                        $sq->where('divisi_id', $request->divisi_id);
                    })
                    ->orWhereHas('pengajuanDana', function ($sq) use ($request) {
                        $sq->where('divisi_id', $request->divisi_id);
                    });
                });
            }

            // Filter by search keyword
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_refund', 'like', "%{$search}%")
                      ->orWhere('alasan_refund', 'like', "%{$search}%");
                });
            }

            return $query;
        };

        // Fetch data for each status
        $refundsDraft = $baseQuery('draft')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $refundsMenungguApproval = $baseQuery('menunggu_approval')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $refundsApproved = $baseQuery('approved')
            ->orderBy('approved_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $refundsProcessed = $baseQuery('processed')
            ->orderBy('processed_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $refundsRejected = $baseQuery('rejected')
            ->orderBy('approved_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Get statistics
        $stats = [
            'draft' => Refund::where('status', 'draft')->count(),
            'menunggu_approval' => Refund::where('status', 'menunggu_approval')->count(),
            'approved' => Refund::where('status', 'approved')->count(),
            'processed' => Refund::where('status', 'processed')->count(),
            'rejected' => Refund::where('status', 'rejected')->count(),
        ];

        $statistics = [
            'total_count' => array_sum($stats),
            'pending_count' => $stats['menunggu_approval'],
            'approved_count' => $stats['approved'],
            'processed_count' => $stats['processed'],
            'total_amount' => Refund::whereIn('status', ['processed'])->sum('jumlah_refund'),
        ];

        return view('refund.index', compact(
            'refundsDraft',
            'refundsMenungguApproval',
            'refundsApproved',
            'refundsProcessed',
            'refundsRejected',
            'stats',
            'statistics'
        ));
    }

    /**
     * Display refund verification page for staff_keuangan and direktur_keuangan.
     */
    public function verificationIndex(Request $request): View
    {
        // Get all data for each tab (without filters for now, to show counts correctly)
        $baseQuery = function ($status) {
            return Refund::with([
                'pencairanDana',
                'pencairanDana.pengajuanDana',
                'pencairanDana.pengajuanDana.divisi',
                'pengajuanDana',
                'pengajuanDana.divisi',
                'createdBy',
                'approvedBy'
            ])->where('status', $status);
        };

        // Menunggu Approval
        $menungguQuery = $baseQuery('menunggu_approval');
        if ($request->filled('divisi_id')) {
            $menungguQuery->where(function ($q) use ($request) {
                $q->whereHas('pencairanDana.pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                })
                ->orWhereHas('pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                });
            });
        }
        if ($request->filled('periode_anggaran_id')) {
            $menungguQuery->where('periode_anggaran_id', $request->periode_anggaran_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $menungguQuery->where(function ($q) use ($search) {
                $q->where('nomor_refund', 'like', "%{$search}%")
                  ->orWhere('alasan_refund', 'like', "%{$search}%");
            });
        }
        $refunds = $menungguQuery->orderBy('created_at', 'desc')->paginate(15);

        // Disetujui (processed)
        $processedQuery = $baseQuery('processed');
        if ($request->filled('divisi_id')) {
            $processedQuery->where(function ($q) use ($request) {
                $q->whereHas('pencairanDana.pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                })
                ->orWhereHas('pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                });
            });
        }
        if ($request->filled('periode_anggaran_id')) {
            $processedQuery->where('periode_anggaran_id', $request->periode_anggaran_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $processedQuery->where(function ($q) use ($search) {
                $q->where('nomor_refund', 'like', "%{$search}%")
                  ->orWhere('alasan_refund', 'like', "%{$search}%");
            });
        }
        $refundsProcessed = $processedQuery->orderBy('approved_at', 'desc')->paginate(15);

        // Ditolak (rejected)
        $rejectedQuery = $baseQuery('rejected');
        if ($request->filled('divisi_id')) {
            $rejectedQuery->where(function ($q) use ($request) {
                $q->whereHas('pencairanDana.pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                })
                ->orWhereHas('pengajuanDana', function ($sq) use ($request) {
                    $sq->where('divisi_id', $request->divisi_id);
                });
            });
        }
        if ($request->filled('periode_anggaran_id')) {
            $rejectedQuery->where('periode_anggaran_id', $request->periode_anggaran_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $rejectedQuery->where(function ($q) use ($search) {
                $q->where('nomor_refund', 'like', "%{$search}%")
                  ->orWhere('alasan_refund', 'like', "%{$search}%");
            });
        }
        $refundsRejected = $rejectedQuery->orderBy('approved_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'menunggu_approval' => Refund::where('status', 'menunggu_approval')->count(),
            'processed' => Refund::where('status', 'processed')->count(),
            'rejected' => Refund::where('status', 'rejected')->count(),
        ];

        return view('refund.verification-index', compact('refunds', 'refundsProcessed', 'refundsRejected', 'stats'));
    }

    /**
     * Show the form for creating a new refund.
     */
    public function create(Request $request): View
    {
        $pencairanId = $request->query('pencairan_id');
        $pengajuanId = $request->query('pengajuan_id');
        $lpjId = $request->query('lpj_id');
        $pencairan = null;
        $pengajuan = null;
        $lpj = null;

        if ($pencairanId) {
            $pencairan = PencairanDana::with(['pengajuanDana'])->findOrFail($pencairanId);
        }

        if ($pengajuanId) {
            $pengajuan = PengajuanDana::findOrFail($pengajuanId);
        }

        if ($lpjId) {
            $lpj = \App\Models\LaporanPertanggungJawaban::with([
                'pencairanDana',
                'pencairanDana.pengajuanDana',
                'detailLpjs'
            ])->findOrFail($lpjId);
        }

        // Get active company bank accounts for rekening tujuan
        $rekeningPerusahaan = \App\Models\RekeningPerusahaan::active()
            ->with('bank')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('refund.create', compact('pencairan', 'pengajuan', 'lpj', 'rekeningPerusahaan'));
    }

    /**
     * Show the form to select LPJ for Refund creation.
     */
    public function selectLpj(Request $request): View
    {
        $query = \App\Models\LaporanPertanggungJawaban::with([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'detailLpjs',
            'createdBy'
        ])->where('status', 'approved')
          ->where('sisa_dana', '>', 0);

        // Filter by divisi
        if ($request->filled('divisi_id')) {
            $query->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        // Filter by periode anggaran
        if ($request->filled('periode_anggaran_id')) {
            $query->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('periode_anggaran_id', $request->periode_anggaran_id);
            });
        }

        // Filter by search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_lpj', 'like', "%{$search}%")
                  ->orWhere('uraian_kegiatan', 'like', "%{$search}%");
            });
        }

        $lpjs = $query->orderBy('approved_at', 'desc')->paginate(15);

        return view('refund.select-lpj', compact('lpjs'));
    }

    /**
     * Store a newly created refund.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lpj_id' => 'nullable|exists:laporan_pertanggung_jawabans,id',
            'pencairan_dana_id' => 'nullable|exists:pencairan_danas,id',
            'pengajuan_dana_id' => 'nullable|exists:pengajuan_danas,id',
            'tanggal_refund' => 'required|date',
            'jumlah_refund' => 'required|numeric|min:0',
            'alasan_refund' => 'required|string|max:1000',
            'jenis_refund' => 'required|in:kelebihan,dana_kembali,batal,pengembalian lainnya',
            'rekening_perusahaan_id' => 'required|exists:rekening_perusahaans,id',
            'rekening_pengirim' => 'nullable|string|max:100',
            'nama_pengirim' => 'nullable|string|max:255',
            'bukti_transfer' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Get LPJ data if lpj_id is provided
        if (!empty($validated['lpj_id'])) {
            $lpj = \App\Models\LaporanPertanggungJawaban::find($validated['lpj_id']);
            if ($lpj) {
                $validated['pencairan_dana_id'] = $lpj->pencairan_dana_id;
                $validated['pengajuan_dana_id'] = $lpj->pencairanDana->pengajuan_dana_id ?? null;
                $validated['periode_anggaran_id'] = $lpj->pencairanDana->pengajuanDana->periode_anggaran_id ?? null;
            }
        }

        // At least one reference is required
        if (empty($validated['pencairan_dana_id']) && empty($validated['pengajuan_dana_id'])) {
            return back()
                ->withInput()
                ->with('error', 'Harap pilih LPJ, pencairan dana, atau pengajuan dana terkait.');
        }

        try {
            // Generate nomor refund
            $validated['nomor_refund'] = \App\Services\NumberingService::generateNomorRefund();

            $refund = RefundService::createRefund([
                ...$validated,
                'created_by' => auth()->id(),
            ], $request->file('bukti_transfer'));

            return redirect()
                ->route('refund.show', $refund)
                ->with('success', 'Refund berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat refund: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified refund.
     */
    public function show(Refund $refund): View
    {
        $refund->load([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'pengajuanDana',
            'pengajuanDana.divisi',
            'rekeningPerusahaan',
            'rekeningPerusahaan.bank',
            'createdBy',
            'approvedBy',
        ]);

        return view('refund.show', compact('refund'));
    }

    /**
     * Show the form for editing the specified refund.
     */
    public function edit(Refund $refund): View
    {
        // Only allow editing draft refunds
        if ($refund->status !== 'draft') {
            abort(403, 'Hanya refund dengan status draft yang dapat diedit.');
        }

        return view('refund.edit', compact('refund'));
    }

    /**
     * Update the specified refund.
     */
    public function update(Request $request, Refund $refund): RedirectResponse
    {
        // Only allow updating draft refunds
        if ($refund->status !== 'draft') {
            return back()->with('error', 'Hanya refund dengan status draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal_refund' => 'required|date',
            'jumlah_refund' => 'required|numeric|min:0',
            'alasan_refund' => 'required|string|max:1000',
            'jenis_refund' => 'required|in:kelebihan,dana_kembali,batal,pengembalian lainnya',
            'rekening_tujuan' => 'nullable|string|max:200',
            'bukti_transfer' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            $refund = RefundService::updateRefund($refund->id, $validated, $request->file('bukti_transfer'));

            return redirect()
                ->route('refund.show', $refund)
                ->with('success', 'Refund berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui refund: ' . $e->getMessage());
        }
    }

    /**
     * Submit refund for approval.
     */
    public function submit(Refund $refund): RedirectResponse
    {
        if ($refund->status !== 'draft') {
            return back()->with('error', 'Hanya refund dengan status draft yang dapat disubmit.');
        }

        try {
            RefundService::submitRefund($refund->id);

            return redirect()
                ->route('refund.show', $refund)
                ->with('success', 'Refund berhasil disubmit untuk approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mensubmit refund: ' . $e->getMessage());
        }
    }

    /**
     * Approve refund.
     */
    public function approve(Request $request, Refund $refund): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_approval' => 'required_if:status,rejected|string|max:1000',
        ]);

        try {
            RefundService::approveRefund(
                $refund->id,
                $validated['status'],
                $validated['catatan_approval'] ?? null,
                auth()->user()
            );

            $message = $validated['status'] === 'approved'
                ? 'Refund berhasil disetujui.'
                : 'Refund ditolak.';

            return redirect()
                ->route('refund.show', $refund)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui refund: ' . $e->getMessage());
        }
    }

    /**
     * Process refund (transfer).
     */
    public function process(Request $request, Refund $refund): RedirectResponse
    {
        if ($refund->status !== 'approved') {
            return back()->with('error', 'Hanya refund yang sudah disetujui yang dapat diproses.');
        }

        $validated = $request->validate([
            'bukti_transfer' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tanggal_transfer' => 'required|date',
        ]);

        try {
            RefundService::processRefund(
                $refund->id,
                $validated['tanggal_transfer'],
                $request->file('bukti_transfer')
            );

            return redirect()
                ->route('refund.show', $refund)
                ->with('success', 'Refund berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified refund.
     */
    public function destroy(Refund $refund): RedirectResponse
    {
        // Only allow deleting draft refunds
        if ($refund->status !== 'draft') {
            return back()->with('error', 'Hanya refund dengan status draft yang dapat dihapus.');
        }

        try {
            RefundService::deleteRefund($refund->id);

            return redirect()
                ->route('refund.index')
                ->with('success', 'Refund berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus refund: ' . $e->getMessage());
        }
    }

    /**
     * Get refund statistics.
     */
    public function statistics(Request $request)
    {
        $periodeId = $request->query('periode_anggaran_id');

        $stats = [
            'total' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->count(),
            'total_nominal' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'processed')->sum('jumlah_refund'),
            'draft' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'draft')->count(),
            'menunggu_approval' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'menunggu_approval')->count(),
            'approved' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'approved')->count(),
            'rejected' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'rejected')->count(),
            'processed' => Refund::when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_anggaran_id', $periodeId);
            })->where('status', 'processed')->count(),
        ];

        return response()->json($stats);
    }
}
