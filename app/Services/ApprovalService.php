<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\User;
use App\Models\ApprovalConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalService
{
    /**
     * Create approval workflow for pengajuan
     */
    public static function createApprovalWorkflow(PengajuanDana $pengajuan)
    {
        // Get approval configuration based on jenis and nominal
        // First try to get config for specific jenis_pengajuan
        $configs = ApprovalConfig::where('jenis_pengajuan', $pengajuan->jenis_pengajuan)
            ->where('minimal_nominal', '<=', $pengajuan->total_pengajuan)
            ->orderBy('urutan', 'asc')
            ->get();

        // If no config found for specific jenis, use default 'pengajuan_dana' config
        if ($configs->isEmpty()) {
            $configs = ApprovalConfig::where('jenis_pengajuan', 'pengajuan_dana')
                ->where('minimal_nominal', '<=', $pengajuan->total_pengajuan)
                ->orderBy('urutan', 'asc')
                ->get();
        }

        $approvals = [];
        $firstApproval = true;

        foreach ($configs as $config) {
            // Skip approval if it's the same level as the user
            if ($pengajuan->created_by && self::isSameLevel($config->level, $pengajuan->created_by)) {
                continue;
            }

            // Determine approver
            $approverId = self::getApproverId($config->level, $pengajuan);

            if ($approverId) {
                // Only first approval is pending, others are 'waiting'
                $status = $firstApproval ? 'pending' : 'waiting';

                $approval = Approval::create([
                    'pengajuan_dana_id' => $pengajuan->id,
                    'approver_id' => $approverId,
                    'level' => $config->level,
                    'urutan' => $config->urutan,
                    'status' => $status,
                    'required' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $approvals[] = $approval;

                // Send email notification only for first (pending) approval
                if ($firstApproval) {
                    self::sendApprovalNotification($approval, $pengajuan);
                    $firstApproval = false;
                }
            }
        }

        // Update pengajuan status
        if (count($approvals) > 0) {
            $pengajuan->update([
                'status' => 'menunggu_approval',
                'submitted_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // No approval needed, set to draft
            $pengajuan->update([
                'status' => 'draft',
                'updated_at' => now(),
            ]);
        }

        return $approvals;
    }

    /**
     * Process approval action
     */
    public static function processApproval(Approval $approval, $action, $notes = null)
    {
        $pengajuan = $approval->pengajuanDana;
        $approver = $approval->approver;

        // Log before processing
        \Log::info('Processing approval', [
            'approval_id' => $approval->id,
            'pengajuan_id' => $pengajuan->id,
            'action' => $action,
            'old_approval_status' => $approval->status,
            'old_pengajuan_status' => $pengajuan->status,
        ]);

        DB::beginTransaction();
        try {
            // Update approval status
            $approval->update([
                'status' => $action,
                'notes' => $notes,
                'approved_at' => now(),
                'approved_by' => $approver->id,
                'updated_at' => now(),
            ]);

            // Check if rejected FIRST - this should take priority
            if ($action === 'ditolak') {
                // Approval rejected, cancel workflow
                Approval::where('pengajuan_dana_id', $pengajuan->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'cancelled',
                        'notes' => 'Cancelled due to rejection at level ' . $approval->level,
                        'updated_at' => now(),
                    ]);

                $pengajuan->update([
                    'status' => 'ditolak',
                    'rejected_at' => now(),
                    'updated_at' => now(),
                ]);

                \Log::info('Pengajuan rejected', [
                    'pengajuan_id' => $pengajuan->id,
                    'approval_id' => $approval->id,
                    'new_status' => 'ditolak',
                ]);

                // Send notification to pengaju
                self::sendRejectedNotification($pengajuan, $approval, $notes);

            } else {
                // Action is 'disetujui' - activate next approval in sequence
                // Find next approval with higher urutan that has 'waiting' status
                $nextApproval = Approval::where('pengajuan_dana_id', $pengajuan->id)
                    ->where('urutan', '>', $approval->urutan ?? 0)
                    ->where('status', 'waiting')
                    ->orderBy('urutan', 'asc')
                    ->first();

                if ($nextApproval) {
                    // Activate next approval
                    $nextApproval->update([
                        'status' => 'pending',
                        'updated_at' => now(),
                    ]);

                    \Log::info('Next approval activated', [
                        'pengajuan_id' => $pengajuan->id,
                        'current_approval_id' => $approval->id,
                        'next_approval_id' => $nextApproval->id,
                        'next_level' => $nextApproval->level,
                    ]);

                    // Send notification to next approver
                    self::sendApprovalNotification($nextApproval, $pengajuan);
                } else {
                    // No more approvals, all completed - approve pengajuan
                    $pengajuan->update([
                        'status' => 'disetujui',
                        'approved_at' => now(),
                        'updated_at' => now(),
                    ]);

                    \Log::info('Pengajuan approved - all levels completed', [
                        'pengajuan_id' => $pengajuan->id,
                        'new_status' => 'disetujui',
                    ]);

                    // Send notification to pengaju
                    self::sendApprovedNotification($pengajuan);

                    // Notify staff keuangan for pencairan
                    self::notifyStaffKeuangan($pengajuan);
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to process approval', [
                'approval_id' => $approval->id,
                'pengajuan_id' => $pengajuan->id,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get approver ID based on level
     */
    private static function getApproverId($level, $pengajuan)
    {
        switch ($level) {
            case 'kepala_divisi':
                // Get kepala divisi from same divisi as pengajuan
                return User::whereHas('roles', function ($query) {
                        $query->where('name', 'kepala_divisi');
                    })
                    ->whereHas('divisis', function ($query) use ($pengajuan) {
                        $query->where('divisis.id', $pengajuan->divisi_id);
                    })
                    ->where('is_active', true)
                    ->first()?->id;

            case 'direktur_keuangan':
                // Get direktur keuangan
                return User::whereHas('roles', function ($query) {
                        $query->where('name', 'direktur_keuangan');
                    })
                    ->where('is_active', true)
                    ->first()?->id;

            case 'direktur_utama':
                // Get direktur utama
                return User::whereHas('roles', function ($query) {
                        $query->where('name', 'direktur_utama');
                    })
                    ->where('is_active', true)
                    ->first()?->id;

            default:
                return null;
        }
    }

    /**
     * Check if user is same level as approval level
     */
    private static function isSameLevel($level, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // Get user's first role
        $userRole = $user->roles()->first()?->name;

        $roleLevelMapping = [
            'kepala_divisi' => 'kepala_divisi',
            'direktur_keuangan' => 'direktur_keuangan',
            'direktur_utama' => 'direktur_utama',
        ];

        return isset($roleLevelMapping[$level]) && $roleLevelMapping[$level] === $userRole;
    }

    /**
     * Send approval notification email
     */
    private static function sendApprovalNotification($approval, $pengajuan)
    {
        // Implementation would use Laravel's Mail facade
        // This is a placeholder for email implementation
        // Mail::to($approval->approver->email)->send(new ApprovalRequestMail($pengajuan, $approval));
    }

    /**
     * Send approved notification email
     */
    private static function sendApprovedNotification($pengajuan)
    {
        // Send to pengaju
        if ($pengajuan->created_by) {
            $pengaju = User::find($pengajuan->created_by);
            if ($pengaju) {
                // Mail::to($pengaju->email)->send(new PengajuanApprovedMail($pengajuan));
            }
        }
    }

    /**
     * Send rejected notification email
     */
    private static function sendRejectedNotification($pengajuan, $approval, $notes)
    {
        // Send to pengaju
        if ($pengajuan->created_by) {
            $pengaju = User::find($pengajuan->created_by);
            if ($pengaju) {
                // Mail::to($pengaju->email)->send(new PengajuanRejectedMail($pengajuan, $approval, $notes));
            }
        }
    }

    /**
     * Notify staff keuangan
     */
    private static function notifyStaffKeuangan($pengajuan)
    {
        // Get all staff keuangan users
        $staffKeuangan = User::whereHas('roles', function ($query) {
                $query->where('name', 'staff_keuangan');
            })
            ->where('is_active', true)
            ->get();

        foreach ($staffKeuangan as $staff) {
            // Mail::to($staff->email)->send(new PengajuanApprovedForPencairanMail($pengajuan));
        }
    }

    /**
     * Get approval status for pengajuan
     */
    public static function getApprovalStatus($pengajuanId)
    {
        $approvals = Approval::where('pengajuan_dana_id', $pengajuanId)
            ->with(['approver' => function($query) {
                $query->select('id', 'full_name', 'email');
            }])
            ->orderBy('level', 'asc')
            ->get();

        $status = [];
        foreach ($approvals as $approval) {
            $status[] = [
                'level' => $approval->level,
                'status' => $approval->status,
                'approver' => $approval->approver,
                'approved_at' => $approval->approved_at,
                'notes' => $approval->notes,
            ];
        }

        return $status;
    }

    /**
     * Check if user can approve pengajuan
     */
    public static function canApprove($user, $pengajuan)
    {
        if (!$user || !$pengajuan) {
            return false;
        }

        // Check if user has approval permission
        if (!$user->hasPermission('pengajuan_dana.approve')) {
            return false;
        }

        // Check if there's a pending approval for this user
        $pendingApproval = Approval::where('pengajuan_dana_id', $pengajuan->id)
            ->where('approver_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return $pendingApproval !== null;
    }

    /**
     * Get next approval in workflow
     */
    public static function getNextApproval($pengajuanId)
    {
        return Approval::where('pengajuan_dana_id', $pengajuanId)
            ->where('status', 'pending')
            ->with(['approver' => function($query) {
                $query->select('id', 'full_name', 'email', 'role_id');
            }])
            ->orderBy('level', 'asc')
            ->first();
    }
}