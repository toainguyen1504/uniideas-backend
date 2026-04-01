<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Acl\Acl;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAccessPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_ROLE_EDIT,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN, 'web');
        $adminRole->givePermissionTo($permissions);
    }
}
