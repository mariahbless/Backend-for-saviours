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
        Permission::create(['name' => 'view loans']);
        Permission::create(['name' => 'create loans']);
        Permission::create(['name' => 'approve loans']);
        Permission::create(['name' => 'reject loans']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view reports']);

        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $loanOfficer = Role::create(['name' => 'loan officer']);
        $loanOfficer->givePermissionTo(['view loans', 'approve loans', 'reject loans']);

        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo(['view loans', 'view reports']);
    }
}