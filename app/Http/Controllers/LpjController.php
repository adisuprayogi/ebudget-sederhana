<?php

namespace App\Http\Controllers;

use App\Models\LaporanPertanggungJawaban;
use App\Models\PencairanDana;
use App\Services\LpjService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class LpjController extends Controller
{
    /**
     * Display a listing of LPJ.
     */
    public function index(Request $request): View
    {
        // Base query function
        $baseQuery = function ($status) use ($request) {
            $query = LaporanPertanggungJawaban::with([
                'pencairanDana',
                'pencairanDana.pengajuanDana',
                'pencairanDana.pengajuanDana.divisi',
                'pencairanDana.pengajuanDana.programKerja',
                'createdBy'
            ])->where('status', $status);

            // Filter by periode anggaran
            if ($request->filled('periode_anggaran_id')) {
                $query->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                    $q->where('periode_anggaran_id', $request->periode_anggaran_id);
                });
            }

            // Filter by divisi
            if ($request->filled('divisi_id')) {
                $query->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                    $q->where('divisi_id', $request->divisi_id);
                });
            }

            // Filter by search keyword
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_lpj', 'like', "%{$search}%")
                      ->orWhere('judul_lpj', 'like', "%{$search}%");
                });
            }

            return $query;
        };

        // Fetch data for each status
        $lpjsDraft = $baseQuery('draft')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $lpjsMenungguVerifikasi = $baseQuery('menunggu_verifikasi')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $lpjsApproved = $baseQuery('approved')
            ->orderBy('approved_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $lpjsRejected = $baseQuery('rejected')
            ->orderBy('approved_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $lpjsRevisi = $baseQuery('revisi')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Get statistics
        $stats = [
            'draft' => LaporanPertanggungJawaban::where('status', 'draft')->count(),
            'menunggu_verifikasi' => LaporanPertanggungJawaban::where('status', 'menunggu_verifikasi')->count(),
            'approved' => LaporanPertanggungJawaban::where('status', 'approved')->count(),
            'rejected' => LaporanPertanggungJawaban::where('status', 'rejected')->count(),
            'revisi' => LaporanPertanggungJawaban::where('status', 'revisi')->count(),
        ];

        $statistics = [
            'total_count' => array_sum($stats),
            'pending_count' => $stats['menunggu_verifikasi'],
            'approved_count' => $stats['approved'],
            'total_amount' => LaporanPertanggungJawaban::where('status', 'approved')->sum('total_digunakan'),
        ];

        // Get periode anggarans for filter
        $periodeAnggarans = \App\Models\PeriodeAnggaran::orderBy('nama_periode')->get();

        return view('lpj.index', compact(
            'lpjsDraft',
            'lpjsMenungguVerifikasi',
            'lpjsApproved',
            'lpjsRejected',
            'lpjsRevisi',
            'stats',
            'statistics',
            'periodeAnggarans'
        ));
    }

    /**
     * Display LPJ verification page for staff_keuangan and direktur_keuangan.
     */
    public function verificationIndex(Request $request): View
    {
        // Get LPJ menunggu verifikasi
        $query = LaporanPertanggungJawaban::with([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'createdBy',
            'detailLpjs'
        ])->where('status', 'menunggu_verifikasi');

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

        $lpjs = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get pencairan that is selesai but doesn't have LPJ yet (belum buat LPJ)
        // Exclude Honorarium and Pembayaran types (they don't have LPJ)
        $pencairanQuery = \App\Models\PencairanDana::with([
            'pengajuanDana',
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.subProgram',
            'detailPencairans'
        ])->where('status', 'selesai')
          ->whereDoesntHave('laporanPertanggungJawaban')
          ->whereHas('pengajuanDana', function ($q) {
              // Exclude Honorarium and Pembayaran - they don't have LPJ
              $q->whereNotIn('jenis_pengajuan', ['honorarium', 'pembayaran']);
          });

        // Filter by divisi for pencairan
        if ($request->filled('divisi_id')) {
            $pencairanQuery->whereHas('pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        // Filter by periode anggaran for pencairan
        if ($request->filled('periode_anggaran_id')) {
            $pencairanQuery->whereHas('pengajuanDana', function ($q) use ($request) {
                $q->where('periode_anggaran_id', $request->periode_anggaran_id);
            });
        }

        // Filter by search keyword for pencairan
        if ($request->filled('search')) {
            $search = $request->search;
            $pencairanQuery->where(function ($q) use ($search) {
                $q->where('nomor_pencairan', 'like', "%{$search}%")
                  ->orWhereHas('pengajuanDana', function ($q) use ($search) {
                      $q->where('judul_pengajuan', 'like', "%{$search}%");
                  });
            });
        }

        $pencairanBelumLpj = $pencairanQuery->orderBy('tanggal_pencairan', 'desc')->paginate(15);

        // Get LPJ menunggu revisi (status revisi)
        $lpjRevisiQuery = LaporanPertanggungJawaban::with([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'createdBy',
            'detailLpjs'
        ])->where('status', 'revisi');

        // Filter by divisi for LPJ revisi
        if ($request->filled('divisi_id')) {
            $lpjRevisiQuery->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        // Filter by periode anggaran for LPJ revisi
        if ($request->filled('periode_anggaran_id')) {
            $lpjRevisiQuery->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('periode_anggaran_id', $request->periode_anggaran_id);
            });
        }

        // Filter by search keyword for LPJ revisi
        if ($request->filled('search')) {
            $search = $request->search;
            $lpjRevisiQuery->where(function ($q) use ($search) {
                $q->where('nomor_lpj', 'like', "%{$search}%")
                  ->orWhere('uraian_kegiatan', 'like', "%{$search}%");
            });
        }

        $lpjRevisi = $lpjRevisiQuery->orderBy('updated_at', 'desc')->paginate(15);

        // Get LPJ selesai/closed (status approved)
        $lpjSelesaiQuery = LaporanPertanggungJawaban::with([
            'pencairanDana',
            'pencairanDana.pengajuanDana',
            'pencairanDana.pengajuanDana.divisi',
            'createdBy',
            'detailLpjs',
            'approvedBy'
        ])->where('status', 'approved');

        // Filter by divisi for LPJ selesai
        if ($request->filled('divisi_id')) {
            $lpjSelesaiQuery->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        // Filter by periode anggaran for LPJ selesai
        if ($request->filled('periode_anggaran_id')) {
            $lpjSelesaiQuery->whereHas('pencairanDana.pengajuanDana', function ($q) use ($request) {
                $q->where('periode_anggaran_id', $request->periode_anggaran_id);
            });
        }

        // Filter by search keyword for LPJ selesai
        if ($request->filled('search')) {
            $search = $request->search;
            $lpjSelesaiQuery->where(function ($q) use ($search) {
                $q->where('nomor_lpj', 'like', "%{$search}%")
                  ->orWhere('uraian_kegiatan', 'like', "%{$search}%");
            });
        }

        $lpjSelesai = $lpjSelesaiQuery->orderBy('approved_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'menunggu_verifikasi' => LaporanPertanggungJawaban::where('status', 'menunggu_verifikasi')->count(),
            'menunggu_revisi' => LaporanPertanggungJawaban::where('status', 'revisi')->count(),
            'belum_buat_lpj' => \App\Models\PencairanDana::where('status', 'selesai')
                ->whereDoesntHave('laporanPertanggungJawaban')
                ->whereHas('pengajuanDana', function ($q) {
                    // Exclude Honorarium and Pembayaran - they don't have LPJ
                    $q->whereNotIn('jenis_pengajuan', ['honorarium', 'pembayaran']);
                })->count(),
            'lpj_selesai' => LaporanPertanggungJawaban::where('status', 'approved')->count(),
            'approved_today' => LaporanPertanggungJawaban::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'approved_this_month' => LaporanPertanggungJawaban::where('status', 'approved')
                ->whereYear('approved_at', now()->year)
                ->whereMonth('approved_at', now()->month)->count(),
        ];

        return view('lpj.verification-index', compact('lpjs', 'lpjRevisi', 'lpjSelesai', 'pencairanBelumLpj', 'stats'));
    }

    /**
     * Show the form to select pengajuan for LPJ creation.
     */
    public function selectPengajuan(Request $request): View
    {
        $query = \App\Models\PengajuanDana::with([
            'divisi',
            'programKerja.periodeAnggaran',
            'subProgram',
            'activePencairan'
        ])
        ->where('status', 'menunggu_lpj')
        // Exclude pengajuans that already have LPJ with menunggu_verifikasi or approved status
        ->whereDoesntHave('laporanPertanggungJawabans', function ($q) {
            $q->whereIn('status', ['menunggu_verifikasi', 'approved']);
        });

        // Filter by divisi
        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        // Filter by periode anggaran
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        // Filter by search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('lpj.select-pengajuan', compact('pengajuans'));
    }

    /**
     * Show the form for creating a new LPJ.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $pengajuanId = $request->query('pengajuan_dana_id');
        $pengajuan = null;
        $pencairan = null;

        if ($pengajuanId) {
            $pengajuan = \App\Models\PengajuanDana::with([
                'divisi',
                'programKerja.periodeAnggaran',
                'subProgram',
                'activePencairan',
                'activePencairan.detailPencairans'
            ])->findOrFail($pengajuanId);

            // Get the active pencairan (non-cancelled, non-revisi)
            $pencairan = $pengajuan->activePencairan;

            if (!$pencairan) {
                return redirect()
                    ->route('lpj.select-pengajuan')
                    ->with('error', 'Pencairan aktif untuk pengajuan ini tidak ditemukan.');
            }

            // Check if LPJ already exists for this pencairan
            $existingLpj = LaporanPertanggungJawaban::where('pencairan_dana_id', $pencairan->id)
                ->first();

            if ($existingLpj) {
                return redirect()
                    ->route('lpj.show', $existingLpj)
                    ->with('info', 'LPJ untuk pencairan ini sudah ada.');
            }
        }

        return view('lpj.create', compact('pengajuan', 'pencairan'));
    }

    /**
     * Store a newly created LPJ.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pengajuan_dana_id' => 'required|exists:pengajuan_danas,id',
            'pencairan_dana_id' => 'required|exists:pencairan_danas,id',
            'uraian_kegiatan' => 'nullable|string',
            'catatan' => 'nullable|string|max:1000',
            'total_digunakan' => 'nullable|numeric|min:0',
            'sisa_dana' => 'nullable|numeric|min:0',
            'details' => 'required|array',
            'details.*.detail_pencairan_id' => 'required|exists:detail_pencairans,id',
            'details.*.uraian' => 'required|string',
            'details.*.tanggal_realisasi' => 'required|date',
            'details.*.volume_realisasi' => 'required|numeric|min:0',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.subtotal_realisasi' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        try {
            $pengajuan = \App\Models\PengajuanDana::findOrFail($validated['pengajuan_dana_id']);
            $pencairan = \App\Models\PencairanDana::findOrFail($validated['pencairan_dana_id']);

            // Generate nomor LPJ first (required field)
            $nomorLpj = \App\Services\NumberingService::generateNomorLPJ();

            // Prepare LPJ data
            $lpjData = [
                'nomor_lpj' => $nomorLpj,
                'pengajuan_dana_id' => $pengajuan->id,
                'pencairan_dana_id' => $pencairan->id,
                'tanggal_lpj' => now(), // Otomatis current datetime
                'uraian_kegiatan' => $validated['uraian_kegiatan'] ?? $pengajuan->judul_pengajuan,
                'total_digunakan' => $validated['total_digunakan'] ?? 0,
                'sisa_dana' => $validated['sisa_dana'] ?? ($pencairan->jumlah_pencairan - ($validated['total_digunakan'] ?? 0)),
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'menunggu_verifikasi', // Status setelah simpan
                'submitted_at' => now(),
                'created_by' => auth()->id(),
            ];

            // Create LPJ
            $lpj = \App\Models\LaporanPertanggungJawaban::create($lpjData);

            // Create detail LPJ from form data
            foreach ($validated['details'] as $index => $detailData) {
                $detailPencairan = \App\Models\DetailPencairan::find($detailData['detail_pencairan_id']);

                // Handle file upload
                $filePath = null;
                if ($request->hasFile("lampiran_{$index}")) {
                    $file = $request->file("lampiran_{$index}");
                    $filePath = $file->store('lpj-lampiran', 'public');
                }

                \App\Models\DetailLpj::create([
                    'laporan_pertanggung_jawaban_id' => $lpj->id,
                    'detail_pencairan_id' => $detailData['detail_pencairan_id'],
                    'uraian' => $detailData['uraian'],
                    'tanggal_realisasi' => $detailData['tanggal_realisasi'],
                    'volume_realisasi' => $detailData['volume_realisasi'],
                    'satuan' => $detailPencairan->satuan ?? null,
                    'harga_satuan' => $detailData['harga_satuan'],
                    'subtotal_realisasi' => $detailData['subtotal_realisasi'],
                    'keterangan' => $detailData['keterangan'] ?? null,
                    'file_lampiran' => $filePath,
                ]);
            }

            // LPJ is separate from pencairan workflow - no status change to pencairan

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
            'uraian_kegiatan' => 'nullable|string',
            'catatan' => 'nullable|string|max:1000',
            'total_digunakan' => 'nullable|numeric|min:0',
            'sisa_dana' => 'nullable|numeric|min:0',
            'details' => 'required|array',
            'details.*.detail_lpj_id' => 'nullable|exists:detail_lpjs,id',
            'details.*.detail_pencairan_id' => 'required|exists:detail_pencairans,id',
            'details.*.uraian' => 'required|string',
            'details.*.tanggal_realisasi' => 'required|date',
            'details.*.volume_realisasi' => 'required|numeric|min:0',
            'details.*.harga_satuan' => 'required|numeric|min:0',
            'details.*.subtotal_realisasi' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update LPJ main data
            $lpj->update([
                'uraian_kegiatan' => $validated['uraian_kegiatan'] ?? $lpj->uraian_kegiatan,
                'catatan' => $validated['catatan'] ?? $lpj->catatan,
                'total_digunakan' => $validated['total_digunakan'] ?? $lpj->total_digunakan,
                'sisa_dana' => $validated['sisa_dana'] ?? $lpj->sisa_dana,
                // If revisi, resubmit to menunggu_verifikasi
                'status' => $lpj->status === 'revisi' ? 'menunggu_verifikasi' : $lpj->status,
                'submitted_at' => $lpj->status === 'revisi' ? now() : $lpj->submitted_at,
                // Clear rejection data when resubmitting
                'rejected_at' => $lpj->status === 'revisi' ? null : $lpj->rejected_at,
                'rejected_by' => $lpj->status === 'revisi' ? null : $lpj->rejected_by,
                'rejection_reason' => $lpj->status === 'revisi' ? null : $lpj->rejection_reason,
            ]);

            // Update detail LPJ
            foreach ($validated['details'] as $index => $detailData) {
                $detailLpj = \App\Models\DetailLpj::find($detailData['detail_lpj_id'] ?? null);

                // Handle file upload
                $filePath = $detailLpj->file_lampiran ?? null;
                if ($request->hasFile("lampiran_{$index}")) {
                    $file = $request->file("lampiran_{$index}");
                    $filePath = $file->store('lpj-lampiran', 'public');
                }

                if ($detailLpj) {
                    // Update existing detail
                    $detailLpj->update([
                        'uraian' => $detailData['uraian'],
                        'tanggal_realisasi' => $detailData['tanggal_realisasi'],
                        'volume_realisasi' => $detailData['volume_realisasi'],
                        'harga_satuan' => $detailData['harga_satuan'],
                        'subtotal_realisasi' => $detailData['subtotal_realisasi'],
                        'keterangan' => $detailData['keterangan'] ?? null,
                        'file_lampiran' => $filePath,
                    ]);
                }
            }

            DB::commit();

            $message = $lpj->status === 'menunggu_verifikasi'
                ? 'LPJ berhasil direvisi dan dikirim ulang untuk verifikasi.'
                : 'LPJ berhasil diperbarui.';

            return redirect()
                ->route('lpj.show', $lpj)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
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
