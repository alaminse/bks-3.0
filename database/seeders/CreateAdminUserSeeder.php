<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Create Roles (if not exists)
        |--------------------------------------------------------------------------
        */
        $userRole  = Role::firstOrCreate(['name' => 'user']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        /*
        |--------------------------------------------------------------------------
        | Assign permissions
        |--------------------------------------------------------------------------
        */
        $userDashboardPermission = Permission::firstOrCreate([
            'name' => 'user-dashboard'
        ]);

        $userRole->givePermissionTo($userDashboardPermission);

        $allPermissions = Permission::pluck('id')->all();
        $adminRole->syncPermissions($allPermissions);

        /*
        |--------------------------------------------------------------------------
        | Create Admin User
        |--------------------------------------------------------------------------
        */
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'              => 'Admin',
                'email'             => 'admin@gmail.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        /*
        |--------------------------------------------------------------------------
        | Create Regular User
        |--------------------------------------------------------------------------
        */
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'              => 'Test User',
                'email'             => 'user@gmail.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole($userRole);

    }
}
