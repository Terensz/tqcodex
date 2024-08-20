<?php

namespace Domain\Language\Services;

use Domain\User\Services\PermissionService;
use Domain\User\Services\UserService;

class Permissions
{
    /**
     * Admin pages
     */
    public const ADMIN_LANGUAGES = 'AdminLanguages';

    /**
     * Customer pages
     */
    public const INITIAL_PERMISSION_SEEDER_DATA = [
        UserService::GUARD_PUBLIC => [

        ],
        UserService::GUARD_CUSTOMER => [

        ],
        UserService::GUARD_ADMIN => [
            self::ADMIN_LANGUAGES => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
        ],
    ];
}
