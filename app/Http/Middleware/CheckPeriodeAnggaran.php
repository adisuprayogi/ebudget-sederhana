<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PeriodeAnggaranService;

class CheckPeriodeAnggaran
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $action = null)
    {
        // Get current periode
        $currentPeriode = PeriodeAnggaranService::getCurrentPeriode();

        if (!$currentPeriode) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Tidak ada periode anggaran aktif',
                    'message' => 'Silakan hubungi administrator untuk mengatur periode anggaran'
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Tidak ada periode anggaran aktif. Silakan hubungi administrator.');
        }

        // Validate action for current phase if specified
        if ($action) {
            $validation = PeriodeAnggaranService::validateActionForFase($action);

            if (!$validation['valid']) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => $validation['message'],
                        'periode' => $validation['periode'],
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', $validation['message']);
            }
        }

        // Share current periode with all views
        view()->share('currentPeriode', $currentPeriode);

        return $next($request);
    }
}