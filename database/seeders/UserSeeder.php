<?php

namespace Database\Seeders;

use App\Models\ModelHasRole;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed a sample user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'super-admin-wb@gmail.com',
            'company' => 'KMP',
            'password' => Hash::make('rahasialah'), // Hashed password
            'is_active' => true, // Optional if you have this field
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        $role = Role::findByName('SUPER_ADMIN'); // Get the role
        $modelHasRole = ModelHasRole::create([
            'role_uuid' => $role->role_uuid,
            'model_type' => 'App\Models\User',
            'model_uuid' => $user->uuid,
        ]);
        $user->assignRole($role);
        $user->save();
    }
}
