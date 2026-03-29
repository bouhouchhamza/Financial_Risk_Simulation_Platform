<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
USE Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' =>'admin@test.com'],
            ['name' => 'Admin',
            'password' => Hash::make('123456'),
            'status' => 'active',
            ]
        );

        $role = Role::where('name', 'admin')->first();

        $admin->roles()->syncWithoutDetaching([$role->id]);
    }
}
