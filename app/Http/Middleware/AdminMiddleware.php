<?php

namespace App\Http\Middleware;

use App\Support\AdminAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!session('kusantara_admin_logged_in')) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Akses khusus admin. Silakan login terlebih dahulu.');
        }

        $role = session('kusantara_admin_role');
        $validRoles = array_keys(config('kusantara_roles.roles', []));

        if (!in_array($role, $validRoles, true)) {
            $request->session()->forget([
                'kusantara_admin_logged_in',
                'kusantara_admin_user',
                'kusantara_admin_role',
            ]);

            return redirect()
                ->route('admin.login')
                ->with('error', 'Tamu atau pengguna umum tidak diperkenankan masuk ke area admin.');
        }

        if ($permission !== null && !AdminAccess::can($role, $permission)) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Role ' . AdminAccess::roleLabel($role) . ' tidak memiliki hak akses ke fitur "' . strtoupper($permission) . '".');
        }

        return $next($request);
    }
}