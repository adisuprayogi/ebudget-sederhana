<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\DetailPencairan;
use App\Models\DetailPengajuan;
use App\Models\User;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PencairanService
{
    /**
     * Create pencairan dana from pengajuan
     */
    public static function createPencairan(PengajuanDana $pengajuan, $data = [])
    {
        DB::beginTransaction();
        try {
            // Validate pengajuan status
            if ($pengajuan->status !== 'disetujui') {
                throw new \Exception('Pengajuan must be approved before pencairan');
            }

            // Check if pencairan already exists
            if ($pengajuan->pencairanDana) {
                throw new \Exception('Pencairan already exists for this pengajuan');
            }

            // Generate nomor pencairan
            $nomorPencairan = NumberingService::generateNomorPencairan();

            // Create pencairan dana
            $pencairan = PencairanDana::create([
                'pengajuan_dana_id' => $pengajuan->id,
                'nomor_pencairan' => $nomorPencairan,
                'tanggal_pencairan' => $data['tanggal_pencairan'] ?? now()->format('Y-m-d'),
                'total_pencairan' => $data['total_pencairan'] ?? $pengajuan->total_pengajuan,
                'cara_pencairan' => $data['cara_pencairan'] ?? 'transfer',
                'bank_tujuan' => $data['bank_tujuan'] ?? null,
                'nomor_rekening' => $data['nomor_rekening'] ?? null,
                'atas_nama' => $data['atas_nama'] ?? null,
                'status' => 'pending',
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create detail pencairan based on detail pengajuan
            $detailPengajuans = DetailPengajuan::where('pengajuan_dana_id', $pengajuan->id)->get();

            foreach ($detailPengajuans as $detailPengajuan) {
                DetailPencairan::create([
                    'pencairan_dana_id' => $pencairan->id,
                    'detail_pengajuan_id' => $detailPengajuan->id,
                    'uraian' => $detailPengajuan->uraian,
                    'volume' => $detailPengajuan->volume,
                    'satuan' => $detailPengajuan->satuan,
                    'harga_satuan' => $detailPengajuan->harga_satuan,
                    'subtotal' => $detailPengajuan->subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update pengajuan status
            $pengajuan->update([
                'status' => 'menunggu_pencairan',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notification to staff keuangan
            self::notifyStaffKeuangan($pencairan);

            return $pencairan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create pencairan', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id
            ]);
            throw $e;
        }
    }

    /**
     * Process pencairan (mark as processed)
     */
    public static function processPencairan(PencairanDana $pencairan, $data = [])
    {
        DB::beginTransaction();
        try {
            // Validate pencairan status
            if ($pencairan->status !== 'pending') {
                throw new \Exception('Pencairan must be in pending status');
            }

            // Update pencairan status
            $pencairan->update([
                'status' => 'processed',
                'processed_at' => now(),
                'processed_by' => auth()->id(),
                'bukti_pencairan' => $data['bukti_pencairan'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan = $pencairan->pengajuanDana;
            $pengajuan->update([
                'status' => $pengajuan->jenis_pengajuan === 'pembayaran' ? 'selesai' : 'dicairkan',
                'dicairkan_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notifications
            self::notifyPengaju($pencairan);

            // For pembayaran type, also send confirmation request if external
            if ($pengajuan->jenis_pengajuan === 'pembayaran' && $pengajuan->penerima_manfaat_type === 'external') {
                self::requestConfirmation($pencairan);
            }

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process pencairan', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            throw $e;
        }
    }

    /**
     * Verify pencairan (for pembayaran type - internal/external confirmation)
     */
    public static function verifyPencairan(PencairanDana $pencairan, $confirmed = true, $notes = null)
    {
        DB::beginTransaction();
        try {
            $pengajuan = $pencairan->pengajuanDana;

            // Only allow verification for pembayaran type
            if ($pengajuan->jenis_pengajuan !== 'pembayaran') {
                throw new \Exception('Verification only available for pembayaran type');
            }

            // Update pencairan status
            $pencairan->update([
                'status' => $confirmed ? 'completed' : 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $notes,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan->update([
                'status' => $confirmed ? 'selesai' : 'pencairan_rejected',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notification to pengaju
            self::notifyVerificationResult($pencairan, $confirmed, $notes);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to verify pencairan', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            throw $e;
        }
    }

    /**
     * Cancel pencairan
     */
    public static function cancelPencairan(PencairanDana $pencairan, $reason = null)
    {
        DB::beginTransaction();
        try {
            // Validate pencairan status
            if ($pencairan->status === 'completed' || $pencairan->status === 'cancelled') {
                throw new \Exception('Cannot cancel completed or already cancelled pencairan');
            }

            // Update pencairan status
            $pencairan->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $reason,
                'updated_at' => now(),
            ]);

            // Update pengajuan status back to approved
            $pengajuan = $pencairan->pengajuanDana;
            $pengajuan->update([
                'status' => 'disetujui',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notification to pengaju
            self::notifyPengajuCancellation($pencairan, $reason);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel pencairan', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            throw $e;
        }
    }

    /**
     * Get pencairan statistics
     */
    public static function getPencairanStatistics($startDate = null, $endDate = null, $divisiId = null)
    {
        $query = PencairanDana::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy']);

        if ($startDate) {
            $query->whereDate('tanggal_pencairan', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_pencairan', '<=', $endDate);
        }

        if ($divisiId) {
            $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        $pencairans = $query->get();

        return [
            'total_pencairan' => $pencairans->count(),
            'total_nominal' => $pencairans->sum('total_pencairan'),
            'by_status' => $pencairans->groupBy('status')->map->count(),
            'by_month' => $pencairans->groupBy(function ($item) {
                return $item->tanggal_pencairan->format('Y-m');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_pencairan')
                ];
            }),
            'by_divisi' => $pencairans->groupBy(function ($item) {
                return $item->pengajuanDana->divisi->nama_divisi ?? 'Unknown';
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_pencairan')
                ];
            }),
            'average_pencairan' => $pencairans->avg('total_pencairan'),
            'max_pencairan' => $pencairans->max('total_pencairan'),
            'min_pencairan' => $pencairans->min('total_pencairan'),
        ];
    }

    /**
     * Get pending pencairan list
     */
    public static function getPendingPencairan($limit = 10)
    {
        return PencairanDana::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy'])
            ->where('status', 'pending')
            ->orderBy('tanggal_pencairan', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if pencairan can be created for pengajuan
     */
    public static function canCreatePencairan(PengajuanDana $pengajuan)
    {
        // Check pengajuan status
        if ($pengajuan->status !== 'disetujui') {
            return false;
        }

        // Check if pencairan already exists
        if ($pengajuan->pencairanDana) {
            return false;
        }

        return true;
    }

    /**
     * Get pencairan due dates (upcoming pencairan)
     */
    public static function getUpcomingPencairan($days = 7)
    {
        return PencairanDana::with(['pengajuanDana.divisi'])
            ->where('status', 'pending')
            ->whereDate('tanggal_pencairan', '<=', now()->addDays($days))
            ->orderBy('tanggal_pencairan', 'asc')
            ->get();
    }

    /**
     * Export pencairan data
     */
    public static function exportPencairan($startDate = null, $endDate = null, $divisiId = null)
    {
        $query = PencairanDana::with([
            'pengajuanDana.divisi',
            'pengajuanDana.createdBy',
            'detailPencairans'
        ]);

        if ($startDate) {
            $query->whereDate('tanggal_pencairan', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_pencairan', '<=', $endDate);
        }

        if ($divisiId) {
            $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        return $query->orderBy('tanggal_pencairan', 'desc')->get();
    }

    /**
     * Notify staff keuangan about new pencairan request
     */
    private static function notifyStaffKeuangan($pencairan)
    {
        try {
            $staffKeuangan = User::whereHas('role', function ($query) {
                $query->where('name', 'staff_keuangan');
            })
            ->where('is_active', true)
            ->get();

            foreach ($staffKeuangan as $staff) {
                Notifications::create([
                    'user_id' => $staff->id,
                    'title' => 'Pencairan Request',
                    'message' => "New pencairan request: {$pencairan->nomor_pencairan}",
                    'type' => 'pencairan',
                    'data' => json_encode(['pencairan_id' => $pencairan->id]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify staff keuangan', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
        }
    }

    /**
     * Notify pengaju about pencairan processed
     */
    private static function notifyPengaju($pencairan)
    {
        try {
            $pengajuan = $pencairan->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => 'Pencairan Processed',
                'message' => "Your pencairan {$pencairan->nomor_pencairan} has been processed",
                'type' => 'pencairan',
                'data' => json_encode(['pencairan_id' => $pencairan->id]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify pengaju', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
        }
    }

    /**
     * Request confirmation for external pembayaran
     */
    private static function requestConfirmation($pencairan)
    {
        try {
            $pengajuan = $pencairan->pengajuanDana;

            // Create notification for external confirmation
            Notifications::create([
                'user_id' => $pengajuan->created_by,
                'title' => 'Confirmation Required',
                'message' => "External confirmation required for pencairan {$pencairan->nomor_pencairan}",
                'type' => 'confirmation',
                'data' => json_encode(['pencairan_id' => $pencairan->id]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to request confirmation', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
        }
    }

    /**
     * Notify verification result
     */
    private static function notifyVerificationResult($pencairan, $confirmed, $notes)
    {
        try {
            $pengajuan = $pencairan->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => $confirmed ? 'Payment Confirmed' : 'Payment Rejected',
                'message' => $confirmed
                    ? "Payment for {$pencairan->nomor_pencairan} has been confirmed"
                    : "Payment for {$pencairan->nomor_pencairan} has been rejected",
                'type' => 'verification',
                'data' => json_encode([
                    'pencairan_id' => $pencairan->id,
                    'confirmed' => $confirmed,
                    'notes' => $notes
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify verification result', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
        }
    }

    /**
     * Notify pengaju about pencairan cancellation
     */
    private static function notifyPengajuCancellation($pencairan, $reason)
    {
        try {
            $pengajuan = $pencairan->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => 'Pencairan Cancelled',
                'message' => "Pencairan {$pencairan->nomor_pencairan} has been cancelled",
                'type' => 'cancellation',
                'data' => json_encode([
                    'pencairan_id' => $pencairan->id,
                    'reason' => $reason
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify pengaju about cancellation', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
        }
    }
}