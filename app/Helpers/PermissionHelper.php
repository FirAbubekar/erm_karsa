<?php

if (!function_exists('hasPermission')) {

    function hasPermission($permission)
    {
        return in_array(
            $permission,
            session('permissions', [])
        );
    }
}
