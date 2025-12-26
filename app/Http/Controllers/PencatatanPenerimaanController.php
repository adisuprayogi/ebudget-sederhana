<?php

namespace App\Http\Controllers;

use App\Models\PencatatanPenerimaan;
use App\Models\PerencanaanPenerimaan;
use App\Models\PeriodeAnggaran;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PencatatanPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PencatatanPenerimaan::with(['perencanaanPenerimaan', 'periodeAnggaran', 'sumberDana', 'createdBy']);

        // Apply filters
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        if ($request->filled('perencanaan_penerimaan_id')) {
            $query->where('perencanaan_penerimaan_id', $request->perencanaan_penerimaan_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('uraian', 'like', "%{$search}%");
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_penerimaan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_penerimaan', '<=', $request->tanggal_selesai);
        }

        $pencatatanPenerimaans = $query->orderBy('tanggal_penerimaan', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();
        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();
        $perencanaanPenerimaans = PerencanaanPenerimaan::orderBy('created_at', 'desc')->get();

        // Calculate summary
        $totalDiterima = 0;
        foreach ($pencatatanPenerimaans as $pencatatan) {
            $totalDiterima += $pencatatan->jumlah_diterima;
        }

        return view('pencatatan-penerimaan.index', [
            'pencatatanPenerimaans' => $pencatatanPenerimaans,
            'filters' => $request->only(['periode_anggaran_id', 'sumber_dana_id', 'perencanaan_penerimaan_id', 'search', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'periodeAnggarans' => $periodeAnggarans,
                'sumberDanas' => $sumberDanas,
                'perencanaanPenerimaans' => $perencanaanPenerimaans,
            ],
            'summary' => [
                'total_diterima' => $totalDiterima,
                'jumlah_record' => $pencatatanPenerimaans->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $periodeAnggarans = PeriodeAnggaran::where('status', 'active')
            ->orderBy('tahun_anggaran', 'desc')
            ->get();

        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();

        // Find currently active periode anggaran (in penggunaan phase)
        $activePeriodeAnggaran = PeriodeAnggaran::current()->first();

        // If no periode in penggunaan phase, check for perencangan phase
        $periodeForFilter = $activePeriodeAnggaran;
        if (!$periodeForFilter) {
            $periodeForFilter = PeriodeAnggaran::fase('perencangan')
                ->where('status', 'active')
                ->first();
        }

        // Get perencanaan penerimaans for reference (filtered by active periode)
        $perencanaanPenerimaansQuery = PerencanaanPenerimaan::with(['periodeAnggaran', 'sumberDana'])
            ->orderBy('created_at', 'desc');

        if ($periodeForFilter) {
            $perencanaanPenerimaansQuery->where('periode_anggaran_id', $periodeForFilter->id);
        }

        $perencanaanPenerimaans = $perencanaanPenerimaansQuery->get();

        // Pre-fill data from perencanaan_penerimaan_id if provided
        $defaultPerencanaan = null;
        $defaultPeriodeAnggaranId = $activePeriodeAnggaran?->id;

        if ($request->filled('perencanaan_penerimaan_id')) {
            $defaultPerencanaan = PerencanaanPenerimaan::with(['periodeAnggaran', 'sumberDana'])
                ->find($request->perencanaan_penerimaan_id);
            $defaultPeriodeAnggaranId = $defaultPerencanaan?->periode_anggaran_id ?? $defaultPeriodeAnggaranId;
        }

        return view('pencatatan-penerimaan.create', [
            'periodeAnggarans' => $periodeAnggarans,
            'sumberDanas' => $sumberDanas,
            'perencanaanPenerimaans' => $perencanaanPenerimaans,
            'defaultPerencanaan' => $defaultPerencanaan,
            'defaultPeriodeAnggaranId' => $defaultPeriodeAnggaranId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'perencanaan_penerimaan_id' => 'required|exists:perencanaan_penerimaans,id',
            'periode_anggaran_id' => 'required|exists:periode_anggaran,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'tanggal_penerimaan' => 'required|date',
            'uraian' => 'required|string|max:500',
            'jumlah_diterima' => 'required|numeric|min:0',
            'bukti_penerimaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('bukti_penerimaan')) {
                $file = $request->file('bukti_penerimaan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti-penerimaan', $fileName, 'public');
            }

            $pencatatan = PencatatanPenerimaan::create([
                'perencanaan_penerimaan_id' => $validated['perencanaan_penerimaan_id'],
                'periode_anggaran_id' => $validated['periode_anggaran_id'],
                'sumber_dana_id' => $validated['sumber_dana_id'],
                'tanggal_penerimaan' => $validated['tanggal_penerimaan'],
                'uraian' => $validated['uraian'],
                'jumlah_diterima' => $validated['jumlah_diterima'],
                'bukti_penerimaan' => $filePath,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('perencanaan-penerimaan.show', $pencatatan->perencanaan_penerimaan_id)
                ->with('success', 'Pencatatan penerimaan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create pencatatan penerimaan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pencatatan penerimaan. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PencatatanPenerimaan $pencatatanPenerimaan)
    {
        $pencatatanPenerimaan->load([
            'perencanaanPenerimaan',
            'periodeAnggaran',
            'sumberDana',
            'createdBy',
        ]);

        return view('pencatatan-penerimaan.show', [
            'pencatatan' => $pencatatanPenerimaan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PencatatanPenerimaan $pencatatanPenerimaan)
    {
        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();
        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();
        $perencanaanPenerimaans = PerencanaanPenerimaan::with(['periodeAnggaran', 'sumberDana'])->orderBy('created_at', 'desc')->get();

        return view('pencatatan-penerimaan.edit', [
            'pencatatan' => $pencatatanPenerimaan,
            'periodeAnggarans' => $periodeAnggarans,
            'sumberDanas' => $sumberDanas,
            'perencanaanPenerimaans' => $perencanaanPenerimaans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PencatatanPenerimaan $pencatatanPenerimaan)
    {
        $validated = $request->validate([
            'perencanaan_penerimaan_id' => 'required|exists:perencanaan_penerimaans,id',
            'periode_anggaran_id' => 'required|exists:periode_anggaran,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'tanggal_penerimaan' => 'required|date',
            'uraian' => 'required|string|max:500',
            'jumlah_diterima' => 'required|numeric|min:0',
            'bukti_penerimaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'perencanaan_penerimaan_id' => $validated['perencanaan_penerimaan_id'],
                'periode_anggaran_id' => $validated['periode_anggaran_id'],
                'sumber_dana_id' => $validated['sumber_dana_id'],
                'tanggal_penerimaan' => $validated['tanggal_penerimaan'],
                'uraian' => $validated['uraian'],
                'jumlah_diterima' => $validated['jumlah_diterima'],
            ];

            // Handle file upload
            if ($request->hasFile('bukti_penerimaan')) {
                // Delete old file if exists
                if ($pencatatanPenerimaan->bukti_penerimaan) {
                    Storage::disk('public')->delete($pencatatanPenerimaan->bukti_penerimaan);
                }

                $file = $request->file('bukti_penerimaan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti-penerimaan', $fileName, 'public');
                $updateData['bukti_penerimaan'] = $filePath;
            }

            $pencatatanPenerimaan->update($updateData);

            DB::commit();

            return redirect()
                ->route('pencatatan-penerimaan.show', $pencatatanPenerimaan)
                ->with('success', 'Pencatatan penerimaan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update pencatatan penerimaan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pencatatan penerimaan. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PencatatanPenerimaan $pencatatanPenerimaan)
    {
        // Delete file if exists
        if ($pencatatanPenerimaan->bukti_penerimaan) {
            Storage::disk('public')->delete($pencatatanPenerimaan->bukti_penerimaan);
        }

        $perencanaanId = $pencatatanPenerimaan->perencanaan_penerimaan_id;
        $pencatatanPenerimaan->delete();

        // If linked to perencanaan, redirect to perencanaan show
        if ($perencanaanId) {
            return redirect()
                ->route('perencanaan-penerimaan.show', $perencanaanId)
                ->with('success', 'Pencatatan penerimaan berhasil dihapus.');
        }

        return redirect()
            ->route('pencatatan-penerimaan.index')
            ->with('success', 'Pencatatan penerimaan berhasil dihapus.');
    }

    /**
     * Get recording statistics.
     */
    public function statistics(Request $request)
    {
        $query = PencatatanPenerimaan::query();

        // Filter by periode if provided
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        $stats = [
            'total' => $query->count(),
            'total_diterima' => $query->sum('jumlah_diterima'),
        ];

        return response()->json($stats);
    }
}
