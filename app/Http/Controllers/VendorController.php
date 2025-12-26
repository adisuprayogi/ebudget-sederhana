<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vendor::with(['createdBy', 'updatedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_vendor', 'like', "%{$search}%")
                  ->orWhere('nama_vendor', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_vendor')) {
            $query->where('jenis_vendor', $request->jenis_vendor);
        }

        if ($request->filled('kota')) {
            $query->where('kota', 'like', "%{$request->kota}%");
        }

        $vendors = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $statuses = Vendor::select('status')->distinct()->pluck('status');
        $jenisVendors = Vendor::select('jenis_vendor')->distinct()->pluck('jenis_vendor');
        $kotas = Vendor::select('kota')->whereNotNull('kota')->distinct()->orderBy('kota')->pluck('kota');

        return view('admin.vendors.index', [
            'vendors' => $vendors,
            'filters' => $request->only(['search', 'status', 'jenis_vendor', 'kota']),
            'filterOptions' => [
                'statuses' => $statuses,
                'jenisVendors' => $jenisVendors,
                'kotas' => $kotas,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vendors.create', [
            'jenisVendors' => ['supplier', 'kontraktor', 'konsultan', 'lainnya'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_vendor' => 'required|string|max:50|unique:vendors,kode_vendor',
            'nama_vendor' => 'required|string|max:255',
            'jenis_vendor' => 'required|in:supplier,kontraktor,konsultan,lainnya',
            'npwp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'propinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'negara' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'kontak_person' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive,blacklisted',
            'rating' => 'nullable|numeric|min:0|max:5',
            'catatan' => 'nullable|string',
        ], [
            'kode_vendor.unique' => 'Kode vendor sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $vendor = Vendor::create([
                'kode_vendor' => $validated['kode_vendor'],
                'nama_vendor' => $validated['nama_vendor'],
                'jenis_vendor' => $validated['jenis_vendor'],
                'npwp' => $validated['npwp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'kota' => $validated['kota'] ?? null,
                'propinsi' => $validated['propinsi'] ?? null,
                'kode_pos' => $validated['kode_pos'] ?? null,
                'negara' => $validated['negara'] ?? 'Indonesia',
                'telepon' => $validated['telepon'] ?? null,
                'email' => $validated['email'] ?? null,
                'kontak_person' => $validated['kontak_person'] ?? null,
                'nomor_rekening' => $validated['nomor_rekening'] ?? null,
                'nama_bank' => $validated['nama_bank'] ?? null,
                'status' => $validated['status'] ?? 'active',
                'rating' => $validated['rating'] ?? 0,
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('vendors.show', $vendor)
                ->with('success', 'Vendor berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create vendor: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat vendor. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['createdBy', 'updatedBy']);

        // Get related transactions if any (pencairan dana, etc.)
        // This can be expanded based on your application needs

        return view('admin.vendors.show', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', [
            'vendor' => $vendor,
            'jenisVendors' => ['supplier', 'kontraktor', 'konsultan', 'lainnya'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'kode_vendor' => 'required|string|max:50|unique:vendors,kode_vendor,' . $vendor->id,
            'nama_vendor' => 'required|string|max:255',
            'jenis_vendor' => 'required|in:supplier,kontraktor,konsultan,lainnya',
            'npwp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'propinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'negara' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'kontak_person' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive,blacklisted',
            'rating' => 'nullable|numeric|min:0|max:5',
            'catatan' => 'nullable|string',
        ], [
            'kode_vendor.unique' => 'Kode vendor sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $vendor->update([
            'kode_vendor' => $validated['kode_vendor'],
            'nama_vendor' => $validated['nama_vendor'],
            'jenis_vendor' => $validated['jenis_vendor'],
            'npwp' => $validated['npwp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kota' => $validated['kota'] ?? null,
            'propinsi' => $validated['propinsi'] ?? null,
            'kode_pos' => $validated['kode_pos'] ?? null,
            'negara' => $validated['negara'] ?? 'Indonesia',
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'kontak_person' => $validated['kontak_person'] ?? null,
            'nomor_rekening' => $validated['nomor_rekening'] ?? null,
            'nama_bank' => $validated['nama_bank'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'rating' => $validated['rating'] ?? 0,
            'catatan' => $validated['catatan'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('vendors.show', $vendor)
            ->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        // Check if vendor has related transactions
        // Add this check when you have the relationships set up

        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }

    /**
     * Toggle vendor status.
     */
    public function toggleStatus(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,blacklisted',
        ]);

        $vendor->update([
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Status vendor berhasil diperbarui.');
    }

    /**
     * Get vendor options for dropdown/select.
     */
    public function options(Request $request)
    {
        $query = Vendor::active();

        if ($request->filled('jenis_vendor')) {
            $query->where('jenis_vendor', $request->jenis_vendor);
        }

        $vendors = $query->orderBy('nama_vendor')->get(['id', 'kode_vendor', 'nama_vendor', 'jenis_vendor']);

        return response()->json($vendors);
    }

    /**
     * Get vendor statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => Vendor::count(),
            'active' => Vendor::active()->count(),
            'inactive' => Vendor::inactive()->count(),
            'blacklisted' => Vendor::blacklisted()->count(),
            'by_type' => [
                'supplier' => Vendor::byType('supplier')->count(),
                'kontraktor' => Vendor::byType('kontraktor')->count(),
                'konsultan' => Vendor::byType('konsultan')->count(),
                'lainnya' => Vendor::byType('lainnya')->count(),
            ],
            'avg_rating' => Vendor::active()->avg('rating') ?? 0,
        ];

        return response()->json($stats);
    }

    /**
     * Export vendor data to Excel.
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality using maatwebsite/excel
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}
