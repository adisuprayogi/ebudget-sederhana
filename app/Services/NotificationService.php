<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create notification for approver when there's a pending approval
     */
    public static function createApprovalNotification(Approval $approval, PengajuanDana $pengajuan)
    {
        try {
            $approver = $approval->approver;
            $pengaju = $pengajuan->createdBy;

            if (!$approver) {
                Log::warning('Approver not found for notification', ['approval_id' => $approval->id]);
                return false;
            }

            // Get level label in Indonesian
            $levelLabel = self::getLevelLabel($approval->level);

            $tanggal = optional($pengajuan->tanggal_pengajuan)->format('d/m/Y');
            $pengajuName = $pengaju->full_name ?: 'System';
            $tanggalStr = $tanggal ?: '-';

            $notification = Notification::create([
                'user_id' => $approver->id,
                'type' => 'info',
                'title' => 'Pengajuan Dana Menunggu Approval',
                'message' => "Pengajuan dana {$pengajuan->nomor_pengajuan} dari {$pengajuName} menunggu approval Anda (Level: {$levelLabel}).\n\n" .
                          "Judul: {$pengajuan->judul_pengajuan}\n" .
                          "Total: " . number_format($pengajuan->total_pengajuan, 0, ',', '.') . "\n" .
                          "Tanggal: " . $tanggalStr,
                'link' => route('approvals.show', $approval->id),
                'notifiable_type' => Approval::class,
                'notifiable_id' => $approval->id,
                'is_read' => false,
            ]);

            Log::info('Approval notification created', [
                'notification_id' => $notification->id,
                'approval_id' => $approval->id,
                'approver_id' => $approver->id,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create approval notification', [
                'error' => $e->getMessage(),
                'approval_id' => $approval->id,
            ]);
            return false;
        }
    }

    /**
     * Create notification for pengaju when pengajuan is approved at a level
     */
    public static function createApprovedLevelNotification(PengajuanDana $pengajuan, Approval $approval)
    {
        try {
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                Log::warning('Pengaju not found for notification', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $levelLabel = self::getLevelLabel($approval->level);
            $approver = $approval->approver;

            // Check if there are more approvals needed
            $remainingApprovals = Approval::where('pengajuan_dana_id', $pengajuan->id)
                ->whereIn('status', ['pending', 'waiting'])
                ->count();

            $message = $remainingApprovals > 0
                ? "Pengajuan dana {$pengajuan->nomor_pengajuan} telah disetujui oleh {$approver->full_name} (Level: {$levelLabel}). Menunggu approval selanjutnya."
                : "Pengajuan dana {$pengajuan->nomor_pengajuan} telah disetujui penuh dan siap untuk pencairan.";

            $notification = Notification::create([
                'user_id' => $pengaju->id,
                'type' => 'success',
                'title' => $remainingApprovals > 0 ? 'Pengajuan Disetujui' : 'Pengajuan Disetujui Penuh',
                'message' => $message,
                'link' => route('pengajuan-dana.show', $pengajuan->id),
                'notifiable_type' => PengajuanDana::class,
                'notifiable_id' => $pengajuan->id,
                'is_read' => false,
            ]);

            Log::info('Approved level notification created', [
                'notification_id' => $notification->id,
                'pengajuan_id' => $pengajuan->id,
                'pengaju_id' => $pengaju->id,
                'remaining_approvals' => $remainingApprovals,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create approved level notification', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
            ]);
            return false;
        }
    }

    /**
     * Create notification for pengaju when pengajuan is fully approved
     */
    public static function createAllApprovedNotification(PengajuanDana $pengajuan)
    {
        try {
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                Log::warning('Pengaju not found for notification', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $notification = Notification::create([
                'user_id' => $pengaju->id,
                'type' => 'success',
                'title' => 'Pengajuan Disetujui Penuh',
                'message' => "Pengajuan dana {$pengajuan->nomor_pengajuan} telah disetujui penuh dan siap untuk pencairan.\n\n" .
                          "Judul: {$pengajuan->judul_pengajuan}\n" .
                          "Total: " . number_format($pengajuan->total_pengajuan, 0, ',', '.') . "\n" .
                          "Silakan hubungi staff keuangan untuk proses pencairan.",
                'link' => route('pengajuan-dana.show', $pengajuan->id),
                'notifiable_type' => PengajuanDana::class,
                'notifiable_id' => $pengajuan->id,
                'is_read' => false,
            ]);

            Log::info('All approved notification created for pengaju', [
                'notification_id' => $notification->id,
                'pengajuan_id' => $pengajuan->id,
                'pengaju_id' => $pengaju->id,
            ]);

            // Also notify staff keuangan
            self::notifyStaffKeuanganForPencairan($pengajuan);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create all approved notification', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
            ]);
            return false;
        }
    }

    /**
     * Create notification when pengajuan is rejected
     */
    public static function createRejectedNotification(PengajuanDana $pengajuan, Approval $approval, $notes = null)
    {
        try {
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                Log::warning('Pengaju not found for notification', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $approver = $approval->approver;
            $levelLabel = self::getLevelLabel($approval->level);

            $message = "Pengajuan dana {$pengajuan->nomor_pengajuan} ditolak oleh {$approver->full_name} (Level: {$levelLabel}).\n\n" .
                      "Judul: {$pengajuan->judul_pengajuan}\n" .
                      "Total: " . number_format($pengajuan->total_pengajuan, 0, ',', '.');

            if ($notes) {
                $message .= "\n\nAlasan: {$notes}";
            }

            $message .= "\n\nSilakan periksa kembali pengajuan Anda atau buat pengajuan baru.";

            $notification = Notification::create([
                'user_id' => $pengaju->id,
                'type' => 'error',
                'title' => 'Pengajuan Dana Ditolak',
                'message' => $message,
                'link' => route('pengajuan-dana.show', $pengajuan->id),
                'notifiable_type' => PengajuanDana::class,
                'notifiable_id' => $pengajuan->id,
                'is_read' => false,
            ]);

            Log::info('Rejected notification created', [
                'notification_id' => $notification->id,
                'pengajuan_id' => $pengajuan->id,
                'pengaju_id' => $pengaju->id,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create rejected notification', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
            ]);
            return false;
        }
    }

    /**
     * Create notification when pengajuan needs revision
     */
    public static function createRevisionNotification(PengajuanDana $pengajuan, Approval $approval, $notes = null)
    {
        try {
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                Log::warning('Pengaju not found for notification', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $approver = $approval->approver;
            $levelLabel = self::getLevelLabel($approval->level);

            $message = "Pengajuan dana {$pengajuan->nomor_pengajuan} memerlukan revisi dari {$approver->full_name} (Level: {$levelLabel}).\n\n" .
                      "Judul: {$pengajuan->judul_pengajuan}\n" .
                      "Total: " . number_format($pengajuan->total_pengajuan, 0, ',', '.');

            if ($notes) {
                $message .= "\n\nCatatan: {$notes}";
            }

            $message .= "\n\nSilakan perbarui pengajuan dan submit kembali.";

            $notification = Notification::create([
                'user_id' => $pengaju->id,
                'type' => 'warning',
                'title' => 'Pengajuan Dana Perlu Revisi',
                'message' => $message,
                'link' => route('pengajuan-dana.edit', $pengajuan->id),
                'notifiable_type' => PengajuanDana::class,
                'notifiable_id' => $pengajuan->id,
                'is_read' => false,
            ]);

            Log::info('Revision notification created', [
                'notification_id' => $notification->id,
                'pengajuan_id' => $pengajuan->id,
                'pengaju_id' => $pengaju->id,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create revision notification', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
            ]);
            return false;
        }
    }

    /**
     * Notify staff keuangan for pencairan
     */
    private static function notifyStaffKeuanganForPencairan(PengajuanDana $pengajuan)
    {
        try {
            $staffKeuangan = User::whereHas('roles', function ($query) {
                $query->where('name', 'staff_keuangan');
            })
            ->where('is_active', true)
            ->get();

            foreach ($staffKeuangan as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'info',
                    'title' => 'Pengajuan Siap Dicairkan',
                    'message' => "Pengajuan dana {$pengajuan->nomor_pengajuan} telah disetujui penuh dan siap untuk pencairan.\n\n" .
                              "Judul: {$pengajuan->judul_pengajuan}\n" .
                              "Total: " . number_format($pengajuan->total_pengajuan, 0, ',', '.') . "\n" .
                              "Pengaju: " . ($pengajuan->createdBy->full_name ?? '-'),
                    'link' => route('pencairan-dana.create', ['pengajuan_id' => $pengajuan->id]),
                    'notifiable_type' => PengajuanDana::class,
                    'notifiable_id' => $pengajuan->id,
                    'is_read' => false,
                ]);
            }

            Log::info('Staff keuangan notified for pencairan', [
                'pengajuan_id' => $pengajuan->id,
                'staff_count' => $staffKeuangan->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify staff keuangan', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
            ]);
        }
    }

    /**
     * Get Indonesian label for approval level
     */
    private static function getLevelLabel($level)
    {
        return match($level) {
            'kepala_divisi' => 'Kepala Divisi',
            'direktur_keuangan' => 'Direktur Keuangan',
            'direktur_utama' => 'Direktur Utama',
            'staff_keuangan' => 'Staff Keuangan',
            default => ucfirst(str_replace('_', ' ', $level)),
        };
    }

    /**
     * Create bulk notifications for multiple users
     */
    public static function createBulkNotifications($userIds, $title, $message, $link, $type = 'info', $notifiableType = null, $notifiableId = null)
    {
        try {
            $notifications = [];
            $now = now();

            foreach ($userIds as $userId) {
                $notifications[] = [
                    'user_id' => $userId,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'link' => $link,
                    'notifiable_type' => $notifiableType,
                    'notifiable_id' => $notifiableId,
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($notifications)) {
                Notification::insert($notifications);
                Log::info('Bulk notifications created', ['count' => count($notifications)]);
            }

            return count($notifications);
        } catch (\Exception $e) {
            Log::error('Failed to create bulk notifications', [
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }
}
