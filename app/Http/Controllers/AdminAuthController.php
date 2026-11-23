<?php

namespace App\Http\Controllers;

use App\Support\AdminAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('kusantara_admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $emailInput = strtolower(trim($credentials['email']));
        $passwordInput = $credentials['password'];
        $validRoles = array_keys(config('kusantara_roles.roles', []));

        if (Schema::hasTable('users')) {
            $user = DB::table('users')->whereRaw('LOWER(email) = ?', [$emailInput])->first();

            if ($user) {
                if (!Hash::check($passwordInput, $user->password)) {
                    return back()
                        ->withInput($request->only('email'))
                        ->with('error', 'Email atau kata sandi tidak sesuai.');
                }

                $isActive = isset($user->is_active) ? (bool) $user->is_active : true;
                if (!$isActive) {
                    return back()
                        ->withInput($request->only('email'))
                        ->with('error', 'Akun ini sedang dinonaktifkan.');
                }

                $resolvedRole = $this->resolveUserRole($user, $validRoles);

                if (!in_array($resolvedRole, $validRoles, true)) {
                    return back()
                        ->withInput($request->only('email'))
                        ->with('error', 'Login khusus admin/staf terdaftar. Pelanggan atau tamu tidak diperkenankan login.');
                }

                $request->session()->regenerate();
                session([
                    'kusantara_admin_logged_in' => true,
                    'kusantara_admin_user'      => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                        'role'  => $resolvedRole,
                    ],
                    'kusantara_admin_role'      => $resolvedRole,
                ]);

                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->name . ' (' . AdminAccess::roleLabel($resolvedRole) . ').');
            }
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Akun tidak ditemukan di database.');
    }

    protected function resolveUserRole(object $user, array $validRoles): string
    {
        // 1. Jika tabel users punya kolom role langsung
        if (isset($user->role) && in_array($user->role, $validRoles, true)) {
            return $user->role;
        }

        // 2. Cek dari tabel model_has_roles & roles (Spatie)
        if (Schema::hasTable('model_has_roles') && Schema::hasTable('roles')) {
            $assigned = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->value('roles.name');

            if ($assigned) {
                $slug = strtolower(str_replace([' ', '-'], '_', $assigned));
                if (in_array($slug, $validRoles, true)) {
                    return $slug;
                }
            }
        }

        // 3. Cocokkan dengan daftar akun resmi di config/kusantara_roles.php berdasarkan email
        foreach (config('kusantara_roles.accounts', []) as $acc) {
            if (strtolower($acc['email']) === strtolower($user->email)) {
                return $acc['role'];
            }
        }

        return 'guest';
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'kusantara_admin_logged_in',
            'kusantara_admin_user',
            'kusantara_admin_role',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda telah keluar dari panel admin.');
    }
}