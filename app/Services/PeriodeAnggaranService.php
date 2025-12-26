<?php

namespace App\Services;

use App\Models\PeriodeAnggaran;
use App\Models\User;
use App\Models\ProgramKerja;
use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeriodeAnggaranService
{
    /**
     * Create new periode anggaran
     */
    public static function createPeriode($data)
    {
        DB::beginTransaction();
        try {
            // Generate kode periode otomatis jika tidak ada
            if (!isset($data['kode_periode'])) {
                $data['kode_periode'] = self::generateKodePeriode($data['tahun_anggaran']);
            }

            // Validate tanggal
            if (isset($data['tanggal_mulai_perencanaan_anggaran']) && isset($data['tanggal_selesai_perencanaan_anggaran'])) {
                if ($data['tanggal_mulai_perencanaan_anggaran'] >= $data['tanggal_selesai_perencanaan_anggaran']) {
                    throw new \Exception('Tanggal mulai perencanaan harus sebelum tanggal selesai perencanaan');
                }
            }

            if (isset($data['tanggal_mulai_penggunaan_anggaran']) && isset($data['tanggal_selesai_penggunaan_anggaran'])) {
                if ($data['tanggal_mulai_penggunaan_anggaran'] >= $data['tanggal_selesai_penggunaan_anggaran']) {
                    throw new \Exception('Tanggal mulai penggunaan harus sebelum tanggal selesai penggunaan');
                }

                // Penggunaan phase harus setelah atau sama dengan perencanaan
                if (isset($data['tanggal_selesai_perencanaan_anggaran']) &&
                    $data['tanggal_mulai_penggunaan_anggaran'] < $data['tanggal_selesai_perencanaan_anggaran']) {
                    throw new \Exception('Fase penggunaan harus dimulai setelah atau sama dengan fase perencanaan selesai');
                }
            }

            // Check for existing periode with same year
            $existing = PeriodeAnggaran::where('tahun_anggaran', $data['tahun_anggaran'])->exists();
            if ($existing) {
                throw new \Exception('Periode anggaran untuk tahun ' . $data['tahun_anggaran'] . ' sudah ada');
            }

            $periode = PeriodeAnggaran::create([
                'kode_periode' => $data['kode_periode'],
                'nama_periode' => $data['nama_periode'],
                'tahun_anggaran' => $data['tahun_anggaran'],
                'tanggal_mulai_perencanaan_anggaran' => $data['tanggal_mulai_perencanaan_anggaran'],
                'tanggal_selesai_perencanaan_anggaran' => $data['tanggal_selesai_perencanaan_anggaran'],
                'tanggal_mulai_penggunaan_anggaran' => $data['tanggal_mulai_penggunaan_anggaran'],
                'tanggal_selesai_penggunaan_anggaran' => $data['tanggal_selesai_penggunaan_anggaran'],
                'status' => 'draft',
                'deskripsi' => $data['deskripsi'] ?? null,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('Periode anggaran created', ['periode_id' => $periode->id, 'kode' => $periode->kode_periode]);

            return $periode;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create periode anggaran', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get active periode anggaran
     */
    public static function getActivePeriode($fase = null)
    {
        $query = PeriodeAnggaran::where('status', 'active');

        if ($fase) {
            $query->fase($fase);
        }

        return $query->orderBy('created_at', 'desc')->first();
    }

    /**
     * Get current periode (yang sedang berjalan)
     */
    public static function getCurrentPeriode()
    {
        $today = now()->startOfDay();

        return PeriodeAnggaran::where('status', 'active')
            ->where(function ($query) use ($today) {
                // Check if today is within perencanaan phase
                $query->where(function ($q) use ($today) {
                    $q->where('tanggal_mulai_perencanaan_anggaran', '<=', $today)
                        ->where('tanggal_selesai_perencanaan_anggaran', '>=', $today);
                })
                // OR within penggunaan phase
                ->orWhere(function ($q) use ($today) {
                    $q->where('tanggal_mulai_penggunaan_anggaran', '<=', $today)
                        ->where('tanggal_selesai_penggunaan_anggaran', '>=', $today);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Transition periode to new fase
     */
    public static function transitionFase($periodeId, $newFase, $approvedBy = null)
    {
        DB::beginTransaction();
        try {
            $periode = PeriodeAnggaran::findOrFail($periodeId);

            $allowedTransitions = [
                'draft' => ['active'],
                'active' => ['closed'],
                'closed' => [],
            ];

            if (!in_array($newFase, $allowedTransitions[$periode->status] ?? [])) {
                throw new \Exception("Tidak dapat transisi dari status '{$periode->status}' ke fase '{$newFase}'");
            }

            $oldFase = $periode->fase;

            // Execute specific actions for each transition
            switch ($newFase) {
                case 'active':
                    // Activate the periode
                    break;

                case 'closed':
                    self::closePeriode($periode);
                    break;
            }

            // Update periode
            $periode->update([
                'status' => $newFase,
                'approved_by' => $approvedBy?->id,
                'approved_at' => now(),
                'updated_at' => now(),
            ]);

            // Log the transition
            Log::info('Periode anggaran phase transition', [
                'periode_id' => $periode->id,
                'old_fase' => $oldFase,
                'new_status' => $newFase,
                'approved_by' => $approvedBy?->id,
            ]);

            // Send notifications if needed
            self::sendFaseTransitionNotification($periode, $oldFase, $newFase);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to transition periode fase', [
                'error' => $e->getMessage(),
                'periode_id' => $periodeId,
                'new_fase' => $newFase,
            ]);
            throw $e;
        }
    }

    /**
     * Auto transition phases based on dates
     */
    public static function autoTransitionPhases()
    {
        $today = now()->startOfDay();

        // Find periode that need to transition from perencanaan to penggunaan
        $needToPenggunaan = PeriodeAnggaran::where('status', 'active')
            ->where('tanggal_selesai_perencanaan_anggaran', '<=', $today)
            ->where('tanggal_mulai_penggunaan_anggaran', '<=', $today)
            ->where('tanggal_selesai_penggunaan_anggaran', '>=', $today)
            ->get();

        foreach ($needToPenggunaan as $periode) {
            // Transition to penggunaan phase - this is automatic based on dates
            self::prepareFasePenggunaan($periode);
        }

        // Find periode that need to transition from penggunaan to closed
        $needToClose = PeriodeAnggaran::where('status', 'active')
            ->where('tanggal_selesai_penggunaan_anggaran', '<', $today)
            ->get();

        foreach ($needToClose as $periode) {
            self::transitionFase($periode->id, 'closed');
        }
    }

    /**
     * Get all periodes with statistics
     */
    public static function getAllPeriodesWithStats()
    {
        return PeriodeAnggaran::with(['createdBy'])
            ->withCount([
                'programKerjas',
                'pengajuanDan',
                'pencairanDan',
                'laporanPertanggungJawaban'
            ])
            ->orderBy('tahun_anggaran', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($periode) {
                $periode->statistics = $periode->getStatistics();
                return $periode;
            });
    }

    /**
     * Get periode options for dropdown
     */
    public static function getPeriodeOptions($status = null)
    {
        $query = PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($periode) {
            return [
                'id' => $periode->id,
                'kode' => $periode->kode_periode,
                'nama' => $periode->nama_periode,
                'tahun' => $periode->tahun_anggaran,
                'fase' => $periode->fase,
                'status' => $periode->status,
                'is_active' => $periode->is_active,
                'tanggal_mulai_perencanaan' => $periode->tanggal_mulai_perencanaan_anggaran,
                'tanggal_selesai_perencanaan' => $periode->tanggal_selesai_perencanaan_anggaran,
                'tanggal_mulai_penggunaan' => $periode->tanggal_mulai_penggunaan_anggaran,
                'tanggal_selesai_penggunaan' => $periode->tanggal_selesai_penggunaan_anggaran,
            ];
        });
    }

    /**
     * Validate if action can be performed in current fase
     */
    public static function validateActionForFase($action, $periodeId = null)
    {
        $periode = $periodeId ? PeriodeAnggaran::find($periodeId) : self::getCurrentPeriode();

        if (!$periode) {
            return ['valid' => false, 'message' => 'Tidak ada periode anggaran aktif'];
        }

        $fasePermissions = [
            'perencangan' => [
                'perencanaan_penerimaan' => true,
                'penetapan_pagu' => true,
                'program_kerja' => true,
                'pengajuan_dana' => false,
                'pencairan_dana' => false,
            ],
            'penggunaan' => [
                'perencanaan_penerimaan' => true, // Still can record
                'penetapan_pagu' => true, // Still can view
                'program_kerja' => true,
                'pengajuan_dana' => true,
                'pencairan_dana' => true,
            ],
            'closed' => [
                'perencanaan_penerimaan' => false,
                'penetapan_pagu' => false,
                'program_kerja' => false,
                'pengajuan_dana' => false,
                'pencairan_dana' => false,
            ],
        ];

        $allowed = $fasePermissions[$periode->fase][$action] ?? false;

        return [
            'valid' => $allowed,
            'message' => $allowed ? null : "Action '{$action}' tidak diizinkan pada fase '{$periode->nama_fase}'",
            'periode' => $periode,
        ];
    }

    /**
     * Generate kode periode
     */
    private static function generateKodePeriode($tahun)
    {
        $prefix = 'PA' . $tahun;

        $lastCode = PeriodeAnggaran::where('kode_periode', 'like', $prefix . '%')
            ->orderBy('kode_periode', 'desc')
            ->value('kode_periode');

        if ($lastCode) {
            $sequence = (int) substr($lastCode, -2) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Prepare fase penggunaan
     */
    private static function prepareFasePenggunaan($periode)
    {
        // Activate approved program kerja
        ProgramKerja::where('periode_anggaran_id', $periode->id)
            ->whereIn('status', ['draft', 'proposed', 'approved'])
            ->update(['status' => 'active']);

        Log::info('Fase penggunaan prepared', ['periode_id' => $periode->id]);
    }

    /**
     * Close periode
     */
    private static function closePeriode($periode)
    {
        // Cancel all pending operations
        PengajuanDana::where('periode_anggaran_id', $periode->id)
            ->whereIn('status', ['draft', 'menunggu_approval'])
            ->update(['status' => 'cancelled']);

        PencairanDana::where('periode_anggaran_id', $periode->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        // Complete ongoing programs
        ProgramKerja::where('periode_anggaran_id', $periode->id)
            ->where('status', 'in_progress')
            ->update(['status' => 'completed']);

        Log::info('Periode closed', ['periode_id' => $periode->id]);
    }

    /**
     * Send notification for fase transition
     */
    private static function sendFaseTransitionNotification($periode, $oldFase, $newFase)
    {
        try {
            // Send notification to relevant users
            $users = User::where('is_active', true)->get();

            foreach ($users as $user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Transisi Fase Periode Anggaran',
                    'message' => "Periode {$periode->nama_periode} telah beralih dari fase {$oldFase} ke fase {$newFase}",
                    'type' => 'periode_anggaran',
                    'data' => json_encode([
                        'periode_id' => $periode->id,
                        'old_fase' => $oldFase,
                        'new_fase' => $newFase,
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send fase transition notification', [
                'error' => $e->getMessage(),
                'periode_id' => $periode->id,
            ]);
        }
    }

    /**
     * Get periode summary for dashboard
     */
    public static function getDashboardSummary()
    {
        $currentPeriode = self::getCurrentPeriode();
        $upcomingTransition = self::getUpcomingPhaseTransition();

        return [
            'current_periode' => $currentPeriode ? [
                'id' => $currentPeriode->id,
                'nama' => $currentPeriode->nama_periode,
                'kode' => $currentPeriode->kode_periode,
                'tahun' => $currentPeriode->tahun_anggaran,
                'fase' => $currentPeriode->fase,
                'nama_fase' => $currentPeriode->nama_fase,
                'progress_percentage' => $currentPeriode->progress_percentage,
                'days_remaining' => $currentPeriode->days_remaining,
                'is_active' => $currentPeriode->is_active,
                'warnings' => $currentPeriode->getWarnings(),
                'statistics' => $currentPeriode->getStatistics(),
            ] : null,
            'upcoming_transition' => $upcomingTransition,
            'total_periodes' => PeriodeAnggaran::count(),
            'active_periodes' => PeriodeAnggaran::where('status', 'active')->count(),
        ];
    }

    /**
     * Get upcoming phase transition
     */
    private static function getUpcomingPhaseTransition()
    {
        $today = now()->startOfDay();

        // Check for transitions in next 7 days
        $periodes = PeriodeAnggaran::where('status', 'active')
            ->where('fase', '!=', 'closed')
            ->get();

        foreach ($periodes as $periode) {
            $warnings = $periode->getWarnings();
            if (!empty($warnings)) {
                return [
                    'periode' => $periode,
                    'warnings' => $warnings,
                ];
            }
        }

        return null;
    }
}
