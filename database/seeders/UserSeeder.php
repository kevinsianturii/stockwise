<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed development user accounts.
     * 
     * IMPORTANT: These are development-only credentials.
     * Change passwords before any real deployment.
     */
    public function run(): void
    {
        // Development admin account
        User::firstOrCreate(
            ['email' => 'admin@stockwise.test'],
            [
                'name'      => 'Administrator',
                'password'  => Hash::make('admin123456'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // Development staff account
        User::firstOrCreate(
            ['email' => 'staff@stockwise.test'],
            [
                'name'      => 'Staf Gudang',
                'password'  => Hash::make('staff123456'),
                'role'      => 'staff',
                'is_active' => true,
            ]
        );
    }
}
