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

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $pengajuans = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $statuses = PengajuanDana::select('status')->distinct()->pluck('status');
        $jenisPengajuans = PengajuanDana::select('jenis_pengajuan')->distinct()->pluck('jenis_pengajuan');
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('pengajuan-dana.index', [
            'pengajuans' => $pengajuans,
            'filters' => $request->only(['search', 'status', 'jenis_pengajuan', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'statuses' => $statuses,
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.create')) {
            abort(403);
        }

        // Get available program kerja based on user's divisi
        $programKerjas = ProgramKerja::where('divisi_id', $user->divisi_id)
            ->where('is_active', true)
            ->orderBy('nama_program')
            ->get();

        // Get divisi options
        $divisis = Divisi::orderBy('nama_divisi')->get();

        // Get penerima manfaat options
        $penerimaOptions = PenerimaManfaatService::getPenerimaManfaatOptions('');

        return view('pengajuan-dana.create', [
            'programKerjas' => $programKerjas,
            'divisis' => $divisis,
            'penerimaOptions' => $penerimaOptions,
            'user' => $user,
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

            // Create pengajuan dana
            $pengajuan = PengajuanDana::create([
                'nomor_pengajuan' => $nomorPengajuan,
                'judul_pengajuan' => $request->judul_pengajuan,
                'jenis_pengajuan' => $request->jenis_pengajuan,
                'program_kerja_id' => $request->program_kerja_id,
                'divisi_id' => $request->divisi_id,
                'created_by' => Auth::id(),
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'periode_mulai' => $request->periode_mulai,
                'periode_selesai' => $request->periode_selesai,
                'total_pengajuan' => $request->total_pengajuan,
                'deskripsi' => $request->deskripsi,
                'penerima_manfaat_type' => $request->penerima_manfaat_type,
                'penerima_manfaat_id' => $request->penerima_manfaat_id,
                'penerima_manfaat_name' => $request->penerima_manfaat_name,
                'penerima_manfaat_detail' => $request->penerima_manfaat_detail,
                'status' => 'draft',
                'catatan' => $request->catatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create detail pengajuan
            foreach ($request->details as $detail) {
                DetailPengajuan::create([
                    'pengajuan_dana_id' => $pengajuan->id,
                    'sub_program_id' => $detail['sub_program_id'] ?? null,
                    'uraian' => $detail['uraian'],
                    'volume' => $detail['volume'],
                    'satuan' => $detail['satuan'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'subtotal' => $detail['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Handle attachments
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

        $pengajuanDana->load([
            'divisi',
            'programKerja',
            'createdBy',
            'detailPengajuan.subProgram',
            'approvals.approver',
            'pencairanDana',
            'laporanPertanggungJawaban',
            'refunds',
            'attachments',
        ]);

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
            ($pengajuanDana->created_by !== $user->id && !$user->hasPermission('pengajuan_dana.edit_all'))) {
            abort(403);
        }

        $pengajuanDana->load([
            'detailPengajuan.subProgram',
            'attachments',
        ]);

        // Get available program kerja based on user's divisi
        $programKerjas = ProgramKerja::where('divisi_id', $pengajuanDana->divisi_id)
            ->where('is_active', true)
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
                'penerima_manfaat_type' => $request->penerima_manfaat_type ?? $pengajuanDana->penerima_manfaat_type,
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
                                'sub_program_id' => $detail['sub_program_id'] ?? null,
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
                            'sub_program_id' => $detail['sub_program_id'] ?? null,
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
            // Delete attachments
            foreach ($pengajuanDana->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            // Delete detail pengajuan
            $pengajuanDana->detailPengajuan()->delete();

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
     * Submit pengajuan for approval
     */
    public function submit(PengajuanDana $pengajuanDana)
    {
        $user = Auth::user();

        // Check permission
        if ($pengajuanDana->created_by !== $user->id || $pengajuanDana->status !== 'draft') {
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