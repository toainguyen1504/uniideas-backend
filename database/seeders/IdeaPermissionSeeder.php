<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Acl\Acl;

class IdeaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_IDEA_LIST,
            Acl::PERMISSION_IDEA_ADD,
            Acl::PERMISSION_IDEA_EDIT,
            Acl::PERMISSION_IDEA_DELETE,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, 'web');
        $staffRole = Role::findByName(Acl::ROLE_STAFF, 'web');

        $qaManagerRole->givePermissionTo($permissions);
        $staffRole->givePermissionTo($permissions);
    }
}
