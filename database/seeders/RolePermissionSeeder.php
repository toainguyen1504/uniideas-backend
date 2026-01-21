<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Acl\Acl;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role, 'web');
        }

        $createdPermissions = [];

        foreach (Acl::permissions() as $permission) {
            $perm = Permission::findOrCreate($permission, 'web');
            $createdPermissions[$permission] = $perm;
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER);
        $qaCoordinatorRole = Role::findByName(Acl::ROLE_QA_COORDINATOR);
        $staffRole = Role::findByName(Acl::ROLE_STAFF);

        $adminRole->givePermissionTo([
            $createdPermissions[Acl::PERMISSION_VIEW_ADMIN_MENU_DASHBOARD],
            $createdPermissions[Acl::PERMISSION_USER_LIST],
            $createdPermissions[Acl::PERMISSION_USER_ADD],
            $createdPermissions[Acl::PERMISSION_USER_EDIT],
            $createdPermissions[Acl::PERMISSION_USER_DELETE],
            $createdPermissions[Acl::PERMISSION_ROLE_MANAGE],
        ]);
        $qaManagerRole->givePermissionTo([
            $createdPermissions[Acl::PERMISSION_VIEW_QA_MANAGER_MENU_DASHBOARD],
        ]);
        $qaCoordinatorRole->givePermissionTo([
            $createdPermissions[Acl::PERMISSION_VIEW_QA_COORDINATOR_MENU_DASHBOARD],
        ]);
        $staffRole->givePermissionTo([
            $createdPermissions[Acl::PERMISSION_VIEW_MENU_DASHBOARD],
        ]);
    }
}
