<?php

namespace App\Http\Controllers;

use App\Models\ApprovalConfig;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApprovalConfigController extends Controller
{
    /**
     * Display a listing of approval configs.
     */
    public function index(Request $request): View
    {
        $jenisPengajuan = $request->get('jenis', 'pengajuan_dana');

        // Order by level priority: staff_keuangan -> kepala_divisi -> direktur_keuangan -> direktur_utama
        $configs = ApprovalConfig::jenisPengajuan($jenisPengajuan)
            ->orderByRaw("FIELD(level, 'staff_keuangan', 'kepala_divisi', 'direktur_keuangan', 'direktur_utama')")
            ->orderBy('minimal_nominal', 'desc')
            ->paginate(15);

        return view('admin.approval-configs.index', compact('configs', 'jenisPengajuan'));
    }

    /**
     * Show the form for creating a new approval config.
     */
    public function create(): View
    {
        $jenisPengajuanList = [
            'pengajuan_dana' => 'Pengajuan Dana',
            'lpj' => 'Laporan Pertanggungjawaban',
            'refund' => 'Refund',
            'pencairan_dana' => 'Pencairan Dana',
        ];

        $levelList = [
            'staff_keuangan' => 'Staff Keuangan',
            'kepala_divisi' => 'Kepala Divisi',
            'direktur_keuangan' => 'Direktur Keuangan',
            'direktur_utama' => 'Direktur Utama',
        ];

        return view('admin.approval-configs.create', compact('jenisPengajuanList', 'levelList'));
    }

    /**
     * Store a newly created approval config.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_pengajuan' => 'required|in:pengajuan_dana,lpj,refund,pencairan_dana',
            'minimal_nominal' => 'required|numeric|min:0',
            'level' => 'required|in:staff_keuangan,kepala_divisi,direktur_keuangan,direktur_utama',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        ApprovalConfig::create($validated);

        return redirect()
            ->route('admin.approval-configs.index', ['jenis' => $validated['jenis_pengajuan']])
            ->with('success', 'Konfigurasi approval berhasil dibuat.');
    }

    /**
     * Display the specified approval config.
     */
    public function show(ApprovalConfig $approvalConfig): View
    {
        return view('admin.approval-configs.show', compact('approvalConfig'));
    }

    /**
     * Show the form for editing the specified approval config.
     */
    public function edit(ApprovalConfig $approvalConfig): View
    {
        $jenisPengajuanList = [
            'pengajuan_dana' => 'Pengajuan Dana',
            'lpj' => 'Laporan Pertanggungjawaban',
            'refund' => 'Refund',
            'pencairan_dana' => 'Pencairan Dana',
        ];

        $levelList = [
            'staff_keuangan' => 'Staff Keuangan',
            'kepala_divisi' => 'Kepala Divisi',
            'direktur_keuangan' => 'Direktur Keuangan',
            'direktur_utama' => 'Direktur Utama',
        ];

        return view('admin.approval-configs.edit', compact('approvalConfig', 'jenisPengajuanList', 'levelList'));
    }

    /**
     * Update the specified approval config.
     */
    public function update(Request $request, ApprovalConfig $approvalConfig): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_pengajuan' => 'required|in:pengajuan_dana,lpj,refund,pencairan_dana',
            'minimal_nominal' => 'required|numeric|min:0',
            'level' => 'required|in:staff_keuangan,kepala_divisi,direktur_keuangan,direktur_utama',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $approvalConfig->update($validated);

        return redirect()
            ->route('admin.approval-configs.index', ['jenis' => $validated['jenis_pengajuan']])
            ->with('success', 'Konfigurasi approval berhasil diperbarui.');
    }

    /**
     * Remove the specified approval config.
     */
    public function destroy(ApprovalConfig $approvalConfig): RedirectResponse
    {
        $jenisPengajuan = $approvalConfig->jenis_pengajuan;
        $approvalConfig->delete();

        return redirect()
            ->route('admin.approval-configs.index', ['jenis' => $jenisPengajuan])
            ->with('success', 'Konfigurasi approval berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ApprovalConfig $approvalConfig): RedirectResponse
    {
        $approvalConfig->update([
            'is_active' => !$approvalConfig->is_active,
        ]);

        return back()->with('success', 'Status konfigurasi approval berhasil diperbarui.');
    }
}
