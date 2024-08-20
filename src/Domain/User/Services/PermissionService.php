<?php

namespace Domain\User\Services;

use Domain\Shared\Helpers\PHPHelper;

class PermissionService
{
    public const POSSIBLE_PREFIXES = 'POSSIBLE_PREFIXES';

    public const PERMISSION_PART_SEPARATOR = '_';

    /**
     * Prefixes
     */
    public const PREFIX_ALL = 'ALL';

    public const PREFIX_VIEW = 'VIEW';

    public const PREFIX_CREATE = 'CREATE';

    public const PREFIX_EDIT = 'EDIT';

    public const PREFIX_DELETE = 'DELETE';

    public static function createPermissionName(string $prefix, string $suffix): string
    {
        return $prefix.PermissionService::PERMISSION_PART_SEPARATOR.$suffix;
    }

    public static function separatePermissionName(string $permissionName): array
    {
        $permissionNameParts = PHPHelper::explode(self::PERMISSION_PART_SEPARATOR, $permissionName);
        $prefix = '';
        $suffixParts = [];
        $counter = 0;
        foreach ($permissionNameParts as $permissionNamePart) {
            if ($counter === 0) {
                $prefix = $permissionNamePart;
            } else {
                $suffixParts[] = $permissionNamePart;
            }
            $counter++;
        }

        return [
            'prefix' => $prefix,
            'suffix' => PHPHelper::implode(self::PERMISSION_PART_SEPARATOR, $suffixParts),
        ];
    }

    public static function createRolePermissionNameSuffix(string $roleName): string
    {
        return 'Role'.$roleName;
    }

    public static function createRolePermissionNames($roleName, null|string|array $requestedPrefixes = null): string|array
    {
        if (! $requestedPrefixes) {
            $prefixes = [PermissionService::PREFIX_VIEW, PermissionService::PREFIX_CREATE, PermissionService::PREFIX_EDIT, PermissionService::PREFIX_DELETE];
        } else {
            $prefixes = is_array($requestedPrefixes) ? $requestedPrefixes : [$requestedPrefixes];
        }

        $suffix = self::createRolePermissionNameSuffix($roleName);
        $rolePermissionNames = [];
        foreach ($prefixes as $prefix) {
            $permissionName = PermissionService::createPermissionName($prefix, $suffix);
            if (count($prefixes) === 1) {
                return $permissionName;
            }
            $rolePermissionNames[$prefix] = $permissionName;
        }

        return $rolePermissionNames;
    }
}
