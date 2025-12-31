<?php

namespace App\Services;

use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundService
{
    /**
     * Create a new refund.
     */
    public static function createRefund(array $data, $file = null): Refund
    {
        DB::beginTransaction();
        try {
            // Handle file upload
            if ($file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti-transfer-refund', $fileName, 'public');
                $data['bukti_transfer'] = $filePath;
            }

            // Set default status to draft
            $data['status'] = $data['status'] ?? 'draft';

            $refund = Refund::create($data);

            DB::commit();

            return $refund;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing refund.
     */
    public static function updateRefund(Refund $refund, array $data, $file = null): Refund
    {
        DB::beginTransaction();
        try {
            // Handle file upload
            if ($file) {
                // Delete old file if exists
                if ($refund->bukti_transfer) {
                    Storage::disk('public')->delete($refund->bukti_transfer);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti-transfer-refund', $fileName, 'public');
                $data['bukti_transfer'] = $filePath;
            }

            $refund->update($data);

            DB::commit();

            return $refund->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Submit refund for approval.
     */
    public static function submitRefund(int $refundId): Refund
    {
        $refund = Refund::findOrFail($refundId);

        if ($refund->status !== 'draft') {
            throw new \Exception('Hanya refund dengan status draft yang dapat diajukan.');
        }

        $refund->update(['status' => 'menunggu_approval']);

        return $refund->refresh();
    }

    /**
     * Approve/Reject a refund.
     * When approved, status becomes 'processed' (selesai) directly.
     */
    public static function approveRefund(int $refundId, string $status, ?string $catatan, $user): Refund
    {
        $refund = Refund::findOrFail($refundId);

        if ($refund->status !== 'menunggu_approval') {
            throw new \Exception('Hanya refund dengan status menunggu_approval yang dapat diproses.');
        }

        if ($status === 'approved') {
            $refund->update([
                'status' => 'processed',
                'approved_at' => now(),
                'approved_by' => $user->id,
                'processed_at' => now(),
                'catatan_approval' => $catatan,
            ]);
        } elseif ($status === 'rejected') {
            $refund->update([
                'status' => 'rejected',
                'catatan_approval' => $catatan,
            ]);
        }

        return $refund->refresh();
    }

    /**
     * Process a refund (mark as transferred).
     */
    public static function processRefund(int $refundId, string $tanggalTransfer, $file = null): Refund
    {
        DB::beginTransaction();
        try {
            $refund = Refund::findOrFail($refundId);

            if ($refund->status !== 'approved') {
                throw new \Exception('Hanya refund dengan status approved yang dapat diproses.');
            }

            // Handle file upload - replace old bukti_transfer with new one
            if ($file) {
                // Delete old file if exists
                if ($refund->bukti_transfer) {
                    Storage::disk('public')->delete($refund->bukti_transfer);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bukti-transfer-refund', $fileName, 'public');
                $refund->bukti_transfer = $filePath;
            }

            $refund->update([
                'status' => 'processed',
                'tanggal_transfer' => $tanggalTransfer,
                'processed_at' => now(),
            ]);

            DB::commit();

            return $refund->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
