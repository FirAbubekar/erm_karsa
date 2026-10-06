<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\PermissionService;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // 1. Cek status login
        if (!Session::get('is_logged_in')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect('/');
        }

        // 2. Pastikan daftar permissions tersimpan di session
        $userPermissions = Session::get('permissions');
        if ($userPermissions === null && Session::get('user_id')) {
            $userPermissions = PermissionService::getPermissions(Session::get('user_id'));
            Session::put('permissions', $userPermissions);
        }

        $userPermissions = is_array($userPermissions) ? $userPermissions : [];

        // Jika rute tidak menspesifikasikan permission khusus, lanjutkan
        if (empty($permissions)) {
            return $next($request);
        }

        // 3. Cek apakah user memiliki salah satu dari permission yang dibutuhkan
        $hasAccess = false;
        foreach ($permissions as $permission) {
            $perm = trim($permission);
            if (in_array($perm, $userPermissions, true)) {
                $hasAccess = true;
                break;
            }
        }

        // 4. Jika tidak memiliki akses, tolak akses dan jangan izinkan bypass melalui URL
        if (!$hasAccess) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Forbidden',
                    'message' => 'Anda tidak memiliki hak akses untuk fitur ini.'
                ], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
        }

        return $next($request);
    }
}
