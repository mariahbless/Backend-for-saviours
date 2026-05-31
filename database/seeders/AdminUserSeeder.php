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
        $user = User::updateOrCreate(
            ['email' => 'admin@savioursfinance.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password123',
                'phone' => '+256750000000',
                'location' => 'HQ',
            ]
        );
        
        // Assign admin role if roles exist
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $user->assignRole('admin');
        }
    }
}
