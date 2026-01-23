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

    const PERMISSION_VIEW_ADMIN_MENU_DASHBOARD = 'View Admin Dashboard Menu';

    const PERMISSION_VIEW_QA_MANAGER_MENU_DASHBOARD = 'View QA Manager Dashboard Menu';

    const PERMISSION_VIEW_QA_COORDINATOR_MENU_DASHBOARD = 'View QA Coordinator Dashboard Menu';

    const PERMISSION_VIEW_MENU_DASHBOARD = 'View Dashboard Menu';

    const PERMISSION_ASSIGNEE = 'Assignee';

    const PERMISSION_USER_MANAGE = 'User Management';

    const PERMISSION_USER_LIST = 'User List';
    
    const PERMISSION_USER_ADD = 'Add User';

    const PERMISSION_USER_EDIT = 'Edit User';

    const PERMISSION_USER_DELETE = 'Delete User';

    const PERMISSION_ROLE_MANAGE = 'Role Management';

    const PERMISSION_DEPARTMENT_LIST = 'Department List';

    const PERMISSION_DEPARTMENT_ADD = 'Add Department';

    const PERMISSION_DEPARTMENT_EDIT = 'Edit Department';

    const PERMISSION_DEPARTMENT_DELETE = 'Delete Department';

    const PERMISSION_CATEGORY_LIST = 'Submission List';

    const PERMISSION_CATEGORY_ADD = 'Add Submission';

    const PERMISSION_CATEGORY_EDIT = 'Edit Submission';

    const PERMISSION_CATEGORY_DELETE = 'Delete Submission';

     const PERMISSION_SUBMISSION_LIST = 'Submission List';

    const PERMISSION_SUBMISSION_ADD = 'Add Submission';

    const PERMISSION_SUBMISSION_EDIT = 'Edit Submission';

    const PERMISSION_SUBMISSION_DELETE = 'Delete Submission';

    

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
