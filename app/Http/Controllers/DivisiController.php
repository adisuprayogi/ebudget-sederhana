<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DivisiController extends Controller
{
    /**
     * Display a listing of divisi.
     */
    public function index(Request $request): View
    {
        $query = Divisi::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_divisi', 'like', "%{$search}%")
                  ->orWhere('kode_divisi', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $divisis = $query->withCount('users')->orderBy('kode_divisi')->paginate(15)->withQueryString();

        return view('admin.divisi.index', compact('divisis'));
    }

    /**
     * Show the form for creating a new divisi.
     */
    public function create(): View
    {
        return view('admin.divisi.create');
    }

    /**
     * Store a newly created divisi.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_divisi' => 'required|string|max:20|unique:divisis,kode_divisi',
            'nama_divisi' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'singkatan' => 'nullable|string|max:20',
        ]);

        Divisi::create($validated);

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil dibuat.');
    }

    /**
     * Display the specified divisi.
     */
    public function show(Divisi $divisi): View
    {
        $divisi->load(['users']);
        return view('admin.divisi.show', compact('divisi'));
    }

    /**
     * Show the form for editing the specified divisi.
     */
    public function edit(Divisi $divisi): View
    {
        return view('admin.divisi.edit', compact('divisi'));
    }

    /**
     * Update the specified divisi.
     */
    public function update(Request $request, Divisi $divisi): RedirectResponse
    {
        $validated = $request->validate([
            'kode_divisi' => 'required|string|max:20|unique:divisis,kode_divisi,' . $divisi->id,
            'nama_divisi' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'singkatan' => 'nullable|string|max:20',
        ]);

        $divisi->update($validated);

        return redirect()->route('admin.divisi.show', $divisi)
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified divisi.
     */
    public function destroy(Divisi $divisi): RedirectResponse
    {
        if ($divisi->users()->count() > 0) {
            return back()->with('error', 'Divisi tidak dapat dihapus karena masih memiliki user.');
        }

        $divisi->delete();

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
