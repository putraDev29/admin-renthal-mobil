<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure existing admin is super_admin
        $admin = User::first();
        if ($admin) {
            $admin->update([
                'role' => 'super_admin'
            ]);
        }

        // 2. Create or ensure Admin Keuangan account
        User::firstOrCreate(
            ['email' => 'keuangan@amiitrans.com'],
            [
                'name' => 'Admin Keuangan',
                'password' => sha1('admin123'),
                'role' => 'admin_keuangan'
            ]
        );
    }
}
