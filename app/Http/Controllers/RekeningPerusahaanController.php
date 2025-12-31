<?php

namespace App\Http\Controllers;

use App\Models\RekeningPerusahaan;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningPerusahaanController extends Controller
{
    /**
     * Check if user has access to rekening perusahaan management.
     */
    private function hasAccess()
    {
        $user = Auth::user();
        return $user->hasRole('superadmin') || $user->hasRole('direktur_keuangan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $rekenings = RekeningPerusahaan::with('bank')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('rekening-perusahaan.index', [
            'rekenings' => $rekenings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $banks = Bank::active()->orderBy('nama_bank')->get();

        return view('rekening-perusahaan.create', [
            'banks' => $banks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_perusahaans,nomor_rekening',
            'atas_nama' => 'required|string|max:100',
            'cabang' => 'nullable|string|max:100',
            'mata_uang' => 'nullable|string|max:10',
            'saldo_awal' => 'nullable|numeric|min:0',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'catatan' => 'nullable|string|max:500',
        ], [
            'bank_id.required' => 'Bank wajib dipilih',
            'bank_id.exists' => 'Bank tidak valid',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi',
            'nomor_rekening.unique' => 'Nomor rekening sudah terdaftar',
            'atas_nama.required' => 'Atas nama wajib diisi',
        ]);

        $validated['created_by'] = Auth::user()->name;
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        RekeningPerusahaan::create($validated);

        return redirect()
            ->route('rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(RekeningPerusahaan $rekeningPerusahaan)
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $rekeningPerusahaan->load('bank');

        return view('rekening-perusahaan.show', [
            'rekening' => $rekeningPerusahaan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekeningPerusahaan $rekeningPerusahaan)
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $banks = Bank::active()->orderBy('nama_bank')->get();
        $rekeningPerusahaan->load('bank');

        return view('rekening-perusahaan.edit', [
            'rekening' => $rekeningPerusahaan,
            'banks' => $banks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningPerusahaan $rekeningPerusahaan)
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_perusahaans,nomor_rekening,' . $rekeningPerusahaan->id,
            'atas_nama' => 'required|string|max:100',
            'cabang' => 'nullable|string|max:100',
            'mata_uang' => 'nullable|string|max:10',
            'saldo_awal' => 'nullable|numeric|min:0',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'catatan' => 'nullable|string|max:500',
        ]);

        $validated['updated_by'] = Auth::user()->name;
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        $rekeningPerusahaan->update($validated);

        return redirect()
            ->route('rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekeningPerusahaan $rekeningPerusahaan)
    {
        if (!$this->hasAccess()) {
            abort(403);
        }

        $rekeningPerusahaan->delete();

        return redirect()
            ->route('rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil dihapus');
    }

    /**
     * Get active rekenings for dropdown.
     */
    public function getActiveRekenings()
    {
        return response()->json([
            'rekenings' => RekeningPerusahaan::with('bank')
                ->active()
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'info' => $item->full_info,
                        'nomor_rekening' => $item->nomor_rekening,
                        'nama_bank' => $item->bank->nama_bank,
                        'atas_nama' => $item->atas_nama,
                    ];
                })
        ]);
    }
}
