<?php

namespace Domain\Project\Services;

use Domain\User\Services\PermissionService;
use Domain\User\Services\UserService;

class PermissionSeederDataService
{
    public const INITIAL_PERMISSION_SEEDER_DATA = [
        UserService::GUARD_PUBLIC => [
            PermissionSuffixService::SUFFIX_PUBLIC_AREA => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
        ],
        UserService::GUARD_CUSTOMER => [
            // PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER => [
            //     PermissionService::POSSIBLE_PREFIXES => [
            //         PermissionService::PREFIX_VIEW,
            //         PermissionService::PREFIX_CREATE,
            //         PermissionService::PREFIX_EDIT,
            //         PermissionService::PREFIX_DELETE,
            //     ],
            // ],
        ],
        UserService::GUARD_ADMIN => [
            // Admin pages
            PermissionSuffixService::SUFFIX_ADMIN_DASHBOARD => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_PROFILE => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_ADMINS => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_ROLES => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_PERMISSIONS => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_ADMINS_ACTIVITY_LOG => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_ERROR_LOG => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_VISIT_LOG => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_SETTINGS => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS_ACTIVITY_LOG => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            // Project
            PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS_CASHFLOW_DATA => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_ACCOUNT_SETTLEMENT_LOG => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_CUSTOMER_REFERRAL_SYSTEM => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],
            PermissionSuffixService::SUFFIX_ADMIN_SERVICE_PRICES => [
                PermissionService::POSSIBLE_PREFIXES => [
                    PermissionService::PREFIX_VIEW,
                    PermissionService::PREFIX_CREATE,
                    PermissionService::PREFIX_EDIT,
                    PermissionService::PREFIX_DELETE,
                ],
            ],

            // Webshop
            PermissionSuffixService::SUFFIX_ADMIN_WEBSHOP_DASHBOARD => [
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
