<?php

namespace App\Http\Controllers;

use App\Models\PerencanaanPenerimaan;
use App\Models\PeriodeAnggaran;
use App\Models\Divisi;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerencanaanPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PerencanaanPenerimaan::with(['periodeAnggaran', 'divisi', 'sumberDana', 'createdBy']);

        // Filter by divisi - finance roles can see all data
        $user = Auth::user();
        if (!$user->hasAnyRole(['superadmin', 'direktur_keuangan', 'staff_keuangan', 'direktur_utama'])) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                $query->whereIn('divisi_id', $accessibleDivisionIds);
            } else {
                // If no job positions, show empty result
                $query->whereRaw('1 = 0');
            }
        }

        // Default to current running periode anggaran if not provided
        $periodeAnggaranId = $request->periode_anggaran_id;
        if (!$periodeAnggaranId) {
            $currentPeriode = PeriodeAnggaran::current()->first();
            $periodeAnggaranId = $currentPeriode ? $currentPeriode->id : null;
        }

        // Apply periode anggaran filter (always applied)
        if ($periodeAnggaranId) {
            $query->where('periode_anggaran_id', $periodeAnggaranId);
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('uraian', 'like', "%{$search}%")
                  ->orWhere('kode_rekening', 'like', "%{$search}%");
        }

        $perencanaanPenerimaans = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();

        // Calculate summary
        $totalEstimasi = 0;
        $totalRealisasi = 0;
        foreach ($perencanaanPenerimaans as $perencanaan) {
            $totalEstimasi += $perencanaan->jumlah_estimasi;
            $totalRealisasi += $perencanaan->realisasi;
        }

        return view('perencanaan-penerimaan.index', [
            'perencanaanPenerimaans' => $perencanaanPenerimaans,
            'filters' => array_merge($request->only(['divisi_id', 'sumber_dana_id', 'search']), [
                'periode_anggaran_id' => $periodeAnggaranId,
            ]),
            'filterOptions' => [
                'periodeAnggarans' => $periodeAnggarans,
                'divisis' => $divisis,
                'sumberDanas' => $sumberDanas,
            ],
            'summary' => [
                'total_estimasi' => $totalEstimasi,
                'total_realisasi' => $totalRealisasi,
                'sisa' => $totalEstimasi - $totalRealisasi,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $periodeAnggarans = PeriodeAnggaran::whereIn('status', ['draft', 'active'])
            ->orderBy('tahun_anggaran', 'desc')
            ->get();

        $divisis = Divisi::orderBy('nama_divisi')->get();

        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();

        // Set default divisi
        $defaultDivisi = null;
        $user = Auth::user();
        if (!$user->hasAnyRole(['superadmin', 'direktur_keuangan', 'direktur_utama'])) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                // Use primary division if available, otherwise first accessible division
                $defaultDivisi = $user->primaryDivision()?->id ?? $accessibleDivisionIds[0];
            }
        }

        return view('perencanaan-penerimaan.create', [
            'periodeAnggarans' => $periodeAnggarans,
            'divisis' => $divisis,
            'sumberDanas' => $sumberDanas,
            'defaultDivisi' => $defaultDivisi,
        ]);
    }

    /**
     * Get months list for selected periode anggaran (AJAX)
     */
    public function getMonths(Request $request)
    {
        $periode = PeriodeAnggaran::find($request->periode_anggaran_id);

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        $startDate = Carbon::parse($periode->tanggal_mulai_penggunaan_anggaran);
        $endDate = Carbon::parse($periode->tanggal_selesai_penggunaan_anggaran);

        $months = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $months[] = [
                'key' => $current->format('Y-m'),
                'label' => $current->translatedFormat('F Y'),
            ];
            $current->addMonth();
        }

        return response()->json(['months' => $months]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Convert empty string to null for divisi_id
        if ($request->has('divisi_id') && $request->input('divisi_id') === '') {
            $request->merge(['divisi_id' => null]);
        }

        $validated = $request->validate([
            'periode_anggaran_id' => 'required|exists:periode_anggaran,id',
            'divisi_id' => 'nullable|exists:divisis,id',
            'kode_rekening' => 'nullable|string|max:50',
            'uraian' => 'required|string|max:500',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'jumlah_estimasi' => 'required|numeric|min:0',
            'perkiraan_bulanan' => 'nullable|array',
            'perkiraan_bulanan.*' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        // Get periode anggaran to validate months
        $periode = PeriodeAnggaran::find($validated['periode_anggaran_id']);
        if (!$periode) {
            return back()->withInput()->with('error', 'Periode anggaran tidak ditemukan.');
        }

        // Build perkiraan_bulanan array
        $perkiraanBulanan = [];
        if (!empty($validated['perkiraan_bulanan'])) {
            foreach ($validated['perkiraan_bulanan'] as $key => $value) {
                if ($value !== null && $value > 0) {
                    $perkiraanBulanan[$key] = (float) $value;
                }
            }
        }

        // Validate total
        $totalBulanan = array_sum($perkiraanBulanan);
        if ($totalBulanan > 0 && abs($totalBulanan - $validated['jumlah_estimasi']) > 1) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah per bulan harus sama dengan jumlah estimasi.');
        }

        DB::beginTransaction();
        try {
            PerencanaanPenerimaan::create([
                'periode_anggaran_id' => $validated['periode_anggaran_id'],
                'divisi_id' => $validated['divisi_id'] ?? Auth::user()->primaryDivision()?->id,
                'kode_rekening' => $validated['kode_rekening'] ?? null,
                'uraian' => $validated['uraian'],
                'sumber_dana_id' => $validated['sumber_dana_id'],
                'jumlah_estimasi' => $validated['jumlah_estimasi'],
                'perkiraan_bulanan' => $perkiraanBulanan,
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('perencanaan-penerimaan.index')
                ->with('success', 'Perencanaan penerimaan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create perencanaan penerimaan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat perencanaan penerimaan. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PerencanaanPenerimaan $perencanaanPenerimaan)
    {
        $perencanaanPenerimaan->load([
            'periodeAnggaran',
            'divisi',
            'sumberDana',
            'createdBy',
            'pencatatanPenerimaans',
        ]);

        return view('perencanaan-penerimaan.show', [
            'perencanaan' => $perencanaanPenerimaan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerencanaanPenerimaan $perencanaanPenerimaan)
    {
        $this->authorize('update', $perencanaanPenerimaan);

        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $sumberDanas = SumberDana::active()->orderBy('kode_sumber')->get();

        return view('perencanaan-penerimaan.edit', [
            'perencanaan' => $perencanaanPenerimaan,
            'periodeAnggarans' => $periodeAnggarans,
            'divisis' => $divisis,
            'sumberDanas' => $sumberDanas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerencanaanPenerimaan $perencanaanPenerimaan)
    {
        $this->authorize('update', $perencanaanPenerimaan);

        $validated = $request->validate([
            'kode_rekening' => 'nullable|string|max:50',
            'uraian' => 'required|string|max:500',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
            'jumlah_estimasi' => 'required|numeric|min:0',
            'perkiraan_bulanan' => 'nullable|array',
            'perkiraan_bulanan.*' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        // Build perkiraan_bulanan array
        $perkiraanBulanan = [];
        if (!empty($validated['perkiraan_bulanan'])) {
            foreach ($validated['perkiraan_bulanan'] as $key => $value) {
                if ($value !== null && $value > 0) {
                    $perkiraanBulanan[$key] = (float) $value;
                }
            }
        }

        // Validate total
        $totalBulanan = array_sum($perkiraanBulanan);
        if ($totalBulanan > 0 && abs($totalBulanan - $validated['jumlah_estimasi']) > 1) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah per bulan harus sama dengan jumlah estimasi.');
        }

        $perencanaanPenerimaan->update([
            'kode_rekening' => $validated['kode_rekening'] ?? null,
            'uraian' => $validated['uraian'],
            'sumber_dana_id' => $validated['sumber_dana_id'],
            'jumlah_estimasi' => $validated['jumlah_estimasi'],
            'perkiraan_bulanan' => $perkiraanBulanan,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('perencanaan-penerimaan.show', $perencanaanPenerimaan)
            ->with('success', 'Perencanaan penerimaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerencanaanPenerimaan $perencanaanPenerimaan)
    {
        $this->authorize('delete', $perencanaanPenerimaan);

        $perencanaanPenerimaan->delete();

        return redirect()
            ->route('perencanaan-penerimaan.index')
            ->with('success', 'Perencanaan penerimaan berhasil dihapus.');
    }

    /**
     * Get planning statistics.
     */
    public function statistics(Request $request)
    {
        $query = PerencanaanPenerimaan::query();

        // Filter by divisi - finance roles can see all data
        $user = Auth::user();
        if (!$user->hasAnyRole(['superadmin', 'direktur_keuangan', 'staff_keuangan', 'direktur_utama'])) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                $query->whereIn('divisi_id', $accessibleDivisionIds);
            } else {
                // If no job positions, return empty result
                return response()->json([
                    'total_estimasi' => 0,
                    'total_realisasi' => 0,
                    'sisa' => 0,
                    'persentase_realisasi' => 0,
                    'jumlah_perencanaan' => 0,
                ]);
            }
        }

        // Filter by periode if provided
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        $perencanaans = $query->get();

        $totalEstimasi = 0;
        $totalRealisasi = 0;

        foreach ($perencanaans as $perencanaan) {
            $totalEstimasi += $perencanaan->jumlah_estimasi;
            $totalRealisasi += $perencanaan->realisasi;
        }

        return response()->json([
            'total_estimasi' => $totalEstimasi,
            'total_realisasi' => $totalRealisasi,
            'sisa' => $totalEstimasi - $totalRealisasi,
            'persentase_realisasi' => $totalEstimasi > 0 ? ($totalRealisasi / $totalEstimasi) * 100 : 0,
            'jumlah_perencanaan' => $perencanaans->count(),
        ]);
    }
}
