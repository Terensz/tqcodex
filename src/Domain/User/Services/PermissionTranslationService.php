<?php

namespace Domain\User\Services;

use Domain\Shared\Helpers\PHPHelper;

class PermissionTranslationService
{
    public static function getNameTranslation($permissionSuffix)
    {
        if (PHPHelper::getStringPosition('Role', $permissionSuffix) === 0) {
            $roleName = PHPHelper::leftCut($permissionSuffix, 'Role');
            $translatedRoleName = __('role.'.$roleName);

            return __('permission.RolePermission', ['role' => $translatedRoleName === 'role.'.$roleName ? $roleName : $translatedRoleName]);
        }
        if (PHPHelper::getStringPosition('Admin', $permissionSuffix) === 0) {
            $pageName = PHPHelper::leftCut($permissionSuffix, 'Admin');

            return __('permission.AdminPagePermission', ['page' => $pageName]);
        }
        if (PHPHelper::getStringPosition('Customer', $permissionSuffix) === 0) {
            $pageName = PHPHelper::leftCut($permissionSuffix, 'Customer');

            return __('permission.CustomerPagePermission', ['page' => $pageName]);
        }

        return $permissionSuffix;
    }
}
