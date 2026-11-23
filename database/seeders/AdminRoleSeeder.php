<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $accounts = config('kusantara_roles.accounts', []);
        $hasRoleCol = Schema::hasColumn('users', 'role');
        $hasActiveCol = Schema::hasColumn('users', 'is_active');

        foreach ($accounts as $acc) {
            $payload = [
                'name'       => $acc['name'],
                'password'   => Hash::make($acc['password']),
                'updated_at' => now(),
            ];

            if ($hasRoleCol) {
                $payload['role'] = $acc['role'];
            }
            if ($hasActiveCol) {
                $payload['is_active'] = $acc['is_active'];
            }

            $exists = DB::table('users')->where('email', $acc['email'])->first();

            if ($exists) {
                DB::table('users')->where('id', $exists->id)->update($payload);
            } else {
                $payload['email'] = $acc['email'];
                $payload['created_at'] = now();
                DB::table('users')->insert($payload);
            }
        }
    }
}