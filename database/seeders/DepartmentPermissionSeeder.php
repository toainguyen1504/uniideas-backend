<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Acl\Acl;

class DepartmentPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_DEPARTMENT_LIST,
            Acl::PERMISSION_DEPARTMENT_ADD,
            Acl::PERMISSION_DEPARTMENT_EDIT,
            Acl::PERMISSION_DEPARTMENT_DELETE,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN, 'web');
        $adminRole->givePermissionTo($permissions);
    }
}
