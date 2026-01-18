<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePencairanDanaRequest;
use App\Http\Requests\UpdatePencairanDanaRequest;
use App\Models\PencairanDana;
use App\Models\PengajuanDana;
use App\Models\DetailPencairan;
use App\Services\PencairanService;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PencairanDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.read')) {
            abort(403);
        }

        // Base query function
        $baseQuery = function ($status) use ($request) {
            $query = PencairanDana::with([
                'pengajuanDana.divisi',
                'pengajuanDana.programKerja',
                'pengajuanDana.createdBy',
                'createdBy',
                'processedBy',
            ])->where('status', $status);

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_pencairan', 'like', "%{$search}%")
                      ->orWhereHas('pengajuanDana', function ($subQ) use ($search) {
                          $subQ->where('nomor_pengajuan', 'like', "%{$search}%")
                            ->orWhere('judul_pengajuan', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('metode_pencairan')) {
                $query->where('metode_pencairan', $request->metode_pencairan);
            }

            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_pencairan', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_pencairan', '<=', $request->tanggal_selesai);
            }

            return $query;
        };

        // Menunggu Verifikasi
        $pencairansMenunggu = $baseQuery('menunggu')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Menunggu Proses
        $pencairansPending = $baseQuery('pending')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Diproses
        $pencairansProcessed = $baseQuery('processed')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Selesai
        $pencairansSelesai = $baseQuery('selesai')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Revisi
        $pencairansRevisi = $baseQuery('revisi')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Dibatalkan
        $pencairansCancelled = $baseQuery('cancelled')
            ->orderBy('tanggal_pencairan', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Get statistics
        $stats = [
            'menunggu' => PencairanDana::where('status', 'menunggu')->count(),
            'pending' => PencairanDana::where('status', 'pending')->count(),
            'processed' => PencairanDana::where('status', 'processed')->count(),
            'selesai' => PencairanDana::where('status', 'selesai')->count(),
            'revisi' => PencairanDana::where('status', 'revisi')->count(),
            'cancelled' => PencairanDana::where('status', 'cancelled')->count(),
        ];

        // Calculate overall statistics
        $statistics = [
            'total_count' => array_sum($stats),
            'total_amount' => PencairanDana::whereIn('status', ['processed', 'selesai'])->sum('jumlah_pencairan'),
            'pending_count' => $stats['menunggu'] + $stats['pending'],
            'completed_count' => $stats['processed'] + $stats['selesai'],
        ];

        return view('pencairan-dana.index', [
            'pencairansMenunggu' => $pencairansMenunggu,
            'pencairansPending' => $pencairansPending,
            'pencairansProcessed' => $pencairansProcessed,
            'pencairansSelesai' => $pencairansSelesai,
            'pencairansRevisi' => $pencairansRevisi,
            'pencairansCancelled' => $pencairansCancelled,
            'stats' => $stats,
            'statistics' => $statistics,
            'permissions' => [
                'create' => $user->hasPermission('pencairan_dana.create'),
                'edit' => $user->hasPermission('pencairan_dana.update'),
                'delete' => $user->hasPermission('pencairan_dana.delete'),
                'process' => $user->hasPermission('pencairan_dana.approve'),
            ],
        ]);
    }

    /**
     * Select pengajuan dana for pencairan.
     */
    public function selectPengajuan()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.create')) {
            abort(403);
        }

        // Get approved pengajuans that haven't been disbursed yet
        $pengajuans = PengajuanDana::with([
                'divisi',
                'programKerja',
                'createdBy',
                'approvals' => function ($query) {
                    $query->with('approver')->orderBy('urutan');
                },
            ])
            ->where('status', 'menunggu_pencairan')
            ->whereDoesntHave('pencairanDana')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pencairan-dana.select-pengajuan', [
            'pengajuans' => $pengajuans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.create')) {
            abort(403);
        }

        $pengajuanId = $request->pengajuan_id;

        if (!$pengajuanId) {
            return redirect()->route('pencairan-dana.select-pengajuan')
                ->with('error', 'Silakan pilih pengajuan dana terlebih dahulu');
        }

        $pengajuan = PengajuanDana::with([
            'divisi',
            'programKerja',
            'subProgram',
            'details.subProgram',
            'honorariumDetails.karyawan',
            'attachments',
            'approvals' => function ($query) {
                $query->with('approver')->orderBy('urutan');
            },
        ])->findOrFail($pengajuanId);

        // Load vendor data if penerima_manfaat_type is vendor and ID is set
        $vendor = null;
        if ($pengajuan->penerima_manfaat_type === 'vendor' && $pengajuan->penerima_manfaat_id) {
            $vendor = \App\Models\Vendor::with('bank')->find($pengajuan->penerima_manfaat_id);
        }

        // Check if can create pencairan
        if (!PencairanService::canCreatePencairan($pengajuan)) {
            return redirect()
                ->route('pencairan-dana.select-pengajuan')
                ->with('error', 'Pencairan tidak dapat dibuat untuk pengajuan ini');
        }

        return view('pencairan-dana.create', [
            'pengajuan' => $pengajuan,
            'vendor' => $vendor,
            'user' => $user,
            'rekeningPerusahaan' => \App\Models\RekeningPerusahaan::with('bank')
                ->active()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get(),
            'banks' => \App\Models\Bank::active()->orderBy('nama_bank')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePencairanDanaRequest $request)
    {
        DB::beginTransaction();
        try {
            $pengajuan = PengajuanDana::findOrFail($request->pengajuan_dana_id);

            // Create pencairan
            $pencairan = PencairanService::createPencairan($pengajuan, $request->validated());

            // Handle lampiran uploads (non-honorarium)
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $path = $file->store('lampiran-pencairan', 'public');
                    \App\Models\PencairanLampiran::create([
                        'pencairan_dana_id' => $pencairan->id,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'tipe_file' => $file->getClientMimeType(),
                        'ukuran_file' => $file->getSize(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // Handle lampiran per honorarium recipient
            if ($request->hasFile('lampiran_honorarium') && $pengajuan->jenis_pengajuan === 'honorarium') {
                foreach ($request->file('lampiran_honorarium') as $honorariumId => $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('lampiran-honorarium', 'public');
                        \App\Models\HonorariumLampiran::create([
                            'pencairan_dana_id' => $pencairan->id,
                            'honorarium_detail_id' => $honorariumId,
                            'nama_file' => $file->getClientOriginalName(),
                            'path_file' => $path,
                            'tipe_file' => $file->getClientMimeType(),
                            'ukuran_file' => $file->getSize(),
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('pencairan-dana.show', $pencairan->id)
                ->with('success', 'Pencairan dana berhasil dibuat dengan nomor: ' . $pencairan->nomor_pencairan);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create pencairan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat pencairan dana. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        // Staff keuangan can access all pencairan
        // Pengaju can only access pencairan related to their own pengajuan
        $isStaffKeuangan = $user->hasPermission('pencairan_dana.read');
        $isOwnerPengaju = $pencairanDana->pengajuanDana && $pencairanDana->pengajuanDana->created_by === $user->id;

        // Debug log
        \Log::info('Pencairan Show Debug', [
            'pencairan_id' => $pencairanDana->id,
            'pencairan_status' => $pencairanDana->status,
            'user_id' => $user->id,
            'isStaffKeuangan' => $isStaffKeuangan,
            'isOwnerPengaju' => $isOwnerPengaju,
            'pengajuan_created_by' => $pencairanDana->pengajuanDana->created_by ?? null,
            'can_verify' => $isOwnerPengaju && in_array($pencairanDana->status, ['menunggu', 'pending']),
        ]);

        if (!$isStaffKeuangan && !$isOwnerPengaju) {
            abort(403);
        }

        $pencairanDana->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.subProgram',
            'pengajuanDana.details.subProgram',
            'pengajuanDana.attachments',
            'pengajuanDana.createdBy',
            'pengajuanDana.honorariumDetails',
            'rekeningPerusahaan.bank',
            'createdBy',
            'processedBy',
            'lampirans',
            'detailPencairans.honorariumDetail',
            'honorariumLampirans',
        ]);

        return view('pencairan-dana.show', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pencairanDana->pengajuanDana,
            'permissions' => [
                'edit' => $isStaffKeuangan && $user->hasPermission('pencairan_dana.update') && in_array($pencairanDana->status, ['menunggu', 'pending']),
                'delete' => $isStaffKeuangan && $user->hasPermission('pencairan_dana.delete') && in_array($pencairanDana->status, ['menunggu', 'pending']),
                'verify' => $isOwnerPengaju && in_array($pencairanDana->status, ['menunggu', 'pending']),
                'retry' => $isStaffKeuangan && $user->hasPermission('pencairan_dana.create') && $pencairanDana->status === 'revisi',
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.update') || $pencairanDana->status !== 'pending') {
            abort(403);
        }

        $pencairanDana->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.subProgram',
            'lampirans',
        ]);

        return view('pencairan-dana.edit', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pencairanDana->pengajuanDana,
            'rekeningPerusahaan' => \App\Models\RekeningPerusahaan::with('bank')
                ->active()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get(),
            'banks' => \App\Models\Bank::active()->orderBy('nama_bank')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePencairanDanaRequest $request, PencairanDana $pencairanDana)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Get bank details if bank_id is provided
            if (isset($data['bank_id'])) {
                $bank = \App\Models\Bank::find($data['bank_id']);
                $data['nama_bank'] = $bank ? $bank->nama_bank : null;
            }

            // Get rekening perusahaan details if provided
            if (isset($data['rekening_perusahaan_id'])) {
                $rekeningPerusahaan = \App\Models\RekeningPerusahaan::with('bank')->find($data['rekening_perusahaan_id']);
                if ($rekeningPerusahaan) {
                    $data['nama_bank_sumber'] = $rekeningPerusahaan->bank->nama_bank;
                    $data['nomor_rekening_sumber'] = $rekeningPerusahaan->nomor_rekening;
                }
            }

            // Update pencairan
            $pencairanDana->update($data);

            // Handle lampiran removal
            if ($request->has('remove_lampiran')) {
                foreach ($request->remove_lampiran as $lampiranId) {
                    $lampiran = \App\Models\PencairanLampiran::find($lampiranId);
                    if ($lampiran && $lampiran->pencairan_dana_id === $pencairanDana->id) {
                        // Delete file from storage
                        if (Storage::disk('public')->exists($lampiran->path_file)) {
                            Storage::disk('public')->delete($lampiran->path_file);
                        }
                        $lampiran->delete();
                    }
                }
            }

            // Handle new lampiran uploads
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $path = $file->store('lampiran-pencairan', 'public');
                    \App\Models\PencairanLampiran::create([
                        'pencairan_dana_id' => $pencairanDana->id,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'tipe_file' => $file->getClientMimeType(),
                        'ukuran_file' => $file->getSize(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('success', 'Pencairan dana berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update pencairan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pencairan dana. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.delete') || $pencairanDana->status !== 'pending') {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Cancel pencairan
            PencairanService::cancelPencairan($pencairanDana, 'Dibatalkan oleh ' . $user->full_name);

            DB::commit();

            return redirect()
                ->route('pencairan-dana.index')
                ->with('success', 'Pencairan dana berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete pencairan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal membatalkan pencairan dana. Silakan coba lagi.');
        }
    }

    /**
     * Process pencairan (mark as processed)
     */
    public function process(Request $request, PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.approve')) {
            abort(403);
        }

        $request->validate([
            'bukti_pencairan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['catatan']);

            // Handle bukti pencairan upload
            if ($request->hasFile('bukti_pencairan')) {
                $file = $request->file('bukti_pencairan');
                $path = $file->store('bukti-pencairan', 'public');
                $data['bukti_pencairan'] = $path;
            }

            // Process pencairan
            PencairanService::processPencairan($pencairanDana, $data);

            DB::commit();

            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('success', 'Pencairan berhasil diproses');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to process pencairan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memproses pencairan. ' . $e->getMessage());
        }
    }

    /**
     * Verify pencairan (for pembayaran type)
     */
    public function verify(Request $request, PencairanDana $pencairanDana)
    {
        $user = Auth::user();
        $pengajuan = $pencairanDana->pengajuanDana;

        // Only pengaju (creator) can verify
        if ($pengajuan->created_by !== $user->id) {
            abort(403);
        }

        // Check if pencairan is in 'menunggu' or 'pending' status
        if (!in_array($pencairanDana->status, ['menunggu', 'pending'])) {
            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('error', 'Pencairan tidak dapat diverifikasi');
        }

        $request->validate([
            'confirmed' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            PencairanService::verifyPencairan(
                $pencairanDana,
                $request->confirmed,
                $request->notes
            );

            DB::commit();

            $message = $request->confirmed ? 'Pembayaran berhasil dikonfirmasi' : 'Pembayaran ditolak';

            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to verify pencairan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memverifikasi pembayaran. ' . $e->getMessage());
        }
    }

    /**
     * Show retry form for pencairan (create new from rejected one)
     */
    public function retry(PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        // Only staff keuangan with create permission can retry
        if (!$user->hasPermission('pencairan_dana.create')) {
            abort(403);
        }

        // Only allow retry for 'revisi' status
        if ($pencairanDana->status !== 'revisi') {
            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('error', 'Hanya pencairan dengan status revisi yang bisa dibuat ulang');
        }

        $pencairanDana->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.subProgram',
            'pengajuanDana.honorariumDetails',
            'pengajuanDana.attachments',
            'pengajuanDana.approvals' => function ($query) {
                $query->with('approver')->orderBy('urutan');
            },
            'detailPencairans.honorariumDetail',
        ]);

        // Load vendor data if applicable
        $vendor = null;
        $pengajuan = $pencairanDana->pengajuanDana;
        if ($pengajuan->penerima_manfaat_type === 'vendor' && $pengajuan->penerima_manfaat_id) {
            $vendor = \App\Models\Vendor::with('bank')->find($pengajuan->penerima_manfaat_id);
        }

        return view('pencairan-dana.retry', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pengajuan,
            'vendor' => $vendor,
            'user' => $user,
            'rekeningPerusahaan' => \App\Models\RekeningPerusahaan::with('bank')
                ->active()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get(),
            'banks' => \App\Models\Bank::active()->orderBy('nama_bank')->get(),
        ]);
    }

    /**
     * Store retry pencairan (create new from rejected one)
     */
    public function storeRetry(StorePencairanDanaRequest $request, PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        // Only staff keuangan with create permission can retry
        if (!$user->hasPermission('pencairan_dana.create')) {
            abort(403);
        }

        // Only allow retry for 'revisi' status
        if ($pencairanDana->status !== 'revisi') {
            return redirect()
                ->route('pencairan-dana.show', $pencairanDana->id)
                ->with('error', 'Hanya pencairan dengan status revisi yang bisa dibuat ulang');
        }

        DB::beginTransaction();
        try {
            $pengajuan = $pencairanDana->pengajuanDana;

            // Create pencairan with validated data
            $newPencairan = PencairanService::retryPencairan($pencairanDana, $request->validated());

            // Handle lampiran uploads (non-honorarium)
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $path = $file->store('lampiran-pencairan', 'public');
                    \App\Models\PencairanLampiran::create([
                        'pencairan_dana_id' => $newPencairan->id,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'tipe_file' => $file->getClientMimeType(),
                        'ukuran_file' => $file->getSize(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // Handle lampiran per honorarium recipient
            if ($request->hasFile('lampiran_honorarium') && $pengajuan->jenis_pengajuan === 'honorarium') {
                foreach ($request->file('lampiran_honorarium') as $honorariumId => $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('lampiran-honorarium', 'public');
                        \App\Models\HonorariumLampiran::create([
                            'pencairan_dana_id' => $newPencairan->id,
                            'honorarium_detail_id' => $honorariumId,
                            'nama_file' => $file->getClientOriginalName(),
                            'path_file' => $path,
                            'tipe_file' => $file->getClientMimeType(),
                            'ukuran_file' => $file->getSize(),
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('pencairan-dana.show', $newPencairan->id)
                ->with('success', 'Pencairan baru berhasil dibuat dengan nomor: ' . $newPencairan->nomor_pencairan);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to retry pencairan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat pencairan ulang. ' . $e->getMessage());
        }
    }

    /**
     * Get pending pencairan count for notification
     */
    public function pendingCount()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.read')) {
            return response()->json(['count' => 0]);
        }

        $count = PencairanDana::where('status', 'pending')
            ->whereHas('pengajuanDana', function ($query) use ($user) {
                if (!$user->hasPermission('pencairan_dana.read_all')) {
                    $query->where('divisi_id', $user->divisi_id);
                }
            })
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Export pencairan data
     */
    public function export(Request $request)
    {
        // Implementation for export functionality
        // This can be implemented later with Excel export
        return response()->json(['message' => 'Export feature coming soon']);
    }

    /**
     * Print pencairan document
     */
    public function print(PencairanDana $pencairanDana)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.read')) {
            abort(403);
        }

        $pencairanDana->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
            'createdBy',
            'processedBy',
        ]);

        return view('pencairan-dana.print', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pencairanDana->pengajuanDana,
        ]);
    }

    /**
     * Get pencairan statistics
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pencairan_dana.read')) {
            abort(403);
        }

        $startDate = $request->tanggal_mulai;
        $endDate = $request->tanggal_selesai;
        $divisiId = $user->hasPermission('pencairan_dana.read_all') ? $request->divisi_id : $user->divisi_id;

        $stats = PencairanService::getPencairanStatistics($startDate, $endDate, $divisiId);

        return response()->json($stats);
    }
}