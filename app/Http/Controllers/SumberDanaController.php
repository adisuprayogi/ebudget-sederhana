<?php

namespace App\Http\Controllers;

use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SumberDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SumberDana::with(['createdBy']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search by kode or nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_sumber', 'like', "%{$search}%")
                  ->orWhere('nama_sumber', 'like', "%{$search}%");
        }

        $sumberDanas = $query->orderBy('kode_sumber')->paginate($request->per_page ?? 15)->withQueryString();

        return view('sumber-dana.index', [
            'sumberDanas' => $sumberDanas,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sumber-dana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_sumber' => 'required|string|max:50|unique:sumber_danas,kode_sumber',
            'nama_sumber' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        SumberDana::create([
            'kode_sumber' => $validated['kode_sumber'],
            'nama_sumber' => $validated['nama_sumber'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('sumber-dana.index')
            ->with('success', 'Sumber Dana berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SumberDana $sumberDana)
    {
        $sumberDana->load(['createdBy', 'perencanaanPenerimaans', 'pencatatanPenerimaans']);

        return view('sumber-dana.show', [
            'sumberDana' => $sumberDana,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SumberDana $sumberDana)
    {
        return view('sumber-dana.edit', [
            'sumberDana' => $sumberDana,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SumberDana $sumberDana)
    {
        $validated = $request->validate([
            'kode_sumber' => 'required|string|max:50|unique:sumber_danas,kode_sumber,' . $sumberDana->id,
            'nama_sumber' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $sumberDana->update([
            'kode_sumber' => $validated['kode_sumber'],
            'nama_sumber' => $validated['nama_sumber'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('sumber-dana.show', $sumberDana)
            ->with('success', 'Sumber Dana berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SumberDana $sumberDana)
    {
        // Check if sumber dana is being used
        if ($sumberDana->perencanaanPenerimaans()->count() > 0 || $sumberDana->pencatatanPenerimaans()->count() > 0) {
            return back()
                ->with('error', 'Sumber Dana tidak dapat dihapus karena masih digunakan.');
        }

        $sumberDana->delete();

        return redirect()
            ->route('sumber-dana.index')
            ->with('success', 'Sumber Dana berhasil dihapus.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(SumberDana $sumberDana)
    {
        $sumberDana->update([
            'is_active' => !$sumberDana->is_active
        ]);

        $status = $sumberDana->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()
            ->with('success', "Sumber Dana berhasil {$status}.");
    }
}
