<?php

/**
 * File Acl.php
 *
 * @author Truong Tri Kiet
 *
 * @version 1.0
 */

namespace App\Acl;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class Acl
{
    const ROLE_ADMIN = 'Admin';

    const ROLE_QA_MANAGER = 'QA Manager';

    const ROLE_QA_COORDINATOR = 'QA Coordinator';

    const ROLE_STAFF = 'Staff';

    const PERMISSION_VIEW_ADMIN_MENU_DASHBOARD = 'dashboard.admin';

    const PERMISSION_VIEW_QA_MANAGER_MENU_DASHBOARD = 'dashboard.qa.manager';

    const PERMISSION_VIEW_QA_COORDINATOR_MENU_DASHBOARD = 'dashboard.qa.coordinator';

    const PERMISSION_VIEW_MENU_DASHBOARD = 'dashboard';

    const PERMISSION_ASSIGNEE = 'assignee';

    const PERMISSION_USER_MANAGE = 'user.manage';

    const PERMISSION_USER_LIST = 'user.list';
    
    const PERMISSION_USER_ADD = 'user.add';

    const PERMISSION_USER_EDIT = 'user.edit';

    const PERMISSION_USER_DELETE = 'user.delete';

    const PERMISSION_ROLE_MANAGE = 'role.manage';

    const PERMISSION_DEPARTMENT_LIST = 'department.list';

    const PERMISSION_DEPARTMENT_ADD = 'department.add';

    const PERMISSION_DEPARTMENT_EDIT = 'department.edit';

    const PERMISSION_DEPARTMENT_DELETE = 'department.delete';

    const PERMISSION_SUBMISSION_LIST = 'submission.list';

    const PERMISSION_SUBMISSION_ADD = 'submission.add';

    const PERMISSION_SUBMISSION_EDIT = 'submission.edit';

    const PERMISSION_SUBMISSION_DELETE = 'submission.delete';

    const PERMISSION_CATEGORY_LIST = 'category.list';

    const PERMISSION_CATEGORY_ADD = 'category.add';

    const PERMISSION_CATEGORY_EDIT = 'category.edit';

    const PERMISSION_CATEGORY_DELETE = 'category.delete';

    const PERMISSION_IDEA_LIST = 'idea.list';

    const PERMISSION_IDEA_ADD = 'idea.add';

    const PERMISSION_IDEA_EDIT = 'idea.edit';

    const PERMISSION_IDEA_DELETE = 'idea.delete';

    const PERMISSION_IDEA_APPROVE = 'idea.approve';

    const PERMISSION_ROLE_LIST = 'role.list';

    const PERMISSION_ROLE_EDIT = 'role.edit';

    const PERMISSION_PERMISSION_LIST = 'permission.list';

    const PERMISSION_EXPORT_IDEAS = 'idea.export';

    /**
     * @param  array  $exclusives Exclude some permissions from the list
     */
    public static function permissions(array $exclusives = []): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function ($value, $key) use ($exclusives) {
                return ! in_array($value, $exclusives) && Str::startsWith($key, 'PERMISSION_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function menuPermissions(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function ($value, $key) {
                return Str::startsWith($key, 'PERMISSION_VIEW_MENU_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function roles(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $roles = Arr::where($constants, function ($value, $key) {
                return Str::startsWith($key, 'ROLE_');
            });

            return array_values($roles);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }
}
