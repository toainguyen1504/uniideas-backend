<?php

namespace Database\Seeders;

use App\Acl\Acl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoryPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_CATEGORY_LIST,
            Acl::PERMISSION_CATEGORY_ADD,
            Acl::PERMISSION_CATEGORY_EDIT,
            Acl::PERMISSION_CATEGORY_DELETE,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, 'web');
        $qaManagerRole->givePermissionTo($permissions);
    }
}
