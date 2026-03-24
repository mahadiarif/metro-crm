<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use HasinHayder\Tyro\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles using Tyro models
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'slug' => 'manager']);
        $marketingRole = Role::firstOrCreate(['name' => 'Marketing Executive', 'slug' => 'marketing-executive']);

        // Create Super Admin user
        $admin = User::firstOrCreate(
        ['email' => 'admin@salescrm.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]
        );

        $admin->assignRole($superAdminRole);
    }
}
