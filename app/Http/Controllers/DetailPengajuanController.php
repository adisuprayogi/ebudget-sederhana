<?php

namespace App\Http\Controllers;

use App\Models\DetailPengajuan;
use App\Models\PengajuanDana;
use App\Models\SubProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailPengajuanController extends Controller
{
    /**
     * Display a listing of detail pengajuan for a specific pengajuan dana.
     */
    public function index(Request $request, PengajuanDana $pengajuanDana)
    {
        $this->authorize('view', $pengajuanDana);

        $details = $pengajuanDana->detailPengajuan()
            ->with(['subProgram'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate totals
        $totalSubtotal = $details->sum('subtotal');

        return view('detail-pengajuan.index', [
            'pengajuanDana' => $pengajuanDana,
            'details' => $details,
            'totals' => [
                'subtotal' => $totalSubtotal,
                'count' => $details->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new detail pengajuan.
     */
    public function create(Request $request, PengajuanDana $pengajuanDana)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return back()->with('error', 'Detail hanya dapat ditambahkan pada status draft atau revisi.');
        }

        // Get available sub programs based on pengajuan's program kerja
        $subPrograms = [];
        if ($pengajuanDana->programKerja) {
            $subPrograms = SubProgram::where('program_kerja_id', $pengajuanDana->program_kerja_id)
                ->where('is_active', true)
                ->orderBy('nama_sub_program')
                ->get();
        }

        return view('detail-pengajuan.create', [
            'pengajuanDana' => $pengajuanDana,
            'subPrograms' => $subPrograms,
        ]);
    }

    /**
     * Store a newly created detail pengajuan in storage.
     */
    public function store(Request $request, PengajuanDana $pengajuanDana)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return back()->with('error', 'Detail hanya dapat ditambahkan pada status draft atau revisi.');
        }

        $validated = $request->validate([
            'sub_program_id' => 'nullable|exists:sub_programs,id',
            'uraian' => 'required|string|max:500',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = $validated['volume'] * $validated['harga_satuan'];

            $detail = DetailPengajuan::create([
                'pengajuan_dana_id' => $pengajuanDana->id,
                'sub_program_id' => $validated['sub_program_id'] ?? null,
                'uraian' => $validated['uraian'],
                'volume' => $validated['volume'],
                'satuan' => $validated['satuan'],
                'harga_satuan' => $validated['harga_satuan'],
                'subtotal' => $subtotal,
            ]);

            // Update pengajuan total
            $newTotal = $pengajuanDana->detailPengajuan()->sum('subtotal');
            $pengajuanDana->update([
                'total_pengajuan' => $newTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana)
                ->with('success', 'Detail pengajuan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create detail pengajuan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan detail pengajuan. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified detail pengajuan.
     */
    public function show(PengajuanDana $pengajuanDana, DetailPengajuan $detailPengajuan)
    {
        $this->authorize('view', $pengajuanDana);

        // Verify detail belongs to pengajuan
        if ($detailPengajuan->pengajuan_dana_id !== $pengajuanDana->id) {
            abort(404);
        }

        $detailPengajuan->load(['subProgram', 'pengajuanDana']);

        return view('detail-pengajuan.show', [
            'detail' => $detailPengajuan,
            'pengajuanDana' => $pengajuanDana,
        ]);
    }

    /**
     * Show the form for editing the specified detail pengajuan.
     */
    public function edit(PengajuanDana $pengajuanDana, DetailPengajuan $detailPengajuan)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return back()->with('error', 'Detail hanya dapat diedit pada status draft atau revisi.');
        }

        // Verify detail belongs to pengajuan
        if ($detailPengajuan->pengajuan_dana_id !== $pengajuanDana->id) {
            abort(404);
        }

        // Get available sub programs
        $subPrograms = [];
        if ($pengajuanDana->programKerja) {
            $subPrograms = SubProgram::where('program_kerja_id', $pengajuanDana->program_kerja_id)
                ->where('is_active', true)
                ->orderBy('nama_sub_program')
                ->get();
        }

        return view('detail-pengajuan.edit', [
            'detail' => $detailPengajuan,
            'pengajuanDana' => $pengajuanDana,
            'subPrograms' => $subPrograms,
        ]);
    }

    /**
     * Update the specified detail pengajuan in storage.
     */
    public function update(Request $request, PengajuanDana $pengajuanDana, DetailPengajuan $detailPengajuan)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return back()->with('error', 'Detail hanya dapat diubah pada status draft atau revisi.');
        }

        // Verify detail belongs to pengajuan
        if ($detailPengajuan->pengajuan_dana_id !== $pengajuanDana->id) {
            abort(404);
        }

        $validated = $request->validate([
            'sub_program_id' => 'nullable|exists:sub_programs,id',
            'uraian' => 'required|string|max:500',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = $validated['volume'] * $validated['harga_satuan'];

            $detailPengajuan->update([
                'sub_program_id' => $validated['sub_program_id'] ?? null,
                'uraian' => $validated['uraian'],
                'volume' => $validated['volume'],
                'satuan' => $validated['satuan'],
                'harga_satuan' => $validated['harga_satuan'],
                'subtotal' => $subtotal,
            ]);

            // Update pengajuan total
            $newTotal = $pengajuanDana->detailPengajuan()->sum('subtotal');
            $pengajuanDana->update([
                'total_pengajuan' => $newTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana)
                ->with('success', 'Detail pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update detail pengajuan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui detail pengajuan. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified detail pengajuan from storage.
     */
    public function destroy(PengajuanDana $pengajuanDana, DetailPengajuan $detailPengajuan)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return back()->with('error', 'Detail hanya dapat dihapus pada status draft atau revisi.');
        }

        // Verify detail belongs to pengajuan
        if ($detailPengajuan->pengajuan_dana_id !== $pengajuanDana->id) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $detailPengajuan->delete();

            // Update pengajuan total
            $newTotal = $pengajuanDana->detailPengajuan()->sum('subtotal');
            $pengajuanDana->update([
                'total_pengajuan' => $newTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('pengajuan-dana.show', $pengajuanDana)
                ->with('success', 'Detail pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete detail pengajuan: ' . $e->getMessage());

            return back()
                ->with('error', 'Gagal menghapus detail pengajuan. ' . $e->getMessage());
        }
    }

    /**
     * Bulk create detail pengajuan items.
     */
    public function bulkStore(Request $request, PengajuanDana $pengajuanDana)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return response()->json(['error' => 'Detail hanya dapat ditambahkan pada status draft atau revisi.'], 403);
        }

        $validated = $request->validate([
            'details' => 'required|array|min:1',
            'details.*.sub_program_id' => 'nullable|exists:sub_programs,id',
            'details.*.uraian' => 'required|string|max:500',
            'details.*.volume' => 'required|numeric|min:0',
            'details.*.satuan' => 'required|string|max:50',
            'details.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['details'] as $detailData) {
                $subtotal = $detailData['volume'] * $detailData['harga_satuan'];

                DetailPengajuan::create([
                    'pengajuan_dana_id' => $pengajuanDana->id,
                    'sub_program_id' => $detailData['sub_program_id'] ?? null,
                    'uraian' => $detailData['uraian'],
                    'volume' => $detailData['volume'],
                    'satuan' => $detailData['satuan'],
                    'harga_satuan' => $detailData['harga_satuan'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Update pengajuan total
            $newTotal = $pengajuanDana->detailPengajuan()->sum('subtotal');
            $pengajuanDana->update([
                'total_pengajuan' => $newTotal,
            ]);

            DB::commit();

            return response()->json([
                'message' => count($validated['details']) . ' detail pengajuan berhasil ditambahkan.',
                'total_pengajuan' => $newTotal,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to bulk create detail pengajuan: ' . $e->getMessage());

            return response()->json(['error' => 'Gagal menambahkan detail pengajuan. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Bulk delete detail pengajuan items.
     */
    public function bulkDestroy(Request $request, PengajuanDana $pengajuanDana)
    {
        $this->authorize('update', $pengajuanDana);

        if ($pengajuanDana->status !== 'draft' && $pengajuanDana->status !== 'revisi') {
            return response()->json(['error' => 'Detail hanya dapat dihapus pada status draft atau revisi.'], 403);
        }

        $validated = $request->validate([
            'detail_ids' => 'required|array|min:1',
            'detail_ids.*' => 'exists:detail_pengajuans,id',
        ]);

        DB::beginTransaction();
        try {
            $deletedCount = DetailPengajuan::where('pengajuan_dana_id', $pengajuanDana->id)
                ->whereIn('id', $validated['detail_ids'])
                ->delete();

            // Update pengajuan total
            $newTotal = $pengajuanDana->detailPengajuan()->sum('subtotal');
            $pengajuanDana->update([
                'total_pengajuan' => $newTotal,
            ]);

            DB::commit();

            return response()->json([
                'message' => $deletedCount . ' detail pengajuan berhasil dihapus.',
                'total_pengajuan' => $newTotal,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to bulk delete detail pengajuan: ' . $e->getMessage());

            return response()->json(['error' => 'Gagal menghapus detail pengajuan. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get detail pengajuan statistics for a pengajuan dana.
     */
    public function statistics(PengajuanDana $pengajuanDana)
    {
        $this->authorize('view', $pengajuanDana);

        $details = $pengajuanDana->detailPengajuan;

        $stats = [
            'count' => $details->count(),
            'total_subtotal' => $details->sum('subtotal'),
            'avg_subtotal' => $details->avg('subtotal') ?? 0,
            'min_subtotal' => $details->min('subtotal') ?? 0,
            'max_subtotal' => $details->max('subtotal') ?? 0,
            'by_sub_program' => [],
        ];

        // Group by sub program
        foreach ($details as $detail) {
            $subProgramName = $detail->subProgram ? $detail->subProgram->nama_sub_program : 'Tanpa Sub Program';

            if (!isset($stats['by_sub_program'][$subProgramName])) {
                $stats['by_sub_program'][$subProgramName] = [
                    'count' => 0,
                    'total' => 0,
                ];
            }

            $stats['by_sub_program'][$subProgramName]['count']++;
            $stats['by_sub_program'][$subProgramName]['total'] += $detail->subtotal;
        }

        return response()->json($stats);
    }
}
