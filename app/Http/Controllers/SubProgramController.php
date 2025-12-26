<?php

namespace App\Http\Controllers;

use App\Models\SubProgram;
use App\Models\Divisi;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubProgramController extends Controller
{
    /**
     * Store a newly created sub program.
     */
    public function store(Request $request, Divisi $divisi, ProgramKerja $programKerja)
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

        $validated = $request->validate([
            'kode_sub_program' => 'required|string|max:50|unique:sub_programs,kode_sub_program',
            'nama_sub_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|string',
            'target_output' => 'nullable|string|max:255',
        ], [
            'kode_sub_program.unique' => 'Kode sub program sudah digunakan.',
        ]);

        // Clean pagu_anggaran - remove thousand separator
        $validated['pagu_anggaran'] = (float) str_replace('.', '', $validated['pagu_anggaran']);

        // Check if total sub program pagu will exceed program pagu
        $totalExistingPagu = SubProgram::where('program_kerja_id', $programKerja->id)
            ->sum('pagu_anggaran');

        if (($totalExistingPagu + $validated['pagu_anggaran']) > $programKerja->pagu_anggaran) {
            return back()
                ->withInput()
                ->with('error', sprintf(
                    'Pagu anggaran melebihi pagu program kerja. Sisa pagu tersedia: %s',
                    number_format($programKerja->pagu_anggaran - $totalExistingPagu, 0, ',', '.')
                ));
        }

        SubProgram::create([
            'program_kerja_id' => $programKerja->id,
            'kode_sub_program' => $validated['kode_sub_program'],
            'nama_sub_program' => $validated['nama_sub_program'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'periode_anggaran_id' => $programKerja->periode_anggaran_id,
            'pagu_anggaran' => $validated['pagu_anggaran'],
            'target_output' => $validated['target_output'] ?? null,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        return back()
            ->with('success', 'Sub program berhasil dibuat.');
    }

    /**
     * Update the specified sub program.
     */
    public function update(Request $request, Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram)
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

        // Verify sub program belongs to this program
        if ($subProgram->program_kerja_id !== $programKerja->id) {
            abort(404, 'Sub program tidak ditemukan di program kerja ini.');
        }

        $validated = $request->validate([
            'kode_sub_program' => 'required|string|max:50|unique:sub_programs,kode_sub_program,' . $subProgram->id,
            'nama_sub_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|string',
            'target_output' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,suspended',
        ], [
            'kode_sub_program.unique' => 'Kode sub program sudah digunakan.',
        ]);

        // Clean pagu_anggaran - remove thousand separator
        $validated['pagu_anggaran'] = (float) str_replace('.', '', $validated['pagu_anggaran']);

        // Check if updated pagu will exceed program pagu
        $totalExistingPagu = SubProgram::where('program_kerja_id', $programKerja->id)
            ->where('id', '!=', $subProgram->id)
            ->sum('pagu_anggaran');

        if (($totalExistingPagu + $validated['pagu_anggaran']) > $programKerja->pagu_anggaran) {
            return back()
                ->withInput()
                ->with('error', sprintf(
                    'Pagu anggaran melebihi pagu program kerja. Sisa pagu tersedia: %s',
                    number_format($programKerja->pagu_anggaran - $totalExistingPagu, 0, ',', '.')
                ));
        }

        $subProgram->update([
            'kode_sub_program' => $validated['kode_sub_program'],
            'nama_sub_program' => $validated['nama_sub_program'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'pagu_anggaran' => $validated['pagu_anggaran'],
            'target_output' => $validated['target_output'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        return back()
            ->with('success', 'Sub program berhasil diperbarui.');
    }

    /**
     * Remove the specified sub program.
     */
    public function destroy(Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram)
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

        // Verify sub program belongs to this program
        if ($subProgram->program_kerja_id !== $programKerja->id) {
            abort(404, 'Sub program tidak ditemukan di program kerja ini.');
        }

        // Check if sub program has related data
        if ($subProgram->detailAnggarans()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus sub program yang memiliki detail anggaran.');
        }

        $subProgram->delete();

        return back()
            ->with('success', 'Sub program berhasil dihapus.');
    }
}
