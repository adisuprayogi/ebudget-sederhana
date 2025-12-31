<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use App\Models\SubProgram;
use App\Models\Divisi;
use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaController extends Controller
{
    /**
     * Display list of divisi for the current user based on active periode anggaran.
     */
    public function index()
    {
        $user = Auth::user();

        // Get active periode anggaran (prioritize perencanaan phase)
        $activePeriodes = PeriodeAnggaran::where('status', 'active')->get();

        // First try to find periode in perencanaan phase
        $activePeriode = $activePeriodes->first(function ($periode) {
            return $periode->fase === 'perencangan';
        });

        // If no periode in perencanaan phase, try to get periode in penggunaan phase
        if (!$activePeriode) {
            $activePeriode = $activePeriodes->first(function ($periode) {
                return $periode->fase === 'penggunaan';
            });
        }

        if (!$activePeriode) {
            return view('program-kerja.index', [
                'activePeriode' => null,
                'penetapanPagus' => collect(),
            ]);
        }

        // Get penetapan pagu for active periode
        $query = \App\Models\PenetapanPagu::with(['divisi' => function ($query) {
                $query->withCount(['programKerjas', 'subPrograms']);
            }])
            ->where('periode_anggaran_id', $activePeriode->id)
            ->with(['divisi']);

        // Filter by user's accessible divisions if not superadmin/direktur_utama
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!empty($accessibleDivisionIds)) {
                $query->whereHas('divisi', function ($q) use ($accessibleDivisionIds) {
                    $q->whereIn('divisis.id', $accessibleDivisionIds);
                });
            } else {
                $query->whereRaw('1 = 0'); // No access, return empty
            }
        }

        $penetapanPagus = $query->get()->sortBy('divisi.nama_divisi');

        return view('program-kerja.index', [
            'activePeriode' => $activePeriode,
            'penetapanPagus' => $penetapanPagus,
        ]);
    }

    /**
     * Show program kerja for specific divisi with active periode anggaran.
     */
    public function divisiShow(Request $request, Divisi $divisi)
    {
        $user = Auth::user();

        // Check if user has access to this divisi
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Get active periode anggaran (prioritize perencanaan phase)
        $activePeriodes = PeriodeAnggaran::where('status', 'active')->get();

        // First try to find periode in perencanaan phase
        $activePeriode = $activePeriodes->first(function ($periode) {
            return $periode->fase === 'perencangan';
        });

        // If no periode in perencanaan phase, try to get periode in penggunaan phase
        if (!$activePeriode) {
            $activePeriode = $activePeriodes->first(function ($periode) {
                return $periode->fase === 'penggunaan';
            });
        }

        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode anggaran yang aktif saat ini.');
        }

        // Get program kerja for this divisi and active periode
        $query = ProgramKerja::with(['subPrograms', 'detailAnggarans', 'periodeAnggaran'])
            ->where('divisi_id', $divisi->id)
            ->where('periode_anggaran_id', $activePeriode->id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_program', 'like', "%{$search}%")
                  ->orWhere('kode_program', 'like', "%{$search}%");
        }

        $programKerjas = $query->orderBy('kode_program', 'asc')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        // Calculate statistics - pagu is calculated from detail anggaran
        $totalPagu = 0;
        foreach ($programKerjas as $program) {
            $totalPagu += $program->calculated_pagu ?? 0;
        }
        $totalProgram = $programKerjas->count();
        $totalSubProgram = SubProgram::whereIn('program_kerja_id', $programKerjas->pluck('id'))->count();

        return view('program-kerja.divisi-show', [
            'divisi' => $divisi,
            'activePeriode' => $activePeriode,
            'programKerjas' => $programKerjas,
            'filters' => $request->only(['status', 'search']),
            'statistics' => [
                'total_pagu' => $totalPagu,
                'total_program' => $totalProgram,
                'total_sub_program' => $totalSubProgram,
            ],
        ]);
    }

    /**
     * Show the form for creating a new program kerja.
     */
    public function create(Request $request, Divisi $divisi)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Get active periode anggaran in perencanaan phase
        $activePeriodes = PeriodeAnggaran::where('status', 'active')->get();

        // First try to find periode in perencanaan phase using the fase accessor
        $activePeriode = $activePeriodes->first(function ($periode) {
            return $periode->fase === 'perencangan';
        });

        // If not found in perencanaan phase, get the most recent active periode
        if (!$activePeriode) {
            $activePeriode = $activePeriodes->sortByDesc('tahun_anggaran')->first();
        }

        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode anggaran yang aktif saat ini.');
        }

        return view('program-kerja.create', [
            'divisi' => $divisi,
            'activePeriode' => $activePeriode,
        ]);
    }

    /**
     * Store a newly created program kerja.
     */
    public function store(Request $request, Divisi $divisi)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Get active periode anggaran in perencanaan phase
        $activePeriodes = PeriodeAnggaran::where('status', 'active')->get();

        // First try to find periode in perencanaan phase using the fase accessor
        $activePeriode = $activePeriodes->first(function ($periode) {
            return $periode->fase === 'perencangan';
        });

        if (!$activePeriode) {
            return back()->with('error', 'Tidak dapat menambah program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        // Get penetapan pagu for this divisi and active periode
        $penetapanPagu = \App\Models\PenetapanPagu::where('divisi_id', $divisi->id)
            ->where('periode_anggaran_id', $activePeriode->id)
            ->first();

        if (!$penetapanPagu) {
            return back()->with('error', 'Belum ada penetapan pagu untuk divisi ini pada periode anggaran aktif.');
        }

        $validated = $request->validate([
            'kode_program' => 'required|string|max:50|unique:program_kerjas,kode_program',
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_output' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], [
            'kode_program.unique' => 'Kode program sudah digunakan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        // pagu_anggaran is now calculated from detail anggaran, set to 0 initially
        ProgramKerja::create([
            'kode_program' => $validated['kode_program'],
            'nama_program' => $validated['nama_program'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'divisi_id' => $divisi->id,
            'periode_anggaran_id' => $activePeriode->id,
            'pagu_anggaran' => 0, // Will be calculated from detail anggaran
            'target_output' => $validated['target_output'] ?? null,
            'status' => 'active',
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? $activePeriode->tanggal_mulai_perencanaan_anggaran,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $activePeriode->tanggal_selesai_perencanaan_anggaran,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('program-kerja.divisi-show', $divisi)
            ->with('success', 'Program kerja berhasil dibuat. Silakan tambahkan Sub Program dan Detail Anggaran.');
    }

    /**
     * Display the specified program kerja.
     */
    public function show(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        $programKerja->load([
            'divisi',
            'periodeAnggaran',
            'subPrograms.detailAnggarans',
            'detailAnggarans.estimasiPengeluarans',
            'createdBy',
        ]);

        // Calculate statistics - pagu is now calculated from detail anggaran
        $calculatedPagu = $programKerja->calculated_pagu ?? 0;
        $totalPaguSubProgram = $programKerja->subPrograms->sum('pagu_anggaran');
        $totalDetailAnggaran = $programKerja->detailAnggarans->sum('total_nominal');
        $persentaseTerpakai = $programKerja->persentase_terpakai;

        return view('program-kerja.show', [
            'programKerja' => $programKerja,
            'divisi' => $divisi,
            'statistics' => [
                'total_pagu' => $calculatedPagu,
                'total_pagu_sub_program' => $totalPaguSubProgram,
                'total_detail_anggaran' => $totalDetailAnggaran,
                'sisa_pagu' => $programKerja->sisa_pagu,
                'persentase_terpakai' => round($persentaseTerpakai, 1),
                'jumlah_sub_program' => $programKerja->subPrograms->count(),
                'jumlah_detail_anggaran' => $programKerja->detailAnggarans->count(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified program kerja.
     */
    public function edit(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        // Validate: Only allow edit when periode is in perencanaan phase
        $periode = $programKerja->periodeAnggaran;
        if ($periode->fase !== 'perencangan') {
            return back()->with('error', 'Tidak dapat mengedit program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        $programKerja->load('divisi', 'periodeAnggaran');

        return view('program-kerja.edit', [
            'divisi' => $divisi,
            'programKerja' => $programKerja,
        ]);
    }

    /**
     * Update the specified program kerja.
     */
    public function update(Request $request, Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        // Validate: Only allow update when periode is in perencanaan phase
        $periode = $programKerja->periodeAnggaran;
        if ($periode->fase !== 'perencangan') {
            return back()->withInput()->with('error', 'Tidak dapat mengubah program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        $validated = $request->validate([
            'kode_program' => 'required|string|max:50|unique:program_kerjas,kode_program,' . $programKerja->id,
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_output' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], [
            'kode_program.unique' => 'Kode program sudah digunakan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        // pagu_anggaran is calculated from detail anggaran, don't update it here
        $programKerja->update([
            'kode_program' => $validated['kode_program'],
            'nama_program' => $validated['nama_program'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'target_output' => $validated['target_output'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        return redirect()
            ->route('program-kerja.show', [$divisi, $programKerja])
            ->with('success', 'Program kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified program kerja.
     */
    public function destroy(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        // Validate: Only allow deletion when periode is in perencanaan phase
        $periode = $programKerja->periodeAnggaran;
        if ($periode->fase !== 'perencangan') {
            return back()->with('error', 'Tidak dapat menghapus program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        // Check if program has related data
        if ($programKerja->subPrograms()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus program kerja yang memiliki sub program.');
        }

        if ($programKerja->detailAnggarans()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus program kerja yang memiliki detail anggaran.');
        }

        $programKerja->delete();

        return redirect()
            ->route('program-kerja.divisi-show', $divisi)
            ->with('success', 'Program kerja berhasil dihapus.');
    }

    /**
     * Display sub programs for the specified program kerja.
     */
    public function subPrograms(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        $subPrograms = $programKerja->subPrograms()->with('detailAnggarans')->get();

        return response()->json([
            'program_kerja' => $programKerja,
            'sub_programs' => $subPrograms,
        ]);
    }

    /**
     * Activate the specified program kerja.
     */
    public function activate(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        // Validate: Only allow status change when periode is in perencanaan phase
        $periode = $programKerja->periodeAnggaran;
        if ($periode->fase !== 'perencangan') {
            return back()->with('error', 'Tidak dapat mengubah status program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        $programKerja->update(['status' => 'active']);

        return back()->with('success', 'Program kerja berhasil diaktifkan.');
    }

    /**
     * Suspend the specified program kerja.
     */
    public function suspend(Divisi $divisi, ProgramKerja $programKerja)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify program belongs to this divisi
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }

        // Validate: Only allow status change when periode is in perencanaan phase
        $periode = $programKerja->periodeAnggaran;
        if ($periode->fase !== 'perencangan') {
            return back()->with('error', 'Tidak dapat mengubah status program kerja. Periode anggaran harus dalam fase Perencanaan.');
        }

        $programKerja->update(['status' => 'suspended']);

        return back()->with('success', 'Program kerja berhasil ditangguhkan.');
    }

    /**
     * Get statistics for the divisi's programs.
     */
    public function statistics(Request $request, Divisi $divisi)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Get active periode anggaran in perencanaan phase
        $activePeriodes = PeriodeAnggaran::where('status', 'active')->get();

        // First try to find periode in perencanaan phase using the fase accessor
        $activePeriode = $activePeriodes->first(function ($periode) {
            return $periode->fase === 'perencangan';
        });

        // If not found in perencanaan phase, get the most recent active periode
        if (!$activePeriode) {
            $activePeriode = $activePeriodes->sortByDesc('tahun_anggaran')->first();
        }

        if (!$activePeriode) {
            return response()->json([
                'error' => 'Tidak ada periode anggaran aktif',
            ], 404);
        }

        // Get penetration pagu
        $penetapanPagu = \App\Models\PenetapanPagu::where('divisi_id', $divisi->id)
            ->where('periode_anggaran_id', $activePeriode->id)
            ->first();

        // Get statistics
        $programKerjas = ProgramKerja::where('divisi_id', $divisi->id)
            ->where('periode_anggaran_id', $activePeriode->id)
            ->with(['subPrograms', 'detailAnggarans'])
            ->get();

        // Calculate total pagu from detail anggaran
        $totalPagu = 0;
        foreach ($programKerjas as $program) {
            $totalPagu += $program->calculated_pagu ?? 0;
        }
        $totalProgram = $programKerjas->count();
        $totalSubProgram = SubProgram::whereIn('program_kerja_id', $programKerjas->pluck('id'))->count();

        return response()->json([
            'penetapan_pagu' => $penetapanPagu ? $penetapanPagu->jumlah_pagu : 0,
            'total_pagu_program' => $totalPagu,
            'sisa_pagu' => $penetapanPagu ? ($penetapanPagu->jumlah_pagu - $totalPagu) : 0,
            'total_program' => $totalProgram,
            'total_sub_program' => $totalSubProgram,
            'programs_by_status' => [
                'active' => $programKerjas->where('status', 'active')->count(),
                'inactive' => $programKerjas->where('status', 'inactive')->count(),
                'suspended' => $programKerjas->where('status', 'suspended')->count(),
            ],
        ]);
    }
}
