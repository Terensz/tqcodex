<?php

namespace Domain\User\Services;

use Domain\Shared\Helpers\PHPHelper;
use Domain\User\Models\Permission;
use Illuminate\Support\Facades\Cache;

class RolePermissionDataService
{
    public const CACHE_NAME = 'role_permission_data';

    public static function recalculateGuardRolePermissionData($roleName, $guardRolePermissionData, $requestedGuardName, $checkAllInGuardRequested, $decheckAllInGuardRequested, $checkAllInSuffixRequested, $decheckAllInSuffixRequested): array
    {
        $cachedRolePermissionData = RolePermissionDataService::getCachedRolePermissionData($roleName);
        if (! isset($cachedRolePermissionData[$requestedGuardName])) {
            return [
                'guardRolePermissionData' => $guardRolePermissionData,
                'allPrefixesAreTrue' => false,
            ];
        }
        foreach ($cachedRolePermissionData[$requestedGuardName] as $suffix => $suffixParams) {
            if (! isset($guardRolePermissionData[$suffix])) {
                $guardRolePermissionData[$suffix] = [];
            }
        }

        $allPrefixesAreTrue = count($cachedRolePermissionData[$requestedGuardName]) > 0 ? true : false;
        foreach ($guardRolePermissionData as $dataSuffix => $dataPrefixes) {
            $allRowPrefixesAreTrue = true;
            foreach ([PermissionService::PREFIX_VIEW, PermissionService::PREFIX_CREATE, PermissionService::PREFIX_EDIT, PermissionService::PREFIX_DELETE] as $prefix) {
                if ($checkAllInGuardRequested || $checkAllInSuffixRequested === $dataSuffix) {
                    $guardRolePermissionData[$dataSuffix][$prefix] = true;
                }
                if (! isset($guardRolePermissionData[$dataSuffix][$prefix]) || ! $guardRolePermissionData[$dataSuffix][$prefix] || $decheckAllInGuardRequested || $decheckAllInSuffixRequested === $dataSuffix) {
                    $guardRolePermissionData[$dataSuffix][$prefix] = false;
                    $allPrefixesAreTrue = false;
                    $allRowPrefixesAreTrue = false;
                }
            }
            $guardRolePermissionData[$dataSuffix][PermissionService::PREFIX_ALL] = false;
            if ($allRowPrefixesAreTrue) {
                $guardRolePermissionData[$dataSuffix][PermissionService::PREFIX_ALL] = true;
            }
        }

        return [
            'guardRolePermissionData' => $guardRolePermissionData,
            'allPrefixesAreTrue' => $allPrefixesAreTrue,
        ];
    }

    public static function getCachedRolePermissionData($exceptRoleName = null): array
    {
        $permissions = Permission::valid()->get();
        $rolePermissionData = [];
        foreach ($permissions as $permission) {
            $permissionNameParts = PHPHelper::explode(PermissionService::PERMISSION_PART_SEPARATOR, $permission->name);
            $prefix = $permissionNameParts[0];
            $suffix = PHPHelper::implode(PermissionService::PERMISSION_PART_SEPARATOR, PHPHelper::arraySlice($permissionNameParts, 1));

            if (! isset($rolePermissionData[$permission->guard_name])) {
                $rolePermissionData[$permission->guard_name] = [];
            }
            $exceptRolePermissionSuffix = null;
            if ($exceptRoleName) {
                // $exceptRoleViewPermissionName = PermissionService::createRolePermissionNames($exceptRoleName, PermissionService::PREFIX_VIEW);
                $exceptRolePermissionSuffix = PermissionService::createRolePermissionNameSuffix($exceptRoleName);
            }
            if (! $exceptRoleName || $exceptRolePermissionSuffix !== $suffix) {
                if (! isset($rolePermissionData[$permission->guard_name][$suffix])) {
                    $rolePermissionData[$permission->guard_name][$suffix] = [PermissionService::POSSIBLE_PREFIXES => []];
                }
                $rolePermissionData[$permission->guard_name][$suffix][PermissionService::POSSIBLE_PREFIXES][] = $prefix;
            }

            // if (! isset($rolePermissionData[$permission->guard_name][$suffix])) {
            //     $rolePermissionData[$permission->guard_name][$suffix] = [PermissionService::POSSIBLE_PREFIXES => []];
            // }
            // $rolePermissionData[$permission->guard_name][$suffix][PermissionService::POSSIBLE_PREFIXES][] = $prefix;
        }

        Cache::forever(RolePermissionDataService::CACHE_NAME, $rolePermissionData);

        return $rolePermissionData;
    }
}
