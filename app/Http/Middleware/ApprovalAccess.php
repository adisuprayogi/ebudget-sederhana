<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PengajuanDana;
use App\Models\Approval;

class ApprovalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get pengajuan_id from route parameter
        $pengajuanId = $request->route('pengajuan');

        if ($pengajuanId) {
            $pengajuan = PengajuanDana::findOrFail($pengajuanId);

            // Check if user has access to this pengajuan
            if (!$this->canAccessPengajuan($user, $pengajuan)) {
                abort(403, 'Unauthorized access to this pengajuan.');
            }
        }

        return $next($request);
    }

    /**
     * Check if user can access the pengajuan
     */
    private function canAccessPengajuan($user, $pengajuan): bool
    {
        // Direktur utama can access all
        if ($user->hasRole('direktur_utama')) {
            return true;
        }

        // Direktur keuangan can access all
        if ($user->hasRole('direktur_keuangan')) {
            return true;
        }

        // Staff keuangan can access all for processing
        if ($user->hasRole('staff_keuangan')) {
            return true;
        }

        // User who created the pengajuan can access
        if ($pengajuan->created_by === $user->id) {
            return true;
        }

        // Kepala divisi can access pengajuan from their divisi
        if ($user->hasRole('kepala_divisi') && $user->divisi_id === $pengajuan->divisi_id) {
            return true;
        }

        // Staff divisi can access pengajuan from their divisi
        if ($user->hasRole('staff_divisi') && $user->divisi_id === $pengajuan->divisi_id) {
            return true;
        }

        return false;
    }
}
