<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPengajuanStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengajuan:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronkan status pengajuan_dana berdasarkan status approval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sinkronisasi status pengajuan dana...');

        $pengajuans = DB::table('pengajuan_danas')->get();
        $fixedCount = 0;

        foreach ($pengajuans as $pengajuan) {
            // Skip cancelled and draft status
            if ($pengajuan->status === 'cancelled' || $pengajuan->status === 'draft') {
                continue;
            }

            // Get all approvals for this pengajuan
            $approvals = DB::table('approvals')
                ->where('pengajuan_dana_id', $pengajuan->id)
                ->get();

            if ($approvals->isEmpty()) {
                continue;
            }

            $approvalStatuses = $approvals->pluck('status')->toArray();

            // Determine correct status based on approvals
            $correctStatus = null;

            // If any approval is rejected, pengajuan should be rejected
            if (in_array('ditolak', $approvalStatuses)) {
                $correctStatus = 'ditolak';
            }
            // If all approvals are approved, pengajuan should be approved
            elseif (in_array('disetujui', $approvalStatuses) &&
                    !in_array('pending', $approvalStatuses) &&
                    !in_array('ditolak', $approvalStatuses)) {
                $correctStatus = 'disetujui';
            }
            // If still has pending approvals, should be menunggu_approval
            elseif (in_array('pending', $approvalStatuses)) {
                $correctStatus = 'menunggu_approval';
            }

            // Update if status is wrong
            if ($correctStatus && $pengajuan->status !== $correctStatus) {
                $this->warn("Mengubah {$pengajuan->nomor_pengajuan}: {$pengajuan->status} -> {$correctStatus}");
                DB::table('pengajuan_danas')
                    ->where('id', $pengajuan->id)
                    ->update(['status' => $correctStatus]);
                $fixedCount++;
            }
        }

        if ($fixedCount === 0) {
            $this->info('Semua status sudah konsisten.');
        } else {
            $this->info("Selesai! {$fixedCount} pengajuan diperbarui.");
        }

        return Command::SUCCESS;
    }
}
