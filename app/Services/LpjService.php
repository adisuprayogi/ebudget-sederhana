<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\DetailLpj;
use App\Models\DetailPencairan;
use App\Models\User;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LpjService
{
    /**
     * Create LPJ from pencairan
     */
    public static function createLpj(PencairanDana $pencairan, $data = [])
    {
        DB::beginTransaction();
        try {
            // Validate pencairan status
            if ($pencairan->status !== 'completed' && $pencairan->status !== 'processed') {
                throw new \Exception('Pencairan must be processed before creating LPJ');
            }

            $pengajuan = $pencairan->pengajuanDana;

            // Skip LPJ for pembayaran type
            if ($pengajuan->jenis_pengajuan === 'pembayaran') {
                throw new \Exception('LPJ not required for pembayaran type');
            }

            // Check if LPJ already exists
            if ($pencairan->laporanPertanggungJawaban) {
                throw new \Exception('LPJ already exists for this pencairan');
            }

            // Generate nomor LPJ
            $nomorLpj = NumberingService::generateNomorLPJ();

            // Create LPJ
            $lpj = LaporanPertanggungJawaban::create([
                'pengajuan_dana_id' => $pengajuan->id,
                'pencairan_dana_id' => $pencairan->id,
                'nomor_lpj' => $nomorLpj,
                'tanggal_lpj' => $data['tanggal_lpj'] ?? now()->format('Y-m-d'),
                'uraian_kegiatan' => $data['uraian_kegiatan'] ?? $pengajuan->judul_pengajuan,
                'total_digunakan' => $data['total_digunakan'] ?? 0,
                'sisa_dana' => $data['sisa_dana'] ?? $pencairan->total_pencairan,
                'status' => 'draft',
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create detail LPJ based on detail pencairan
            $detailPencairans = DetailPencairan::where('pencairan_dana_id', $pencairan->id)->get();

            foreach ($detailPencairans as $detailPencairan) {
                DetailLpj::create([
                    'lpj_id' => $lpj->id,
                    'detail_pencairan_id' => $detailPencairan->id,
                    'uraian' => $detailPencairan->uraian,
                    'volume_realisasi' => $detailPencairan->volume,
                    'satuan' => $detailPencairan->satuan,
                    'harga_satuan' => $detailPencairan->harga_satuan,
                    'subtotal_realisasi' => $detailPencairan->subtotal,
                    'keterangan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update pengajuan status
            $pengajuan->update([
                'status' => 'lpj_dibuat',
                'updated_at' => now(),
            ]);

            DB::commit();

            return $lpj;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create LPJ', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            throw $e;
        }
    }

    /**
     * Update LPJ details and calculations
     */
    public static function updateLpj(LaporanPertanggungJawaban $lpj, $data)
    {
        DB::beginTransaction();
        try {
            // Update LPJ main data
            $lpj->update([
                'tanggal_lpj' => $data['tanggal_lpj'] ?? $lpj->tanggal_lpj,
                'uraian_kegiatan' => $data['uraian_kegiatan'] ?? $lpj->uraian_kegiatan,
                'updated_at' => now(),
            ]);

            // Update detail LPJ if provided
            if (isset($data['details']) && is_array($data['details'])) {
                foreach ($data['details'] as $detailData) {
                    if (isset($detailData['id'])) {
                        // Update existing detail
                        $detail = DetailLpj::find($detailData['id']);
                        if ($detail && $detail->lpj_id === $lpj->id) {
                            $detail->update([
                                'volume_realisasi' => $detailData['volume_realisasi'] ?? $detail->volume_realisasi,
                                'harga_satuan' => $detailData['harga_satuan'] ?? $detail->harga_satuan,
                                'subtotal_realisasi' => $detailData['subtotal_realisasi'] ?? $detail->subtotal_realisasi,
                                'keterangan' => $detailData['keterangan'] ?? $detail->keterangan,
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // Recalculate totals
            self::recalculateLpjTotals($lpj);

            DB::commit();

            return $lpj;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update LPJ', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            throw $e;
        }
    }

    /**
     * Submit LPJ for approval
     */
    public static function submitLpj(LaporanPertanggungJawaban $lpj, $data = [])
    {
        DB::beginTransaction();
        try {
            // Validate LPJ status
            if ($lpj->status !== 'draft') {
                throw new \Exception('LPJ must be in draft status to submit');
            }

            // Validate required fields
            if (!$lpj->total_digunakan || $lpj->total_digunakan <= 0) {
                throw new \Exception('Total used amount must be greater than 0');
            }

            // Update LPJ status
            $lpj->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'catatan' => $data['catatan'] ?? null,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan = $lpj->pengajuanDana;
            $pengajuan->update([
                'status' => 'lpj_submitted',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notifications
            self::notifyStaffKeuangan($lpj);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit LPJ', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            throw $e;
        }
    }

    /**
     * Approve LPJ
     */
    public static function approveLpj(LaporanPertanggungJawaban $lpj, $notes = null)
    {
        DB::beginTransaction();
        try {
            // Validate LPJ status
            if ($lpj->status !== 'submitted') {
                throw new \Exception('LPJ must be submitted to approve');
            }

            // Update LPJ status
            $lpj->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'approval_notes' => $notes,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan = $lpj->pengajuanDana;
            $pengajuan->update([
                'status' => 'lpj_approved',
                'updated_at' => now(),
            ]);

            // If there's sisa dana, suggest refund
            if ($lpj->sisa_dana > 0) {
                self::suggestRefund($lpj);
            } else {
                // Close pengajuan if no sisa dana
                $pengajuan->update([
                    'status' => 'selesai',
                    'selesai_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // Send notifications
            self::notifyPengajuApproval($lpj);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve LPJ', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            throw $e;
        }
    }

    /**
     * Reject LPJ
     */
    public static function rejectLpj(LaporanPertanggungJawaban $lpj, $reason)
    {
        DB::beginTransaction();
        try {
            // Validate LPJ status
            if ($lpj->status !== 'submitted') {
                throw new \Exception('LPJ must be submitted to reject');
            }

            // Update LPJ status
            $lpj->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $reason,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan = $lpj->pengajuanDana;
            $pengajuan->update([
                'status' => 'lpj_rejected',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notifications
            self::notifyPengajuRejection($lpj);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject LPJ', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            throw $e;
        }
    }

    /**
     * Add attachments to LPJ
     */
    public static function addAttachments(LaporanPertanggungJawaban $lpj, $files)
    {
        try {
            $attachments = [];

            foreach ($files as $file) {
                if ($file->isValid()) {
                    $path = $file->store('lpj-attachments', 'public');

                    $attachments[] = [
                        'lpj_id' => $lpj->id,
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($attachments)) {
                // Assuming there's an lpj_attachments table
                // DB::table('lpj_attachments')->insert($attachments);
            }

            return $attachments;
        } catch (\Exception $e) {
            Log::error('Failed to add LPJ attachments', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
            throw $e;
        }
    }

    /**
     * Get LPJ statistics
     */
    public static function getLpjStatistics($startDate = null, $endDate = null, $divisiId = null)
    {
        $query = LaporanPertanggungJawaban::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy']);

        if ($startDate) {
            $query->whereDate('tanggal_lpj', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_lpj', '<=', $endDate);
        }

        if ($divisiId) {
            $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        $lpjs = $query->get();

        return [
            'total_lpj' => $lpjs->count(),
            'total_digunakan' => $lpjs->sum('total_digunakan'),
            'total_sisa' => $lpjs->sum('sisa_dana'),
            'by_status' => $lpjs->groupBy('status')->map->count(),
            'by_month' => $lpjs->groupBy(function ($item) {
                return $item->tanggal_lpj->format('Y-m');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_digunakan' => $group->sum('total_digunakan'),
                    'total_sisa' => $group->sum('sisa_dana')
                ];
            }),
            'by_divisi' => $lpjs->groupBy(function ($item) {
                return $item->pengajuanDana->divisi->nama_divisi ?? 'Unknown';
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_digunakan' => $group->sum('total_digunakan'),
                    'total_sisa' => $group->sum('sisa_dana')
                ];
            }),
            'efficiency_rate' => $lpjs->sum('total_digunakan') > 0
                ? (($lpjs->sum('total_digunakan') / ($lpjs->sum('total_digunakan') + $lpjs->sum('sisa_dana'))) * 100)
                : 0,
        ];
    }

    /**
     * Get overdue LPJ list
     */
    public static function getOverdueLpj($days = 30)
    {
        return LaporanPertanggungJawaban::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy'])
            ->whereDoesntHave('laporanPertanggungJawaban')
            ->whereHas('pengajuanDana', function ($query) use ($days) {
                $query->where('status', 'dicairkan')
                    ->where('dicairkan_at', '<', now()->subDays($days));
            })
            ->orderBy('pengajuanDana.dicairkan_at', 'asc')
            ->get();
    }

    /**
     * Export LPJ data
     */
    public static function exportLpj($startDate = null, $endDate = null, $divisiId = null)
    {
        $query = LaporanPertanggungJawaban::with([
            'pengajuanDana.divisi',
            'pengajuanDana.createdBy',
            'pencairanDana',
            'detailLpjs'
        ]);

        if ($startDate) {
            $query->whereDate('tanggal_lpj', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_lpj', '<=', $endDate);
        }

        if ($divisiId) {
            $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        return $query->orderBy('tanggal_lpj', 'desc')->get();
    }

    /**
     * Recalculate LPJ totals
     */
    private static function recalculateLpjTotals($lpj)
    {
        $totalDigunakan = $lpj->detailLpjs->sum('subtotal_realisasi');
        $pencairan = $lpj->pencairanDana;
        $totalPencairan = $pencairan ? $pencairan->total_pencairan : 0;
        $sisaDana = $totalPencairan - $totalDigunakan;

        $lpj->update([
            'total_digunakan' => $totalDigunakan,
            'sisa_dana' => max(0, $sisaDana),
            'updated_at' => now(),
        ]);
    }

    /**
     * Suggest refund for sisa dana
     */
    private static function suggestRefund($lpj)
    {
        try {
            $pengajuan = $lpj->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => 'Refund Suggested',
                'message' => "Sisa dana from LPJ {$lpj->nomor_lpj}: " . format_currency($lpj->sisa_dana),
                'type' => 'refund_suggestion',
                'data' => json_encode([
                    'lpj_id' => $lpj->id,
                    'sisa_dana' => $lpj->sisa_dana
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to suggest refund', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
        }
    }

    /**
     * Notify staff keuangan about LPJ submission
     */
    private static function notifyStaffKeuangan($lpj)
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
                    'title' => 'LPJ Submitted',
                    'message' => "New LPJ submitted: {$lpj->nomor_lpj}",
                    'type' => 'lpj',
                    'data' => json_encode(['lpj_id' => $lpj->id]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify staff keuangan', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
        }
    }

    /**
     * Notify pengaju about LPJ approval
     */
    private static function notifyPengajuApproval($lpj)
    {
        try {
            $pengajuan = $lpj->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => 'LPJ Approved',
                'message' => "Your LPJ {$lpj->nomor_lpj} has been approved",
                'type' => 'lpj',
                'data' => json_encode(['lpj_id' => $lpj->id]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify pengaju about LPJ approval', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
        }
    }

    /**
     * Notify pengaju about LPJ rejection
     */
    private static function notifyPengajuRejection($lpj)
    {
        try {
            $pengajuan = $lpj->pengajuanDana;
            $pengaju = $pengajuan->createdBy;

            if (!$pengaju) {
                return;
            }

            Notifications::create([
                'user_id' => $pengaju->id,
                'title' => 'LPJ Rejected',
                'message' => "Your LPJ {$lpj->nomor_lpj} has been rejected",
                'type' => 'lpj',
                'data' => json_encode([
                    'lpj_id' => $lpj->id,
                    'rejection_reason' => $lpj->rejection_reason
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify pengaju about LPJ rejection', [
                'error' => $e->getMessage(),
                'lpj_id' => $lpj->id
            ]);
        }
    }
}