<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanDanaRequest;
use App\Http\Requests\UpdatePengajuanDanaRequest;
use App\Models\PengajuanDana;
use App\Models\DetailPengajuan;
use App\Models\ProgramKerja;
use App\Models\Divisi;
use App\Models\Approval;
use App\Models\ApprovalConfig;
use App\Services\ApprovalService;
use App\Services\PenerimaManfaatService;
use App\Services\NumberingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query function with permission filters
        $baseQuery = function ($status) use ($request, $user) {
            $query = PengajuanDana::with(['divisi', 'programKerja', 'createdBy', 'approvals.approver']);

            // Filter based on user role and permissions
            if (!$user->hasPermission('pengajuan_dana.view_all')) {
                if ($user->hasPermission('pengajuan_dana.view_divisi')) {
                    // Get accessible divisions through job positions
                    $accessibleDivisionIds = $user->divisionIds();
                    if (!empty($accessibleDivisionIds)) {
                        $query->whereIn('divisi_id', $accessibleDivisionIds);
                    } else {
                        // If no job positions assigned, only show own
                        $query->where('created_by', $user->id);
                    }
                } else {
                    $query->where('created_by', $user->id);
                }
            }

            // Filter by status or status group
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_pengajuan', 'like', "%{$search}%")
                      ->orWhere('judul_pengajuan', 'like', "%{$search}%");
                });
            }

            if ($request->filled('jenis_pengajuan')) {
                $query->where('jenis_pengajuan', $request->jenis_pengajuan);
            }

            if ($request->filled('divisi_id')) {
                $query->where('divisi_id', $request->divisi_id);
            }

            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('created_at', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('created_at', '<=', $request->tanggal_selesai);
            }

            return $query;
        };

        // Fetch data for each status group
        $pengajuansDraft = $baseQuery('draft')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansMenungguApproval = $baseQuery('menunggu_approval')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansMenungguPencairan = $baseQuery('menunggu_pencairan')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansDicairkan = $baseQuery('cair')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansProses = $baseQuery(['menunggu_lpj', 'lpj_submitted'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansSelesai = $baseQuery(['lpj_disetujui', 'selesai', 'disetujui', 'approved'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansDitolak = $baseQuery(['ditolak', 'rejected', 'lpj_ditolak', 'refund_ditolak'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        $pengajuansCancelled = $baseQuery('cancelled')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Calculate statistics based on user's access
        $statsQuery = PengajuanDana::query();

        // Apply same permission filter to stats
        if (!$user->hasPermission('pengajuan_dana.view_all')) {
            if ($user->hasPermission('pengajuan_dana.view_divisi')) {
                $accessibleDivisionIds = $user->divisionIds();
                if (!empty($accessibleDivisionIds)) {
                    $statsQuery->whereIn('divisi_id', $accessibleDivisionIds);
                } else {
                    $statsQuery->where('created_by', $user->id);
                }
            } else {
                $statsQuery->where('created_by', $user->id);
            }
        }

        $stats = [
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
            'menunggu_approval' => (clone $statsQuery)->where('status', 'menunggu_approval')->count(),
            'menunggu_pencairan' => (clone $statsQuery)->where('status', 'menunggu_pencairan')->count(),
            'dicairkan' => (clone $statsQuery)->where('status', 'cair')->count(),
            'proses' => (clone $statsQuery)->whereIn('status', ['menunggu_lpj', 'lpj_submitted'])->count(),
            'selesai' => (clone $statsQuery)->whereIn('status', ['lpj_disetujui', 'selesai', 'disetujui', 'approved'])->count(),
            'ditolak' => (clone $statsQuery)->whereIn('status', ['ditolak', 'rejected', 'lpj_ditolak', 'refund_ditolak'])->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
        ];

        $statistics = [
            'total' => array_sum($stats),
            'menunggu_approval' => $stats['menunggu_approval'],
            'disetujui' => $stats['selesai'],
            'total_nilai' => $statsQuery->sum('total_pengajuan'),
        ];

        // Get filter options
        $jenisPengajuans = PengajuanDana::select('jenis_pengajuan')->distinct()->pluck('jenis_pengajuan');
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('pengajuan-dana.index', [
            'pengajuansDraft' => $pengajuansDraft,
            'pengajuansMenungguApproval' => $pengajuansMenungguApproval,
            'pengajuansMenungguPencairan' => $pengajuansMenungguPencairan,
            'pengajuansDicairkan' => $pengajuansDicairkan,
            'pengajuansProses' => $pengajuansProses,
            'pengajuansSelesai' => $pengajuansSelesai,
            'pengajuansDitolak' => $pengajuansDitolak,
            'pengajuansCancelled' => $pengajuansCancelled,
            'stats' => $stats,
            'statistics' => $statistics,
            'filters' => $request->only(['search', 'jenis_pengajuan', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'jenisPengajuans' => $jenisPengajuans,
                'divisis' => $divisis,
            ],
            'permissions' => [
                'create' => $user->hasPermission('pengajuan_dana.create'),
                'edit' => $user->hasPermission('pengajuan_dana.edit'),
                'delete' => $user->hasPermission('pengajuan_dana.delete'),
                'view_all' => $user->hasPermission('pengajuan_dana.view_all'),
                'approve' => $user->hasPermission('pengajuan_dana.approve'),
            ],
        ]);
    }

    /**
     * Show form to select jenis pengajuan.
     */
    public function selectJenis()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.create')) {
            abort(403);
        }

        return view('pengajuan-dana.select-jenis');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.create')) {
            abort(403);
        }

        // Get jenis pengajuan from query parameter
        $jenisPengajuan = $request->query('jenis');

        // Validate jenis pengajuan
        $validJenis = ['kegiatan', 'pengadaan', 'pembayaran', 'honorarium', 'sewa', 'konsumi', 'reimbursement', 'lainnya'];
        if (!$jenisPengajuan || !in_array($jenisPengajuan, $validJenis)) {
            return redirect()->route('pengajuan-dana.select-jenis');
        }

        // Get active periode anggaran in penggunaan phase
        $activePeriode = \App\Models\PeriodeAnggaran::active()->first();

        if (!$activePeriode) {
            return redirect()->route('pengajuan-dana.select-jenis')
                ->with('error', 'Tidak dapat membuat pengajuan dana. Belum ada periode anggaran yang aktif dalam fase Penggunaan.');
        }

        // Get program kerjas for user's divisions, filtered by active periode in penggunaan phase
        $programKerjas = ProgramKerja::whereIn('divisi_id', $user->divisionIds())
            ->where('periode_anggaran_id', $activePeriode->id)
            ->where('status', 'active')
            ->orderBy('nama_program')
            ->get();

        // Get users for karyawan dropdown
        $users = \App\Models\User::orderBy('name')->get();

        return view('pengajuan-dana.create', [
            'programKerjas' => $programKerjas,
            'users' => $users,
            'jenisPengajuan' => $jenisPengajuan,
            'user' => $user,
            'activePeriode' => $activePeriode,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengajuanDanaRequest $request)
    {
        DB::beginTransaction();
        try {
            // Generate nomor pengajuan
            $nomorPengajuan = NumberingService::generateNomorPengajuan();

            // Prepare common data
            $pengajuanData = [
                'nomor_pengajuan' => $nomorPengajuan,
                'judul_pengajuan' => $request->judul_pengajuan,
                'jenis_pengajuan' => $request->jenis_pengajuan,
                'program_kerja_id' => $request->program_kerja_id,
                'divisi_id' => $request->divisi_id,
                'created_by' => Auth::id(),
                'tanggal_pengajuan' => $request->tanggal_pengajuan ?? now()->toDateString(),
                'total_pengajuan' => $request->total_pengajuan,
                'deskripsi' => $request->deskripsi,
                'nama_bank' => $request->nama_bank,
                'rekening_tujuan' => $request->rekening_tujuan,
                'status' => 'draft',
                'catatan' => $request->catatan,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add detail_anggaran_id for honorarium
            if ($request->jenis_pengajuan === 'honorarium' && $request->detail_anggaran_id) {
                $pengajuanData['detail_anggaran_id'] = $request->detail_anggaran_id;
                $pengajuanData['sub_program_id'] = $request->sub_program_id;
            }

            // Add penerima_manfaat fields only for non-honorarium
            if ($request->jenis_pengajuan !== 'honorarium') {
                $pengajuanData['periode_mulai'] = $request->periode_mulai;
                $pengajuanData['periode_selesai'] = $request->periode_selesai;

                // Map form values to database ENUM values
                $jenisPenerimaMap = [
                    'karyawan' => 'pegawai',
                    'vendor' => 'vendor',
                    'lainnya' => 'non_pegawai',
                ];
                $pengajuanData['penerima_manfaat_type'] = $jenisPenerimaMap[$request->jenis_penerima] ?? $request->jenis_penerima;

                $pengajuanData['penerima_manfaat_id'] = $request->penerima_manfaat_id;
                $pengajuanData['penerima_manfaat_name'] = $request->penerima_manfaat_name;
                $pengajuanData['penerima_manfaat_detail'] = $request->penerima_manfaat_detail;
            }

            // Create pengajuan dana
            $pengajuan = PengajuanDana::create($pengajuanData);

            // Handle based on jenis pengajuan
            if ($request->jenis_pengajuan === 'honorarium') {
                // Store honorarium details
                foreach ($request->honorarium_details as $index => $detail) {
                    $lampiranPath = null;
                    $lampiranFilename = null;

                    // Handle file upload for each recipient
                    if ($request->hasFile("honorarium_details.$index.lampiran")) {
                        $file = $request->file("honorarium_details.$index.lampiran");
                        $lampiranPath = $file->store('honorarium-lampiran', 'public');
                        $lampiranFilename = $file->getClientOriginalName();
                    }

                    \App\Models\HonorariumDetail::create([
                        'pengajuan_dana_id' => $pengajuan->id,
                        'penerima_manfaat_type' => $detail['penerima_manfaat_type'],
                        'penerima_manfaat_id' => $detail['penerima_manfaat_id'] ?? null,
                        'penerima_manfaat_name' => $detail['penerima_manfaat_name'] ?? null,
                        'jumlah_honor' => $detail['jumlah_honor'],
                        'nomor_rekening' => $detail['nomor_rekening'] ?? null,
                        'deskripsi' => $detail['deskripsi'] ?? null,
                        'lampiran' => $lampiranPath,
                        'lampiran_filename' => $lampiranFilename,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Store regular detail pengajuan
                foreach ($request->details as $detail) {
                    $volume = (float) ($detail['volume'] ?? 0);
                    $hargaSatuan = (float) ($detail['harga_satuan'] ?? 0);
                    $subtotal = $volume * $hargaSatuan;

                    DetailPengajuan::create([
                        'pengajuan_dana_id' => $pengajuan->id,
                        'sub_program_id' => $detail['sub_program_id'] ?? $request->sub_program_id,
                        'detail_anggaran_id' => $detail['detail_anggaran_id'] ?? null,
                        'uraian' => $detail['uraian'],
                        'volume' => $volume,
                        'satuan' => $detail['satuan'],
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Handle global attachments for all jenis pengajuan (both honorarium and regular)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('pengajuan-attachments', 'public');

                    $pengajuan->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuan->id)
                ->with('success', 'Pengajuan dana berhasil dibuat dengan nomor: ' . $nomorPengajuan);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create pengajuan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat pengajuan dana. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Check permission
        if (!$this->canViewPengajuan($pengajuanDana, $user)) {
            abort(403);
        }

        // Load relationships that exist
        $pengajuanDana->load(['divisi', 'programKerja', 'subProgram', 'createdBy', 'details.subProgram', 'approvals.approver', 'pencairanDana']);
        $pengajuanDana->load(['attachments', 'honorariumDetails.karyawan']);

        // Get approval status
        $approvalStatus = ApprovalService::getApprovalStatus($pengajuanDana->id);
        $nextApproval = ApprovalService::getNextApproval($pengajuanDana->id);
        $canApprove = ApprovalService::canApprove($user, $pengajuanDana);

        return view('pengajuan-dana.show', [
            'pengajuan' => $pengajuanDana,
            'approvalStatus' => $approvalStatus,
            'nextApproval' => $nextApproval,
            'canApprove' => $canApprove,
            'permissions' => [
                'edit' => $user->hasPermission('pengajuan_dana.edit') && $pengajuanDana->status === 'draft',
                'delete' => $user->hasPermission('pengajuan_dana.delete') && $pengajuanDana->status === 'draft',
                'submit' => $pengajuanDana->created_by === $user->id && $pengajuanDana->status === 'draft',
                'approve' => $canApprove,
                'create_pencairan' => $user->hasPermission('pencairan_dana.create') && $pengajuanDana->status === 'disetujui',
                'create_lpj' => $pengajuanDana->created_by === $user->id && in_array($pengajuanDana->status, ['dicairkan', 'lpj_dibuat']),
                'create_refund' => $user->hasPermission('refund.create') && in_array($pengajuanDana->status, ['dicairkan', 'lpj_approved', 'selesai']),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('pengajuan_dana.edit') ||
            ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') ||
            ($pengajuanDana->created_by != $user->id && !$user->hasPermission('pengajuan_dana.edit_all'))) {
            abort(403);
        }

        $pengajuanDana->load([
            'details.subProgram',
            'attachments',
        ]);

        // Get available program kerja based on user's divisi
        $programKerjas = ProgramKerja::where('divisi_id', $pengajuanDana->divisi_id)
            ->where('status', 'active')
            ->orderBy('nama_program')
            ->get();

        // Get divisi options
        $divisis = Divisi::orderBy('nama_divisi')->get();

        // Get penerima manfaat options
        $penerimaOptions = PenerimaManfaatService::getPenerimaManfaatOptions($pengajuanDana->jenis_pengajuan);

        return view('pengajuan-dana.edit', [
            'pengajuan' => $pengajuanDana,
            'programKerjas' => $programKerjas,
            'divisis' => $divisis,
            'penerimaOptions' => $penerimaOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengajuanDanaRequest $request, PengajuanDana $pengajuanDana)
    {
        DB::beginTransaction();
        try {
            // Update pengajuan dana
            $pengajuanDana->update([
                'judul_pengajuan' => $request->judul_pengajuan ?? $pengajuanDana->judul_pengajuan,
                'jenis_pengajuan' => $request->jenis_pengajuan ?? $pengajuanDana->jenis_pengajuan,
                'program_kerja_id' => $request->program_kerja_id ?? $pengajuanDana->program_kerja_id,
                'divisi_id' => $request->divisi_id ?? $pengajuanDana->divisi_id,
                'tanggal_pengajuan' => $request->tanggal_pengajuan ?? $pengajuanDana->tanggal_pengajuan,
                'periode_mulai' => $request->periode_mulai ?? $pengajuanDana->periode_mulai,
                'periode_selesai' => $request->periode_selesai ?? $pengajuanDana->periode_selesai,
                'total_pengajuan' => $request->total_pengajuan ?? $pengajuanDana->total_pengajuan,
                'deskripsi' => $request->deskripsi ?? $pengajuanDana->deskripsi,
                'nama_bank' => $request->nama_bank ?? $pengajuanDana->nama_bank,
                'rekening_tujuan' => $request->rekening_tujuan ?? $pengajuanDana->rekening_tujuan,
                'penerima_manfaat_type' => $request->jenis_penerima ?? $pengajuanDana->penerima_manfaat_type,
                'penerima_manfaat_id' => $request->penerima_manfaat_id ?? $pengajuanDana->penerima_manfaat_id,
                'penerima_manfaat_name' => $request->penerima_manfaat_name ?? $pengajuanDana->penerima_manfaat_name,
                'penerima_manfaat_detail' => $request->penerima_manfaat_detail ?? $pengajuanDana->penerima_manfaat_detail,
                'catatan' => $request->catatan ?? $pengajuanDana->catatan,
                'updated_at' => now(),
            ]);

            // Update detail pengajuan
            if ($request->has('details')) {
                // Remove details that are not in the request
                $detailIds = array_filter(array_column($request->details, 'id'));
                DetailPengajuan::where('pengajuan_dana_id', $pengajuanDana->id)
                    ->whereNotIn('id', $detailIds)
                    ->delete();

                // Create or update details
                foreach ($request->details as $detail) {
                    if (isset($detail['id'])) {
                        // Update existing detail
                        DetailPengajuan::where('id', $detail['id'])
                            ->where('pengajuan_dana_id', $pengajuanDana->id)
                            ->update([
                                'sub_program_id' => $detail['sub_program_id'] ?? $request->sub_program_id,
                                'detail_anggaran_id' => $detail['detail_anggaran_id'] ?? null,
                                'uraian' => $detail['uraian'],
                                'volume' => $detail['volume'],
                                'satuan' => $detail['satuan'],
                                'harga_satuan' => $detail['harga_satuan'],
                                'subtotal' => $detail['subtotal'],
                                'updated_at' => now(),
                            ]);
                    } else {
                        // Create new detail
                        DetailPengajuan::create([
                            'pengajuan_dana_id' => $pengajuanDana->id,
                            'sub_program_id' => $detail['sub_program_id'] ?? $request->sub_program_id,
                            'detail_anggaran_id' => $detail['detail_anggaran_id'] ?? null,
                            'uraian' => $detail['uraian'],
                            'volume' => $detail['volume'],
                            'satuan' => $detail['satuan'],
                            'harga_satuan' => $detail['harga_satuan'],
                            'subtotal' => $detail['subtotal'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Handle removed attachments
            if ($request->has('removed_attachments')) {
                foreach ($request->removed_attachments as $attachmentId) {
                    $attachment = $pengajuanDana->attachments()->find($attachmentId);
                    if ($attachment) {
                        // Delete file from storage
                        Storage::disk('public')->delete($attachment->path);
                        $attachment->delete();
                    }
                }
            }

            // Handle new attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('pengajuan-attachments', 'public');

                    $pengajuanDana->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana->id)
                ->with('success', 'Pengajuan dana berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update pengajuan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengajuan dana. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('pengajuan_dana.delete') || $pengajuanDana->status !== 'draft') {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Delete related notifications
            \App\Models\Notification::where('type', 'approval')
                ->where('notifiable_type', \App\Models\PengajuanDana::class)
                ->where('notifiable_id', $pengajuanDana->id)
                ->delete();

            // Delete related approvals
            \App\Models\Approval::where('pengajuan_dana_id', $pengajuanDana->id)->delete();

            // Delete attachments
            foreach ($pengajuanDana->attachments as $attachment) {
                \Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            // Delete detail pengajuan
            $pengajuanDana->details()->delete();

            // Delete pengajuan
            $pengajuanDana->delete();

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.index')
                ->with('success', 'Pengajuan dana berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete pengajuan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pengajuan dana. Silakan coba lagi.');
        }
    }

    /**
     * Cancel pengajuan dana
     */
    public function cancel(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Only creator can cancel their own pengajuan
        if ($pengajuanDana->created_by != $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan pengajuan ini.');
        }

        // Can only cancel draft or menunggu_approval status
        if (!in_array($pengajuanDana->status, ['draft', 'menunggu_approval'])) {
            return redirect()
                ->back()
                ->with('error', 'Pengajuan tidak dapat dibatalkan karena sudah dalam proses lebih lanjut.');
        }

        DB::beginTransaction();
        try {
            $pengajuanDana->update([
                'status' => 'cancelled',
            ]);

            // Delete related approval notifications
            \App\Models\Notification::where('type', 'approval')
                ->where('notifiable_type', \App\Models\PengajuanDana::class)
                ->where('notifiable_id', $pengajuanDana->id)
                ->delete();

            // Also delete pending approvals
            \App\Models\Approval::where('pengajuan_dana_id', $pengajuanDana->id)
                ->where('status', 'pending')
                ->delete();

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana)
                ->with('success', 'Pengajuan dana berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to cancel pengajuan dana: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal membatalkan pengajuan dana. Silakan coba lagi.');
        }
    }

    /**
     * Submit pengajuan for approval
     */
    public function submit(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Check permission
        if ($pengajuanDana->created_by != $user->id || $pengajuanDana->status !== 'draft') {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Create approval workflow
            $approvals = ApprovalService::createApprovalWorkflow($pengajuanDana);

            if (empty($approvals)) {
                throw new \Exception('Tidak ada workflow approval yang tersedia');
            }

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana->id)
                ->with('success', 'Pengajuan berhasil disubmit dan menunggu approval');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to submit pengajuan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal submit pengajuan. ' . $e->getMessage());
        }
    }

    /**
     * Check if user can view pengajuan
     */
    private function canViewPengajuan($pengajuan, $user): bool
    {
        // User can view their own pengajuan
        if ($pengajuan->created_by === $user->id) {
            return true;
        }

        // User with view_all permission
        if ($user->hasPermission('pengajuan_dana.view_all')) {
            return true;
        }

        // User with view_divisi permission for same divisi
        if ($user->hasPermission('pengajuan_dana.view_divisi') && $pengajuan->divisi_id === $user->divisi_id) {
            return true;
        }

        // User in approval chain
        if ($pengajuan->approvals()->where('approver_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Get penerima manfaat options API
     */
    public function getPenerimaOptions(Request $request)
    {
        $jenisPengajuan = $request->jenis_pengajuan;

        if (!$jenisPengajuan) {
            return response()->json(['error' => 'Jenis pengajuan wajib diisi'], 400);
        }

        $options = PenerimaManfaatService::getPenerimaManfaatOptions($jenisPengajuan);

        return response()->json($options);
    }

    /**
     * Export pengajuan data
     */
    public function export(Request $request)
    {
        // Implementation for export functionality
        // This can be implemented later with Excel export
        return response()->json(['message' => 'Export feature coming soon']);
    }
}