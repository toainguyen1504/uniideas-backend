<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Acl\Acl;

class SubmissionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            Acl::PERMISSION_SUBMISSION_LIST,
            Acl::PERMISSION_SUBMISSION_ADD,
            Acl::PERMISSION_SUBMISSION_EDIT,
            Acl::PERMISSION_SUBMISSION_DELETE,
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, 'web');
        $qaManagerRole->givePermissionTo($permissions);
    }
}
