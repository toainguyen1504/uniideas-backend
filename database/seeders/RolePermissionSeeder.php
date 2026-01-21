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
        $guard = config('auth.defaults.guard', 'api');

        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role, $guard);
        }

        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, $guard);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN, $guard);
        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, $guard);
        $qaCoordinatorRole = Role::findByName(Acl::ROLE_QA_COORDINATOR, $guard);
        $staffRole = Role::findByName(Acl::ROLE_STAFF, $guard);

        $adminRole->givePermissionTo([
            Acl::PERMISSION_VIEW_ADMIN_MENU_DASHBOARD,
            Acl::PERMISSION_USER_LIST,
            Acl::PERMISSION_USER_ADD,
            Acl::PERMISSION_USER_EDIT,
            Acl::PERMISSION_USER_DELETE,
            Acl::PERMISSION_ROLE_MANAGE,
        ]);
        $qaManagerRole->givePermissionTo([
            Acl::PERMISSION_VIEW_QA_MANAGER_MENU_DASHBOARD,
        ]);
        $qaCoordinatorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_QA_COORDINATOR_MENU_DASHBOARD,
        ]);
        $staffRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_DASHBOARD,
        ]);
    }
}
