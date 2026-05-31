<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        Permission::firstOrCreate(['name' => 'view loans']);
        Permission::firstOrCreate(['name' => 'create loans']);
        Permission::firstOrCreate(['name' => 'approve loans']);
        Permission::firstOrCreate(['name' => 'reject loans']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'view reports']);

        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $loanOfficer = Role::firstOrCreate(['name' => 'loan officer']);
        $loanOfficer->givePermissionTo(['view loans', 'approve loans', 'reject loans']);

        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo(['view loans', 'view reports']);
    }
}