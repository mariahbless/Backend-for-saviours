<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@savioursfinance.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'location' => 'HQ',
            ]
        );
        
        // Assign admin role if roles exist
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $user->assignRole('admin');
        }
    }
}
