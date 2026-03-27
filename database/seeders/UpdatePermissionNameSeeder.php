<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Acl\Acl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdatePermissionNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manualMapping = [
            'View Admin Dashboard Menu' => Acl::PERMISSION_VIEW_ADMIN_MENU_DASHBOARD,
            'View QA Manager Dashboard Menu' => Acl::PERMISSION_VIEW_QA_MANAGER_MENU_DASHBOARD,
            'View QA Coordinator Dashboard Menu' => Acl::PERMISSION_VIEW_QA_COORDINATOR_MENU_DASHBOARD,
            'View Dashboard Menu' => Acl::PERMISSION_VIEW_MENU_DASHBOARD,

            'User Management' => Acl::PERMISSION_USER_MANAGE,
            'User List' => Acl::PERMISSION_USER_LIST,
            'Add User' => Acl::PERMISSION_USER_ADD,
            'Edit User' => Acl::PERMISSION_USER_EDIT,
            'Delete User' => Acl::PERMISSION_USER_DELETE,
            'Role Management' => Acl::PERMISSION_ROLE_MANAGE,
            'Role List' => Acl::PERMISSION_ROLE_LIST,
            'Edit Role' => Acl::PERMISSION_ROLE_EDIT,
            'Permission List' => Acl::PERMISSION_PERMISSION_LIST,

            'Department List' => Acl::PERMISSION_DEPARTMENT_LIST,
            'Add Department' => Acl::PERMISSION_DEPARTMENT_ADD,
            'Edit Department' => Acl::PERMISSION_DEPARTMENT_EDIT,
            'Delete Department' => Acl::PERMISSION_DEPARTMENT_DELETE,

            'Submission List' => Acl::PERMISSION_SUBMISSION_LIST,
            'Add Submission' => Acl::PERMISSION_SUBMISSION_ADD,
            'Edit Submission' => Acl::PERMISSION_SUBMISSION_EDIT,
            'Delete Submission' => Acl::PERMISSION_SUBMISSION_DELETE,

            'Category List' => Acl::PERMISSION_CATEGORY_LIST,
            'Add Category' => Acl::PERMISSION_CATEGORY_ADD,
            'Edit Category' => Acl::PERMISSION_CATEGORY_EDIT,
            'Delete Category' => Acl::PERMISSION_CATEGORY_DELETE,

            'Idea List' => Acl::PERMISSION_IDEA_LIST,
            'Add Idea' => Acl::PERMISSION_IDEA_ADD,
            'Edit Idea' => Acl::PERMISSION_IDEA_EDIT,
            'Delete Idea' => Acl::PERMISSION_IDEA_DELETE,
            'Approve Idea' => Acl::PERMISSION_IDEA_APPROVE,
            
            'Assignee' => Acl::PERMISSION_ASSIGNEE,
        ];

        $this->command->info("Bắt đầu cập nhật tên quyền...");

        foreach ($manualMapping as $oldName => $newName) {
            $updated = DB::table('permissions')
                ->where('name', $oldName)
                ->update([
                    'name' => $newName,
                    'updated_at' => now()
                ]);

            if ($updated) {
                $this->command->info("Đã đổi: [{$oldName}] ---> [{$newName}]");
            } else {
                $alreadyUpdated = DB::table('permissions')->where('name', $newName)->exists();
                if ($alreadyUpdated) {
                    $this->command->line("<fg=blue>Bỏ qua: [{$newName}] đã được cập nhật trước đó.</>");
                } else {
                    $this->command->error("Lỗi: Không tìm thấy tên cũ '{$oldName}' trong Database.");
                }
            }
        }

        $this->command->info("--- DONE ---");
    }
}
