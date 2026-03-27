<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Acl\Acl;

class ExportIdeasPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            Acl::PERMISSION_EXPORT_IDEAS
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, 'web');

        $qaManagerRole->givePermissionTo($permissions);
    }
}
