<?php

namespace App\Support;

class AdminAccess
{
    public static function can(?string $role, string $permission): bool
    {
        if (!$role) {
            return false;
        }

        $roles = config('kusantara_roles.roles', []);
        if (!isset($roles[$role])) {
            return false;
        }

        $allowed = $roles[$role]['permissions'] ?? [];

        return in_array('*', $allowed, true) || in_array($permission, $allowed, true);
    }

    public static function roleLabel(?string $role): string
    {
        $roles = config('kusantara_roles.roles', []);
        return $roles[$role]['label'] ?? ucfirst(str_replace('_', ' ', (string) $role));
    }
}