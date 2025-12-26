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

        $query = PencairanDana::with([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
            'createdBy',
            'processedBy',
        ]);

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('divisi_id')) {
            $query->whereHas('pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pencairan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pencairan', '<=', $request->tanggal_selesai);
        }

        $pencairans = $query->orderBy('tanggal_pencairan', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $statuses = PencairanDana::select('status')->distinct()->pluck('status');
        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();

        return view('pencairan-dana.index', [
            'pencairans' => $pencairans,
            'filters' => $request->only(['search', 'status', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'statuses' => $statuses,
                'divisis' => $divisis,
            ],
            'permissions' => [
                'create' => $user->hasPermission('pencairan_dana.create'),
                'edit' => $user->hasPermission('pencairan_dana.update'),
                'delete' => $user->hasPermission('pencairan_dana.delete'),
                'process' => $user->hasPermission('pencairan_dana.approve'),
            ],
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
        $pengajuan = null;

        if ($pengajuanId) {
            $pengajuan = PengajuanDana::with(['divisi', 'programKerja', 'detailPengajuan'])
                ->findOrFail($pengajuanId);

            // Check if can create pencairan
            if (!PencairanService::canCreatePencairan($pengajuan)) {
                return redirect()
                    ->route('pengajuan-dana.show', $pengajuanId)
                    ->with('error', 'Pencairan tidak dapat dibuat untuk pengajuan ini');
            }
        }

        // Get approved pengajuans for selection
        $pengajuans = PengajuanDana::with(['divisi', 'programKerja'])
            ->where('status', 'disetujui')
            ->whereDoesntHave('pencairanDana')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pencairan-dana.create', [
            'pengajuans' => $pengajuans,
            'selectedPengajuan' => $pengajuan,
            'user' => $user,
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

        if (!$user->hasPermission('pencairan_dana.read')) {
            abort(403);
        }

        $pencairanDana->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
            'detailPencairans.detailPengajuan.subProgram',
            'createdBy',
            'processedBy',
            'pengajuanDana.laporanPertanggungJawaban',
            'pengajuanDana.refunds',
        ]);

        return view('pencairan-dana.show', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pencairanDana->pengajuanDana,
            'permissions' => [
                'edit' => $user->hasPermission('pencairan_dana.update') && $pencairanDana->status === 'pending',
                'delete' => $user->hasPermission('pencairan_dana.delete') && $pencairanDana->status === 'pending',
                'process' => $user->hasPermission('pencairan_dana.approve') && $pencairanDana->status === 'pending',
                'verify' => $pencairanDana->pengajuanDana->jenis_pengajuan === 'pembayaran' &&
                            in_array($pencairanDana->status, ['processed']) &&
                            ($pencairanDana->pengajuanDana->created_by === $user->id ||
                             $pencairanDana->pengajuanDana->penerima_manfaat_id === $user->id),
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
            'detailPencairans.detailPengajuan.subProgram',
        ]);

        return view('pencairan-dana.edit', [
            'pencairan' => $pencairanDana,
            'pengajuan' => $pencairanDana->pengajuanDana,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePencairanDanaRequest $request, PencairanDana $pencairanDana)
    {
        DB::beginTransaction();
        try {
            // Update pencairan
            $pencairanDana->update($request->validated());

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

        // Check if user can verify
        if ($pengajuan->jenis_pengajuan !== 'pembayaran' ||
            !in_array($pencairanDana->status, ['processed']) ||
            ($pengajuan->created_by !== $user->id && $pengajuan->penerima_manfaat_id !== $user->id)) {
            abort(403);
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
            'detailPencairans.detailPengajuan.subProgram',
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