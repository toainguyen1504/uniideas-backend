<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Acl\Acl;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApproveIdeaPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_IDEA_APPROVE,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $qaCoordinatorRole = Role::findByName(Acl::ROLE_QA_COORDINATOR, 'web');
        $qaCoordinatorRole->givePermissionTo($permissions);
    }
}
