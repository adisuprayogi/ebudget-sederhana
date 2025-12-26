<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDivisi
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

        // Direktur utama, direktur keuangan, dan staff keuangan tidak memiliki divisi
        if ($user->hasRole('direktur_utama') || $user->hasRole('direktur_keuangan') || $user->hasRole('staff_keuangan')) {
            return $next($request);
        }

        // Kepala divisi dan staff divisi harus memiliki divisi
        if (!$user->divisi_id) {
            abort(403, 'User must be assigned to a division.');
        }

        return $next($request);
    }
}
