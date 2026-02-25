<?php
use Carbon\Carbon;

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

if (!function_exists('customFormatDate')) {
    /**
     * Format the given datetime to format.
     *
     * @param mixed $datetime
     * @param string $format
     * @return string
     */
    function customFormatDate($datetime, string $format = 'd/m/Y'): string
    {
        if (!$datetime) {
            return '';
        }
        return Carbon::parse($datetime)->format($format);
    }
}

if (!function_exists('customDateFormat')) {

    /**
     * Custom date format method.
     *
     * @param $dateTime
     * @return string
     */
    function customDateFormat($dateTime): string
    {
        return date_format($dateTime, 'H:i | d/m/Y') ?? 'N/A';
    }
}
