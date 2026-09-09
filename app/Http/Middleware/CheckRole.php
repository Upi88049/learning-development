<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan session user sudah login
        if (!session()->has('user')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = session('role');

        // Parse semua role yang diizinkan (mendukung pemisah koma dan argumen variadik)
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $part) {
                $trimmed = trim($part);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Periksa apakah role user saat ini diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            // Jika request berupa AJAX / JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.',
                ], 403);
            }

            // Redirect ke dashboard masing-masing role dengan pesan peringatan
            if ($userRole === 'DLC') {
                return redirect()->route('dashboarddlc')->with('error', 'Akses ditolak! Halaman tersebut khusus untuk Immediate Manager.');
            } elseif ($userRole === 'Immediate Manager') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak! Halaman tersebut khusus untuk DLC (Super Admin).');
            }

            return redirect()->route('login')->with('error', 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}