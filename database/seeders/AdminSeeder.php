<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
        ]);

        $adminEmail = (string) config('admin.seed.email');
        $adminPassword = (string) config('admin.seed.password');

        if ($adminEmail === '' || $adminPassword === '') {
            $this->command?->warn('AdminSeeder skipped: ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env.');
            return;
        }

        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => (string) config('admin.seed.name', 'Admin'),
                'password' => Hash::make($adminPassword),
                'status' => (string) config('admin.seed.status', 'active'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$role->id]);
    }
}
