<?php

namespace App\Http\Controllers;

use App\Models\DetailAnggaran;
use App\Models\Divisi;
use App\Models\ProgramKerja;
use App\Models\SubProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailAnggaranController extends Controller
{
    /**
     * Store a newly created detail anggaran.
     */
    public function store(Request $request, Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram)
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
            'nama_detail' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'frekuensi' => 'required|in:bulanan,triwulan,semesteran,tahunan,sekali',
            'jumlah_periode' => 'required|integer|min:1',
            'nominal_per_periode' => 'required|string',
            'satuan' => 'nullable|string|max:50',
            'tanggal_mulai_custom' => 'nullable|date',
        ]);

        // Clean nominal_per_periode - remove thousand separator
        $validated['nominal_per_periode'] = (float) str_replace('.', '', $validated['nominal_per_periode']);

        // Calculate total nominal
        $totalNominal = $validated['jumlah_periode'] * $validated['nominal_per_periode'];

        // Check if total detail anggaran will exceed sub program pagu
        $totalExistingDetail = DetailAnggaran::where('sub_program_id', $subProgram->id)
            ->sum('total_nominal');

        if (($totalExistingDetail + $totalNominal) > $subProgram->pagu_anggaran) {
            return back()
                ->withInput()
                ->with('error', sprintf(
                    'Total nominal melebihi pagu sub program. Sisa pagu tersedia: %s',
                    number_format($subProgram->pagu_anggaran - $totalExistingDetail, 0, ',', '.')
                ));
        }

        $detailAnggaran = DetailAnggaran::create([
            'sub_program_id' => $subProgram->id,
            'nama_detail' => $validated['nama_detail'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'frekuensi' => $validated['frekuensi'],
            'jumlah_periode' => $validated['jumlah_periode'],
            'nominal_per_periode' => $validated['nominal_per_periode'],
            'total_nominal' => $totalNominal,
            'satuan' => $validated['satuan'] ?? null,
            'tanggal_mulai_custom' => $validated['tanggal_mulai_custom'] ?? null,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        // Generate estimasi pengeluarans
        $detailAnggaran->generateEstimasiPengeluarans();

        return back()
            ->with('success', 'Detail anggaran berhasil dibuat.');
    }

    /**
     * Update the specified detail anggaran.
     */
    public function update(Request $request, Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram, DetailAnggaran $detailAnggaran)
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

        // Verify detail anggaran belongs to this sub program
        if ($detailAnggaran->sub_program_id !== $subProgram->id) {
            abort(404, 'Detail anggaran tidak ditemukan di sub program ini.');
        }

        $validated = $request->validate([
            'nama_detail' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'frekuensi' => 'required|in:bulanan,triwulan,semesteran,tahunan,sekali',
            'jumlah_periode' => 'required|integer|min:1',
            'nominal_per_periode' => 'required|string',
            'satuan' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive,suspended',
            'tanggal_mulai_custom' => 'nullable|date',
        ]);

        // Clean nominal_per_periode - remove thousand separator
        $validated['nominal_per_periode'] = (float) str_replace('.', '', $validated['nominal_per_periode']);

        // Calculate total nominal
        $totalNominal = $validated['jumlah_periode'] * $validated['nominal_per_periode'];

        // Check if updated total will exceed sub program pagu
        $totalExistingDetail = DetailAnggaran::where('sub_program_id', $subProgram->id)
            ->where('id', '!=', $detailAnggaran->id)
            ->sum('total_nominal');

        if (($totalExistingDetail + $totalNominal) > $subProgram->pagu_anggaran) {
            return back()
                ->withInput()
                ->with('error', sprintf(
                    'Total nominal melebihi pagu sub program. Sisa pagu tersedia: %s',
                    number_format($subProgram->pagu_anggaran - $totalExistingDetail, 0, ',', '.')
                ));
        }

        $detailAnggaran->update([
            'nama_detail' => $validated['nama_detail'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'frekuensi' => $validated['frekuensi'],
            'jumlah_periode' => $validated['jumlah_periode'],
            'nominal_per_periode' => $validated['nominal_per_periode'],
            'total_nominal' => $totalNominal,
            'satuan' => $validated['satuan'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'tanggal_mulai_custom' => $validated['tanggal_mulai_custom'] ?? null,
        ]);

        // Regenerate estimasi pengeluarans
        $detailAnggaran->generateEstimasiPengeluarans();

        return back()
            ->with('success', 'Detail anggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified detail anggaran.
     */
    public function destroy(Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram, DetailAnggaran $detailAnggaran)
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

        // Verify detail anggaran belongs to this sub program
        if ($detailAnggaran->sub_program_id !== $subProgram->id) {
            abort(404, 'Detail anggaran tidak ditemukan di sub program ini.');
        }

        // Check if detail has realisasi
        if ($detailAnggaran->estimasiPengeluarans()->where('nominal_realisasi', '>', 0)->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus detail anggaran yang sudah memiliki realisasi.');
        }

        $detailAnggaran->delete();

        return back()
            ->with('success', 'Detail anggaran berhasil dihapus.');
    }
}
