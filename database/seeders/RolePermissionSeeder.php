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

        $permissionNames = array_map(fn($p) => trim($p), Acl::permissions());
        foreach ($permissionNames as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guard,
            ]);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN, $guard);
        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, $guard);
        $qaCoordinatorRole = Role::findByName(Acl::ROLE_QA_COORDINATOR, $guard);
        $staffRole = Role::findByName(Acl::ROLE_STAFF, $guard);

        $permissionModels = Permission::whereIn('name', $permissionNames)
            ->where('guard_name', $guard)
            ->get()
            ->all();

        $adminRole->givePermissionTo($permissionModels);
        $qaManagerRole->givePermissionTo($permissionModels);
        $qaCoordinatorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_DASHBOARD,
        ]);
        $staffRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_DASHBOARD,
        ]);
    }
}
