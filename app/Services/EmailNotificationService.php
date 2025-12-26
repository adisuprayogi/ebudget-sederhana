<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send notification to approver when there's a pending approval
     */
    public static function sendApprovalRequest(Approval $approval, PengajuanDana $pengajuan)
    {
        try {
            $approver = $approval->approver;
            $pengaju = $pengajuan->createdBy;

            if (!$approver || !$approver->email) {
                Log::warning('Approver not found or no email address', ['approval_id' => $approval->id]);
                return false;
            }

            $data = [
                'approver_name' => $approver->full_name,
                'pengaju_name' => $pengaju ? $pengaju->full_name : 'System',
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pengajuan_total' => $pengajuan->total_pengajuan,
                'pengajuan_jenis' => $pengajuan->jenis_pengajuan,
                'approval_level' => $approval->level,
                'approval_url' => route('approvals.show', $approval->id),
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($approver->email)->send(new ApprovalRequestMail($data));

            Log::info('Approval request email sent', [
                'to' => $approver->email,
                'approval_id' => $approval->id,
                'pengajuan_id' => $pengajuan->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send approval request email', [
                'error' => $e->getMessage(),
                'approval_id' => $approval->id,
                'pengajuan_id' => $pengajuan->id
            ]);
            return false;
        }
    }

    /**
     * Send notification to pengaju when pengajuan is approved
     */
    public static function sendPengajuanApproved(PengajuanDana $pengajuan)
    {
        try {
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju || !$pengaju->email) {
                Log::warning('Pengaju not found or no email address', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $data = [
                'pengaju_name' => $pengaju->full_name,
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pengajuan_total' => $pengajuan->total_pengajuan,
                'pengajuan_jenis' => $pengajuan->jenis_pengajuan,
                'approved_at' => $pengajuan->approved_at,
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($pengaju->email)->send(new PengajuanApprovedMail($data));

            Log::info('Pengajuan approved email sent', [
                'to' => $pengaju->email,
                'pengajuan_id' => $pengajuan->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send pengajuan approved email', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id
            ]);
            return false;
        }
    }

    /**
     * Send notification to pengaju when pengajuan is rejected
     */
    public static function sendPengajuanRejected(PengajuanDana $pengajuan, Approval $approval, $notes = null)
    {
        try {
            $pengaju = $pengajuan->createdBy;
            $approver = $approval->approver;

            if (!$pengaju || !$pengaju->email) {
                Log::warning('Pengaju not found or no email address', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $data = [
                'pengaju_name' => $pengaju->full_name,
                'approver_name' => $approver ? $approver->full_name : 'System',
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pengajuan_total' => $pengajuan->total_pengajuan,
                'pengajuan_jenis' => $pengajuan->jenis_pengajuan,
                'rejected_at' => $pengajuan->rejected_at,
                'rejection_notes' => $notes,
                'approval_level' => $approval->level,
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($pengaju->email)->send(new PengajuanRejectedMail($data));

            Log::info('Pengajuan rejected email sent', [
                'to' => $pengaju->email,
                'pengajuan_id' => $pengajuan->id,
                'approval_id' => $approval->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send pengajuan rejected email', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
                'approval_id' => $approval->id
            ]);
            return false;
        }
    }

    /**
     * Send notification to staff keuangan when pengajuan is ready for pencairan
     */
    public static function sendReadyForPencairan(PengajuanDana $pengajuan)
    {
        try {
            $staffKeuangan = User::whereHas('role', function ($query) {
                $query->where('name', 'staff_keuangan');
            })
            ->where('is_active', true)
            ->get();

            if ($staffKeuangan->isEmpty()) {
                Log::warning('No staff keuangan found', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $data = [
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pengajuan_total' => $pengajuan->total_pengajuan,
                'pengajuan_jenis' => $pengajuan->jenis_pengajuan,
                'penerima_manfaat' => PenerimaManfaatService::formatPenerimaManfaat($pengajuan),
                'approved_at' => $pengajuan->approved_at,
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
                'pencairan_url' => route('pencairan-dana.create', ['pengajuan_id' => $pengajuan->id]),
            ];

            foreach ($staffKeuangan as $staff) {
                // Mail::to($staff->email)->send(new ReadyForPencairanMail($data));
            }

            Log::info('Ready for pencairan emails sent', [
                'pengajuan_id' => $pengajuan->id,
                'recipients_count' => $staffKeuangan->count()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send ready for pencairan emails', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id
            ]);
            return false;
        }
    }

    /**
     * Send notification to pengaju when pencairan is processed
     */
    public static function sendPencairanProcessed(PencairanDana $pencairan)
    {
        try {
            $pengajuan = $pencairan->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju || !$pengaju->email) {
                Log::warning('Pengaju not found or no email address', ['pencairan_id' => $pencairan->id]);
                return false;
            }

            $data = [
                'pengaju_name' => $pengaju->full_name,
                'pencairan_nomor' => $pencairan->nomor_pencairan,
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pencairan_total' => $pencairan->total_pencairan,
                'pencairan_tanggal' => $pencairan->tanggal_pencairan,
                'penerima_manfaat' => PenerimaManfaatService::formatPenerimaManfaat($pengajuan),
                'pencairan_url' => route('pencairan-dana.show', $pencairan->id),
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($pengaju->email)->send(new PencairanProcessedMail($data));

            Log::info('Pencairan processed email sent', [
                'to' => $pengaju->email,
                'pencairan_id' => $pencairan->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send pencairan processed email', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            return false;
        }
    }

    /**
     * Send notification for LPJ submission reminder
     */
    public static function sendLpjReminder(PengajuanDana $pengajuan)
    {
        try {
            // Only send for non-pembayaran types
            if ($pengajuan->jenis_pengajuan === 'pembayaran') {
                return false;
            }

            $pengaju = $pengajuan->createdBy;

            if (!$pengaju || !$pengaju->email) {
                Log::warning('Pengaju not found or no email address', ['pengajuan_id' => $pengajuan->id]);
                return false;
            }

            $daysSincePencairan = $pengajuan->dicairkan_at
                ? now()->diffInDays($pengajuan->dicairkan_at)
                : 0;

            $data = [
                'pengaju_name' => $pengaju->full_name,
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'pengajuan_total' => $pengajuan->total_pengajuan,
                'pencairan_date' => $pengajuan->dicairkan_at,
                'days_since_pencairan' => $daysSincePencairan,
                'lpj_url' => route('lpj.create', ['pengajuan_id' => $pengajuan->id]),
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($pengaju->email)->send(new LpjReminderMail($data));

            Log::info('LPJ reminder email sent', [
                'to' => $pengaju->email,
                'pengajuan_id' => $pengajuan->id,
                'days_since_pencairan' => $daysSincePencairan
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send LPJ reminder email', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id
            ]);
            return false;
        }
    }

    /**
     * Send notification when LPJ is submitted
     */
    public static function sendLpjSubmitted(LaporanPertanggungJawaban $lpj)
    {
        try {
            $pengajuan = $lpj->pengajuanDana;
            $staffKeuangan = User::whereHas('role', function ($query) {
                $query->where('name', 'staff_keuangan');
            })
            ->where('is_active', true)
            ->get();

            if ($staffKeuangan->isEmpty()) {
                Log::warning('No staff keuangan found', ['lpj_id' => $lpj->id]);
                return false;
            }

            $data = [
                'lpj_nomor' => $lpj->nomor_lpj,
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'lpj_total_digunakan' => $lpj->total_digunakan,
                'lpj_sisa' => $lpj->sisa_dana,
                'submitted_at' => $lpj->created_at,
                'lpj_url' => route('lpj.show', $lpj->id),
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            foreach ($staffKeuangan as $staff) {
                // Mail::to($staff->email)->send(new LpjSubmittedMail($data));
            }

            Log::info('LPJ submitted emails sent', [
                'lpj_id' => $lpj->id,
                'recipients_count' => $staffKeuangan->count()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send LPJ submitted email', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            return false;
        }
    }

    /**
     * Send notification when refund is processed
     */
    public static function sendRefundProcessed(Refund $refund)
    {
        try {
            $pengajuan = $refund->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju || !$pengaju->email) {
                Log::warning('Pengaju not found or no email address', ['refund_id' => $refund->id]);
                return false;
            }

            $data = [
                'pengaju_name' => $pengaju->full_name,
                'refund_nomor' => $refund->nomor_refund,
                'pengajuan_nomor' => $pengajuan->nomor_pengajuan,
                'pengajuan_judul' => $pengajuan->judul_pengajuan,
                'refund_nominal' => $refund->nominal_refund,
                'refund_jenis' => $refund->jenis_refund,
                'processed_at' => $refund->processed_at,
                'refund_url' => route('refund.show', $refund->id),
                'pengajuan_url' => route('pengajuan-dana.show', $pengajuan->id),
            ];

            // Mail::to($pengaju->email)->send(new RefundProcessedMail($data));

            Log::info('Refund processed email sent', [
                'to' => $pengaju->email,
                'refund_id' => $refund->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send refund processed email', [
                'error' => $e->getMessage(),
                'refund_id' => $refund->id
            ]);
            return false;
        }
    }

    /**
     * Send daily summary to direktur keuangan
     */
    public static function sendDailySummary($date = null)
    {
        try {
            $date = $date ?? now()->subDay();

            $pengajuanCount = PengajuanDana::whereDate('created_at', $date)->count();
            $approvedCount = PengajuanDana::whereDate('approved_at', $date)->count();
            $rejectedCount = PengajuanDana::whereDate('rejected_at', $date)->count();
            $pencairanCount = PencairanDana::whereDate('tanggal_pencairan', $date)->count();

            $direkturKeuangan = User::whereHas('role', function ($query) {
                $query->where('name', 'direktur_keuangan');
            })
            ->where('is_active', true)
            ->first();

            if (!$direkturKeuangan || !$direkturKeuangan->email) {
                Log::warning('Direktur keuangan not found or no email address');
                return false;
            }

            $data = [
                'recipient_name' => $direkturKeuangan->full_name,
                'summary_date' => $date->format('d F Y'),
                'pengajuan_count' => $pengajuanCount,
                'approved_count' => $approvedCount,
                'rejected_count' => $rejectedCount,
                'pencairan_count' => $pencairanCount,
                'total_pengajuan' => PengajuanDana::whereDate('created_at', $date)->sum('total_pengajuan'),
                'total_pencairan' => PencairanDana::whereDate('tanggal_pencairan', $date)->sum('total_pencairan'),
                'dashboard_url' => route('dashboard'),
            ];

            // Mail::to($direkturKeuangan->email)->send(new DailySummaryMail($data));

            Log::info('Daily summary email sent', [
                'to' => $direkturKeuangan->email,
                'date' => $date->format('Y-m-d')
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send daily summary email', [
                'error' => $e->getMessage(),
                'date' => $date?->format('Y-m-d')
            ]);
            return false;
        }
    }

    /**
     * Test email configuration
     */
    public static function testEmailConfiguration($email = null)
    {
        try {
            $testEmail = $email ?? config('mail.test_address');

            if (!$testEmail) {
                Log::warning('No test email address configured');
                return false;
            }

            $data = [
                'test_time' => now()->format('Y-m-d H:i:s'),
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
            ];

            // Mail::to($testEmail)->send(new TestMail($data));

            Log::info('Test email sent', ['to' => $testEmail]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'error' => $e->getMessage(),
                'email' => $testEmail ?? null
            ]);
            return false;
        }
    }

    /**
     * Get email recipient list based on role and divisi
     */
    public static function getRecipientsByRole($roleName, $divisiId = null)
    {
        $query = User::whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->where('is_active', true);

        if ($divisiId) {
            $query->where('divisi_id', $divisiId);
        }

        return $query->get();
    }

    /**
     * Send custom email with template
     */
    public static function sendCustomEmail($recipients, $subject, $template, $data = [])
    {
        try {
            if (is_string($recipients)) {
                $recipients = [$recipients];
            }

            foreach ($recipients as $recipient) {
                // Mail::to($recipient)->send(new CustomMail($subject, $template, $data));
            }

            Log::info('Custom emails sent', [
                'recipients_count' => count($recipients),
                'template' => $template
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send custom emails', [
                'error' => $e->getMessage(),
                'recipients' => $recipients
            ]);
            return false;
        }
    }
}