<?php

namespace App\Http\Controllers;

use App\Models\EstimasiPengeluaran;
use App\Models\Divisi;
use App\Models\ProgramKerja;
use App\Models\SubProgram;
use App\Models\DetailAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstimasiPengeluaranController extends Controller
{
    /**
     * Update the specified estimasi pengeluaran.
     */
    public function update(Request $request, Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram, DetailAnggaran $detailAnggaran, EstimasiPengeluaran $estimasi)
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

        // Verify estimasi belongs to this detail anggaran
        if ($estimasi->detail_anggaran_id !== $detailAnggaran->id) {
            abort(404, 'Estimasi pengeluaran tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal_rencana_realisasi' => 'required|date',
            'nominal_rencana' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Clean nominal_rencana - remove thousand separator
        $validated['nominal_rencana'] = (float) str_replace('.', '', $validated['nominal_rencana']);

        $estimasi->update([
            'tanggal_rencana_realisasi' => $validated['tanggal_rencana_realisasi'],
            'nominal_rencana' => $validated['nominal_rencana'],
            'catatan' => $validated['catatan'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        return back()
            ->with('success', 'Estimasi pengeluaran berhasil diperbarui.');
    }

    /**
     * Bulk update status for multiple estimasi.
     */
    public function bulkUpdateStatus(Request $request, Divisi $divisi, ProgramKerja $programKerja, SubProgram $subProgram, DetailAnggaran $detailAnggaran)
    {
        $user = Auth::user();

        // Check access
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama')) {
            $accessibleDivisionIds = $user->divisionIds();
            if (!in_array($divisi->id, $accessibleDivisionIds)) {
                abort(403, 'Anda tidak memiliki akses ke divisi ini.');
            }
        }

        // Verify relationships
        if ($programKerja->divisi_id !== $divisi->id) {
            abort(404, 'Program kerja tidak ditemukan di divisi ini.');
        }
        if ($subProgram->program_kerja_id !== $programKerja->id) {
            abort(404, 'Sub program tidak ditemukan di program kerja ini.');
        }
        if ($detailAnggaran->sub_program_id !== $subProgram->id) {
            abort(404, 'Detail anggaran tidak ditemukan di sub program ini.');
        }

        $validated = $request->validate([
            'estimasi_ids' => 'required|array',
            'estimasi_ids.*' => 'integer',
            'status' => 'required|in:pending,selesai,batal',
        ]);

        EstimasiPengeluaran::where('detail_anggaran_id', $detailAnggaran->id)
            ->whereIn('id', $validated['estimasi_ids'])
            ->update([
                'status' => $validated['status'],
                'updated_by' => Auth::id(),
            ]);

        return back()
            ->with('success', 'Status estimasi pengeluaran berhasil diperbarui.');
    }
}
