<?php

if (!function_exists('checkPermission')) {

    /**
     * Check permission from current user
     *
     * @param string $permission
     * @return bool
     */
    function checkPermission($permission)
    {
        return auth()->user()->hasPermissionTo($permission);
    }
}
