<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\DetailPencairan;
use App\Models\DetailPengajuan;
use App\Models\User;
use App\Models\Notification;
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
            if ($pengajuan->status !== 'menunggu_pencairan') {
                throw new \Exception('Pengajuan must be approved (menunggu_pencairan) before pencairan');
            }

            // Check if active (non-cancelled) pencairan already exists
            if ($pengajuan->activePencairan) {
                throw new \Exception('Pengajuan ini sudah memiliki pencairan aktif');
            }

            // Generate nomor pencairan
            $nomorPencairan = NumberingService::generateNomorPencairan();

            $isHonorarium = $pengajuan->jenis_pengajuan === 'honorarium';

            // Prepare pencairan data
            $pencairanData = [
                'pengajuan_dana_id' => $pengajuan->id,
                'nomor_pencairan' => $nomorPencairan,
                'tanggal_pencairan' => $data['tanggal_pencairan'] ?? now()->format('Y-m-d'),
                'jumlah_pencairan' => $data['jumlah_pencairan'] ?? $pengajuan->total_pengajuan,
                'catatan' => $data['catatan'] ?? null,
                'status' => 'menunggu',
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!$isHonorarium) {
                // Non-honorarium: include bank details
                // Get rekening perusahaan details if provided
                $rekeningPerusahaan = null;
                if (isset($data['rekening_perusahaan_id'])) {
                    $rekeningPerusahaan = \App\Models\RekeningPerusahaan::with('bank')->find($data['rekening_perusahaan_id']);
                }

                // Get bank details if bank_id is provided
                $namaBank = null;
                if (isset($data['bank_id'])) {
                    $bank = \App\Models\Bank::find($data['bank_id']);
                    $namaBank = $bank ? $bank->nama_bank : ($data['nama_bank'] ?? null);
                } else {
                    $namaBank = $data['nama_bank'] ?? null;
                }

                $pencairanData['rekening_perusahaan_id'] = $data['rekening_perusahaan_id'] ?? null;
                $pencairanData['metode_pencairan'] = $data['metode_pencairan'] ?? 'transfer';
                $pencairanData['nama_bank'] = $namaBank;
                $pencairanData['nomor_rekening'] = $data['nomor_rekening'] ?? null;
                $pencairanData['atas_nama'] = $data['atas_nama'] ?? null;
                $pencairanData['nama_bank_sumber'] = $rekeningPerusahaan ? $rekeningPerusahaan->bank->nama_bank : ($data['nama_bank_sumber'] ?? null);
                $pencairanData['nomor_rekening_sumber'] = $rekeningPerusahaan ? $rekeningPerusahaan->nomor_rekening : ($data['nomor_rekening_sumber'] ?? null);
            } else {
                // Honorarium: mark as transfer (default method)
                $pencairanData['metode_pencairan'] = 'transfer';
                $pencairanData['rekening_perusahaan_id'] = $data['rekening_perusahaan_id'] ?? null;
            }

            // Create pencairan dana
            $pencairan = PencairanDana::create($pencairanData);

            // Create detail pencairan
            if ($isHonorarium && isset($data['honorarium_ids'])) {
                // For honorarium: create detail for each selected honorarium
                $honorariumDetails = \App\Models\HonorariumDetail::whereIn('id', $data['honorarium_ids'])->get();

                foreach ($honorariumDetails as $honorarium) {
                    DetailPencairan::create([
                        'pencairan_dana_id' => $pencairan->id,
                        'honorarium_detail_id' => $honorarium->id,
                        'uraian' => 'Honorarium - ' . $honorarium->penerima_nama . ' (' . $honorarium->jabatan . ')',
                        'volume' => 1,
                        'satuan' => 'orang',
                        'harga_satuan' => $honorarium->jumlah_honor,
                        'subtotal' => $honorarium->jumlah_honor,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // For non-honorarium: create detail from detail pengajuan
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
            }

            // Update pengajuan status to cair (dana sudah dicairkan, menunggu konfirmasi pengaju)
            $pengajuan->update([
                'status' => 'cair',
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
     * Verify pencairan (pengaju confirms/rejects receiving funds)
     */
    public static function verifyPencairan(PencairanDana $pencairan, $confirmed = true, $notes = null)
    {
        DB::beginTransaction();
        try {
            $pengajuan = $pencairan->pengajuanDana;

            // Only allow verification when status is 'menunggu' or 'pending'
            if (!in_array($pencairan->status, ['menunggu', 'pending'])) {
                throw new \Exception('Pencairan must be in menunggu or pending status');
            }

            // Only allow pengaju (creator) to verify
            if ($pengajuan->created_by !== auth()->id()) {
                throw new \Exception('Only pengaju can verify pencairan');
            }

            // All types go directly to selesai after pengaju verification
            if ($confirmed) {
                // Pengaju confirms receiving funds - mark as selesai
                $pencairan->update([
                    'status' => 'selesai',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                    'verification_notes' => $notes,
                    'updated_at' => now(),
                ]);

                $pengajuan->update([
                    'status' => 'selesai',
                    'updated_at' => now(),
                ]);
            } else {
                // Pengaju rejects - needs to be re-disbursed
                $pencairan->update([
                    'status' => 'revisi',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                    'verification_notes' => $notes,
                    'updated_at' => now(),
                ]);

                // Return pengajuan to menunggu_pencairan for re-disbursement
                $pengajuan->update([
                    'status' => 'menunggu_pencairan',
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // Send notification
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
            // Validate pencairan status - cannot cancel if already selesai or cancelled
            if ($pencairan->status === 'selesai' || $pencairan->status === 'cancelled') {
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

            // Update pengajuan status back to menunggu_pencairan
            $pengajuan = $pencairan->pengajuanDana;
            $pengajuan->update([
                'status' => 'menunggu_pencairan',
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
    public static function getPencairanStatistics($periodeAnggaranId = null, $divisiId = null)
    {
        $query = PencairanDana::with(['pengajuanDana.divisi', 'pengajuanDana.createdBy']);

        // Apply periode anggaran filter through pengajuanDana -> program_kerja / sub_program
        if ($periodeAnggaranId) {
            $query->whereHas('pengajuanDana', function ($q) use ($periodeAnggaranId) {
                $q->where(function ($subQ) use ($periodeAnggaranId) {
                    $subQ->whereHas('programKerja', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    })->orWhereHas('subProgram', function ($ss) use ($periodeAnggaranId) {
                        $ss->where('periode_anggaran_id', $periodeAnggaranId);
                    });
                });
            });
        }

        if ($divisiId) {
            $query->whereHas('pengajuanDana', function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId);
            });
        }

        $pencairans = $query->get();

        return [
            'total_pencairan' => $pencairans->count(),
            'total_nominal' => $pencairans->sum('jumlah_pencairan'),
            'by_status' => $pencairans->groupBy('status')->map->count(),
            'by_month' => $pencairans->groupBy(function ($item) {
                return $item->tanggal_pencairan->format('Y-m');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('jumlah_pencairan')
                ];
            }),
            'by_divisi' => $pencairans->groupBy(function ($item) {
                return $item->pengajuanDana->divisi->nama_divisi ?? 'Unknown';
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('jumlah_pencairan')
                ];
            }),
            'average_pencairan' => $pencairans->avg('jumlah_pencairan'),
            'max_pencairan' => $pencairans->max('jumlah_pencairan'),
            'min_pencairan' => $pencairans->min('jumlah_pencairan'),
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
        if ($pengajuan->status !== 'menunggu_pencairan') {
            return false;
        }

        // Check if active (non-cancelled) pencairan already exists
        if ($pengajuan->activePencairan) {
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
                Notification::create([
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

            Notification::create([
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
            Notification::create([
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

            Notification::create([
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

            Notification::create([
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

    /**
     * Retry pencairan (create new pencairan from rejected one)
     */
    public static function retryPencairan(PencairanDana $pencairan, $data = [])
    {
        DB::beginTransaction();
        try {
            // Validate pencairan status - only allow retry for 'revisi' status
            if ($pencairan->status !== 'revisi') {
                throw new \Exception('Only pencairan with revisi status can be retried');
            }

            $pengajuan = $pencairan->pengajuanDana;

            // Validate pengajuan status
            if ($pengajuan->status !== 'menunggu_pencairan') {
                throw new \Exception('Pengajuan status must be menunggu_pencairan');
            }

            // Generate new nomor pencairan
            $nomorPencairan = NumberingService::generateNomorPencairan();

            $isHonorarium = $pengajuan->jenis_pengajuan === 'honorarium';

            // Prepare new pencairan data - use provided data or fallback to old data
            $newPencairanData = [
                'pengajuan_dana_id' => $pengajuan->id,
                'nomor_pencairan' => $nomorPencairan,
                'tanggal_pencairan' => $data['tanggal_pencairan'] ?? $pencairan->tanggal_pencairan->format('Y-m-d'),
                'jumlah_pencairan' => $data['jumlah_pencairan'] ?? $pencairan->jumlah_pencairan,
                'catatan' => $data['catatan'] ?? $pencairan->catatan,
                'status' => 'menunggu',
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!$isHonorarium) {
                // Get rekening perusahaan details if provided
                $rekeningPerusahaan = null;
                if (isset($data['rekening_perusahaan_id'])) {
                    $rekeningPerusahaan = \App\Models\RekeningPerusahaan::with('bank')->find($data['rekening_perusahaan_id']);
                }

                // Get bank details if bank_id is provided
                $namaBank = null;
                if (isset($data['bank_id'])) {
                    $bank = \App\Models\Bank::find($data['bank_id']);
                    $namaBank = $bank ? $bank->nama_bank : ($data['nama_bank'] ?? null);
                } else {
                    $namaBank = $data['nama_bank'] ?? $pencairan->nama_bank;
                }

                $newPencairanData['rekening_perusahaan_id'] = $data['rekening_perusahaan_id'] ?? $pencairan->rekening_perusahaan_id;
                $newPencairanData['metode_pencairan'] = $data['metode_pencairan'] ?? $pencairan->metode_pencairan;
                $newPencairanData['nama_bank'] = $namaBank;
                $newPencairanData['nomor_rekening'] = $data['nomor_rekening'] ?? $pencairan->nomor_rekening;
                $newPencairanData['atas_nama'] = $data['atas_nama'] ?? $pencairan->atas_nama;
                $newPencairanData['nama_bank_sumber'] = $rekeningPerusahaan ? $rekeningPerusahaan->bank->nama_bank : ($pencairan->nama_bank_sumber ?? null);
                $newPencairanData['nomor_rekening_sumber'] = $rekeningPerusahaan ? $rekeningPerusahaan->nomor_rekening : ($pencairan->nomor_rekening_sumber ?? null);
            } else {
                // Honorarium: use provided data or fallback to old data
                $newPencairanData['metode_pencairan'] = $data['metode_pencairan'] ?? $pencairan->metode_pencairan;
                $newPencairanData['rekening_perusahaan_id'] = $data['rekening_perusahaan_id'] ?? $pencairan->rekening_perusahaan_id;
            }

            // Create new pencairan
            $newPencairan = PencairanDana::create($newPencairanData);

            // Copy/create detail pencairan based on selected honorarium_ids or copy all
            if ($isHonorarium && isset($data['honorarium_ids'])) {
                // For honorarium: create detail for each selected honorarium
                $honorariumDetails = \App\Models\HonorariumDetail::whereIn('id', $data['honorarium_ids'])->get();

                foreach ($honorariumDetails as $honorarium) {
                    DetailPencairan::create([
                        'pencairan_dana_id' => $newPencairan->id,
                        'honorarium_detail_id' => $honorarium->id,
                        'uraian' => 'Honorarium - ' . $honorarium->penerima_nama . ' (' . $honorarium->jabatan . ')',
                        'volume' => 1,
                        'satuan' => 'orang',
                        'harga_satuan' => $honorarium->jumlah_honor,
                        'subtotal' => $honorarium->jumlah_honor,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Copy detail pencairan from old one
                foreach ($pencairan->detailPencairans as $detail) {
                    DetailPencairan::create([
                        'pencairan_dana_id' => $newPencairan->id,
                        'detail_pengajuan_id' => $detail->detail_pengajuan_id,
                        'honorarium_detail_id' => $detail->honorarium_detail_id,
                        'uraian' => $detail->uraian,
                        'volume' => $detail->volume,
                        'satuan' => $detail->satuan,
                        'harga_satuan' => $detail->harga_satuan,
                        'subtotal' => $detail->subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update old pencairan status to cancelled
            $pencairan->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => 'Dibatalkan karena pencairan ulang (retry) dengan nomor: ' . $nomorPencairan,
                'updated_at' => now(),
            ]);

            // Update pengajuan status
            $pengajuan->update([
                'status' => 'cair',
                'updated_at' => now(),
            ]);

            DB::commit();

            // Send notification
            self::notifyStaffKeuangan($newPencairan);

            return $newPencairan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to retry pencairan', [
                'error' => $e->getMessage(),
                'pencairan_id' => $pencairan->id
            ]);
            throw $e;
        }
    }
}