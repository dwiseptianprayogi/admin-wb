<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create user',
            'edit user',
            'delete user',
            'view user',
            'export user',
            'import user',
            'create vehicle',
            'edit vehicle',
            'delete vehicle',
            'view vehicle',
            'export vehicle',
            'import vehicle',
            'create vehicle_type',
            'edit vehicle_type',
            'delete vehicle_type',
            'view vehicle_type',
            'export vehicle_type',
            'import vehicle_type',
            'create area',
            'edit area',
            'delete area',
            'view area',
            'export area',
            'import area',
            'create region',
            'edit region',
            'delete region',
            'view region',
            'export region',
            'import region',
            'create transporter',
            'edit transporter',
            'delete transporter',
            'view transporter',
            'export transporter',
            'import transporter',
            'create transporter_rate',
            'edit transporter_rate',
            'delete transporter_rate',
            'view transporter_rate',
            'export transporter_rate',
            'import transporter_rate',
            'view analytics',
            'view receiving_material',
            'view finish_good',
            'weight_in',
            'manual_input',
            'weight_out',
            'print_rw',
            'print_fg',
            'view data_wb',
            'approve',
            'reject',
            'approve 2',
            'reject 2',
            'view approval',
            'view group',
            'create group',
            'edit group',
            'view log',
            'view report'
        ];

        $role = Role::findByName('SUPER_ADMIN'); // Get the role
        foreach ($permissions as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $roleHasPermission = RoleHasPermission::create([
                'role_uuid' => $role->uuid,
                'permission_uuid' => $permission->uuid,
            ]);
            // $permission->assignRole($role);
            // $permission->update();
        }
    }
}
