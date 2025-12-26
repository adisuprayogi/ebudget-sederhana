<?php

namespace App\Http\Controllers;

use App\Models\PenetapanPagu;
use App\Models\Divisi;
use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenetapanPaguController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenetapanPagu::with(['divisi', 'periodeAnggaran', 'createdBy']);

        // Filter by divisi - admin can see all, others only their assigned divisions
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_keuangan') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                $query->whereIn('divisi_id', $accessibleDivisionIds);
            } else {
                // If no job positions, show empty result
                $query->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('divisi', function ($q) use ($search) {
                $q->where('nama_divisi', 'like', "%{$search}%");
            });
        }

        $penetapanPagus = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();

        // Calculate total statistics
        $totalPagu = 0;
        $totalTerpakai = 0;
        foreach ($penetapanPagus as $pagu) {
            $totalPagu += $pagu->jumlah_pagu;
            $totalTerpakai += $pagu->used_amount;
        }

        return view('penetapan-pagu.index', [
            'penetapanPagus' => $penetapanPagus,
            'filters' => $request->only(['divisi_id', 'periode_anggaran_id', 'search']),
            'filterOptions' => [
                'divisis' => $divisis,
                'periodeAnggarans' => $periodeAnggarans,
            ],
            'summary' => [
                'total_pagu' => $totalPagu,
                'total_terpakai' => $totalTerpakai,
                'sisa_pagu' => $totalPagu - $totalTerpakai,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $periodeAnggarans = PeriodeAnggaran::where('status', 'active')
            ->orderBy('tahun_anggaran', 'desc')
            ->get();

        return view('penetapan-pagu.create', [
            'divisis' => $divisis,
            'periodeAnggarans' => $periodeAnggarans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'divisi_id' => 'required|exists:divisis,id',
            'periode_anggaran_id' => 'required|exists:periode_anggaran,id',
            'jumlah_pagu' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        // Check if pagu already exists for this divisi and periode
        $existing = PenetapanPagu::where('divisi_id', $validated['divisi_id'])
            ->where('periode_anggaran_id', $validated['periode_anggaran_id'])
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Pagu untuk divisi dan periode anggaran ini sudah ada.');
        }

        DB::beginTransaction();
        try {
            PenetapanPagu::create([
                'divisi_id' => $validated['divisi_id'],
                'periode_anggaran_id' => $validated['periode_anggaran_id'],
                'jumlah_pagu' => $validated['jumlah_pagu'],
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('penetapan-pagu.index')
                ->with('success', 'Penetapan pagu berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create penetapan pagu: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penetapan pagu. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PenetapanPagu $penetapanPagu)
    {
        $penetapanPagu->load([
            'divisi',
            'periodeAnggaran',
            'createdBy',
        ]);

        return view('penetapan-pagu.show', [
            'penetapanPagu' => $penetapanPagu,
            'statistics' => [
                'total_pagu' => $penetapanPagu->jumlah_pagu,
                'total_terpakai' => $penetapanPagu->used_amount,
                'sisa_pagu' => $penetapanPagu->remaining_amount,
                'persentase_terpakai' => $penetapanPagu->usage_percentage,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenetapanPagu $penetapanPagu)
    {
        $this->authorize('update', $penetapanPagu);

        $divisis = Divisi::orderBy('nama_divisi')->get();
        $periodeAnggarans = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get();

        return view('penetapan-pagu.edit', [
            'penetapanPagu' => $penetapanPagu,
            'divisis' => $divisis,
            'periodeAnggarans' => $periodeAnggarans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenetapanPagu $penetapanPagu)
    {
        $this->authorize('update', $penetapanPagu);

        $validated = $request->validate([
            'jumlah_pagu' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $penetapanPagu->update([
            'jumlah_pagu' => $validated['jumlah_pagu'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('penetapan-pagu.show', $penetapanPagu)
            ->with('success', 'Penetapan pagu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenetapanPagu $penetapanPagu)
    {
        $this->authorize('delete', $penetapanPagu);

        $penetapanPagu->delete();

        return redirect()
            ->route('penetapan-pagu.index')
            ->with('success', 'Penetapan pagu berhasil dihapus.');
    }

    /**
     * Get pagu statistics.
     */
    public function statistics(Request $request)
    {
        $query = PenetapanPagu::with('divisi');

        // Filter by divisi - admin can see all, others only their assigned divisions
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_keuangan') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                $query->whereIn('divisi_id', $accessibleDivisionIds);
            } else {
                // If no job positions, return empty result
                return response()->json([
                    'total_pagu' => 0,
                    'total_terpakai' => 0,
                    'sisa_pagu' => 0,
                    'persentase_terpakai' => 0,
                    'jumlah_divisi' => 0,
                ]);
            }
        }

        // Filter by periode if provided
        if ($request->filled('periode_anggaran_id')) {
            $query->where('periode_anggaran_id', $request->periode_anggaran_id);
        }

        $penetapanPagus = $query->get();

        $totalPagu = 0;
        $totalTerpakai = 0;

        foreach ($penetapanPagus as $pagu) {
            $totalPagu += $pagu->jumlah_pagu;
            $totalTerpakai += $pagu->used_amount;
        }

        return response()->json([
            'total_pagu' => $totalPagu,
            'total_terpakai' => $totalTerpakai,
            'sisa_pagu' => $totalPagu - $totalTerpakai,
            'persentase_terpakai' => $totalPagu > 0 ? ($totalTerpakai / $totalPagu) * 100 : 0,
            'jumlah_divisi' => $penetapanPagus->count(),
        ]);
    }
}
