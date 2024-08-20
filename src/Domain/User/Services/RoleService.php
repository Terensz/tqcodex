<?php

namespace Domain\User\Services;

use Domain\User\Models\Role;

/**
 * Service for all types of roles, admins, customers and guests
 */
class RoleService
{
    /**
     * Roles
     */
    public const ROLE_SUPER_ADMIN = 'SuperAdmin';

    public const ROLE_KEY_ACCOUNT_MANAGER = 'KeyAccountManager';

    public const ROLE_FINANCIAL_ACCOUNTING_MANAGER = 'FinancialAccountingManager';

    public const ROLE_REGISTERED_CUSTOMER = 'RegisteredCustomer';

    public const ROLE_VISITOR = 'Visitor';

    public const ROLES = [
        UserService::ROLE_TYPE_ADMIN => [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_KEY_ACCOUNT_MANAGER,
            self::ROLE_FINANCIAL_ACCOUNTING_MANAGER,
        ],
        UserService::ROLE_TYPE_CUSTOMER => [
            self::ROLE_REGISTERED_CUSTOMER,
        ],
        UserService::ROLE_TYPE_GUEST => [
            self::ROLE_VISITOR,
        ],
    ];

    public static function getRoleNames()
    {
        return self::getRoleParams('name');
    }

    public static function getRoleParams($requiredParam = null)
    {
        $roleParams = [];
        foreach (Role::valid()->get() as $role) {
            $type = UserService::getRoleTypeByGuard($role->guard_name);
            $loopRoleParams = [
                'name' => $role->name,
                'type' => $type,
            ];
            if ($requiredParam) {
                $roleParams[] = $loopRoleParams[$requiredParam];
            } else {
                $roleParams[] = $loopRoleParams;
            }
        }

        return $roleParams;
    }
}
