<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionNames = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete'
        ];

        foreach ($permissionNames as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $permissions = Permission::all();
        Log::info('Permissions:', $permissions->pluck('name')->toArray());

        $user = User::updateOrCreate(
            ['email' => 'Jaydev@gmail.com'],
            [
                'name' => 'Jaydev Mori',
                'phone' => '1234567899',
                'password' => bcrypt('123456')
            ]
        );

        $role = Role::firstOrCreate(['name' => 'Admin']);

        $role->syncPermissions($permissions);

        Log::info('Permissions assigned to role:', $role->permissions->pluck('name')->toArray());

        $user->assignRole($role);

        Log::info('Roles assigned to user:', $user->roles->pluck('name')->toArray());
    }
}
