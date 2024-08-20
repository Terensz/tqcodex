<?php

/*
|--------------------------------------------------------------------------
| Common Application Helpers not defiend yet
|--------------------------------------------------------------------------
|
| Common App Helper function
|
*/
use Domain\User\Services\UserRoleService;

if (! function_exists('adminCan')) {
    function adminCan($action)
    {
        return UserRoleService::adminHasPermission($action);
    }
}

if (! function_exists('permission')) {
    function permission($domain)
    {
        return (new \Domain\System\Services\PermissionNameBuilder)->domain($domain);
    }
}

if (! function_exists('csp_nonce')) {
    /**
     * generate a nonce
     */
    function csp_nonce()
    {
        return uniqid('ho-', true);
    }
}
