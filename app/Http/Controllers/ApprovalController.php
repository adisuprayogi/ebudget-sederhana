<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessApprovalRequest;
use App\Models\Approval;
use App\Models\PengajuanDana;
use App\Services\ApprovalService;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * Display a listing of pending approvals.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        $query = Approval::with([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
        ])
        ->where('approver_id', $user->id)
        ->where('status', 'pending');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pengajuanDana', function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('divisi_id')) {
            $query->whereHas('pengajuanDana', function ($q) use ($request) {
                $q->where('divisi_id', $request->divisi_id);
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        $approvals = $query->orderBy('created_at', 'asc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $levels = Approval::select('level')->distinct()->where('approver_id', $user->id)->pluck('level');
        $divisis = \App\Models\Divisi::orderBy('nama_divisi')->get();

        return view('approvals.index', [
            'approvals' => $approvals,
            'filters' => $request->only(['search', 'level', 'divisi_id', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'levels' => $levels,
                'divisis' => $divisis,
            ],
            'user' => $user,
        ]);
    }

    /**
     * Show the approval form.
     */
    public function show(Approval $approval)
    {
        $user = Auth::user();

        // Check if user can view this approval
        if ($approval->approver_id !== $user->id || !$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        // Check if approval is still pending
        if ($approval->status !== 'pending') {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Approval ini sudah diproses');
        }

        $approval->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
            'pengajuanDana.detailPengajuan.subProgram',
            'pengajuanDana.attachments',
            'pengajuanDana.approvals' => function ($query) {
                $query->with('approver')->orderBy('level', 'asc');
            },
        ]);

        return view('approvals.show', [
            'approval' => $approval,
            'pengajuan' => $approval->pengajuanDana,
        ]);
    }

    /**
     * Process the approval.
     */
    public function process(ProcessApprovalRequest $request, Approval $approval)
    {
        $user = Auth::user();

        // Additional authorization check
        if ($approval->approver_id !== $user->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $action = $request->validated()['action'];
            $notes = $request->validated()['notes'] ?? null;

            // Process approval
            $success = ApprovalService::processApproval($approval, $action, $notes);

            if (!$success) {
                throw new \Exception('Gagal memproses approval');
            }

            DB::commit();

            $message = $action === 'disetujui' ? 'Pengajuan berhasil disetujui' : 'Pengajuan ditolak';

            return redirect()
                ->route('approval.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to process approval: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memproses approval. ' . $e->getMessage());
        }
    }

    /**
     * Display approval history.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        $query = Approval::with([
            'pengajuanDana.divisi',
            'pengajuanDana.createdBy',
        ])
        ->where('approver_id', $user->id)
        ->whereIn('status', ['disetujui', 'ditolak']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pengajuanDana', function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('approved_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('approved_at', '<=', $request->tanggal_selesai);
        }

        $approvals = $query->orderBy('approved_at', 'desc')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $levels = Approval::select('level')->distinct()->where('approver_id', $user->id)->pluck('level');

        return view('approvals.history', [
            'approvals' => $approvals,
            'filters' => $request->only(['search', 'status', 'level', 'tanggal_mulai', 'tanggal_selesai']),
            'filterOptions' => [
                'levels' => $levels,
            ],
        ]);
    }

    /**
     * Get approval statistics.
     */
    public function statistics()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        $stats = [
            'pending' => Approval::where('approver_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'approved' => Approval::where('approver_id', $user->id)
                ->where('status', 'disetujui')
                ->count(),
            'rejected' => Approval::where('approver_id', $user->id)
                ->where('status', 'ditolak')
                ->count(),
            'total_processed' => Approval::where('approver_id', $user->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->count(),
        ];

        // Calculate approval rate
        $total = $stats['approved'] + $stats['rejected'];
        $stats['approval_rate'] = $total > 0 ? ($stats['approved'] / $total) * 100 : 0;

        // Average processing time
        $processedApprovals = Approval::where('approver_id', $user->id)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereNotNull('approved_at')
            ->get();

        $stats['avg_processing_time'] = $processedApprovals->avg(function ($approval) {
            return $approval->created_at->diffInHours($approval->approved_at);
        });

        // Recent approvals
        $stats['recent_approvals'] = Approval::with(['pengajuanDana.divisi'])
            ->where('approver_id', $user->id)
            ->orderBy('approved_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($stats);
    }

    /**
     * Get pending approval count for notification badge
     */
    public function pendingCount()
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.approve')) {
            return response()->json(['count' => 0]);
        }

        $count = Approval::where('approver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Bulk approval process
     */
    public function bulkProcess(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        $request->validate([
            'approval_ids' => 'required|array',
            'approval_ids.*' => 'integer|exists:approvals,id',
            'action' => 'required|in:disetujui,ditolak',
            'notes' => 'nullable|string|max:1000',
        ]);

        $action = $request->action;
        $notes = $request->notes;
        $approvalIds = $request->approval_ids;

        DB::beginTransaction();
        try {
            $processedCount = 0;
            $errors = [];

            foreach ($approvalIds as $approvalId) {
                $approval = Approval::where('id', $approvalId)
                    ->where('approver_id', $user->id)
                    ->where('status', 'pending')
                    ->first();

                if (!$approval) {
                    $errors[] = "Approval ID {$approvalId} tidak valid atau sudah diproses";
                    continue;
                }

                $success = ApprovalService::processApproval($approval, $action, $notes);

                if ($success) {
                    $processedCount++;
                } else {
                    $errors[] = "Gagal memproses approval untuk pengajuan: {$approval->pengajuanDana->nomor_pengajuan}";
                }
            }

            DB::commit();

            $message = "Berhasil memproses {$processedCount} approval";
            if (!empty($errors)) {
                $message .= ". " . implode(', ', $errors);
            }

            return redirect()
                ->route('approval.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to bulk process approvals: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memproses approval secara bulk. ' . $e->getMessage());
        }
    }

    /**
     * Print approval document
     */
    public function print(Approval $approval)
    {
        $user = Auth::user();

        // Check if user can view this approval
        if ($approval->approver_id !== $user->id || !$user->hasPermission('pengajuan_dana.approve')) {
            abort(403);
        }

        $approval->load([
            'pengajuanDana.divisi',
            'pengajuanDana.programKerja',
            'pengajuanDana.createdBy',
            'pengajuanDana.detailPengajuan.subProgram',
            'pengajuanDana.approvals' => function ($query) {
                $query->with('approver')->orderBy('level', 'asc');
            },
        ]);

        return view('approvals.print', [
            'approval' => $approval,
            'pengajuan' => $approval->pengajuanDana,
        ]);
    }
}