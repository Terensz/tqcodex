<?php

namespace Domain\User\Services;

use Domain\User\Models\Permission;
use Spatie\Permission\Contracts\Permission as PermissionInterface;

class PermissionRepository
{
    public static function create(string $name): PermissionInterface
    {
        $permission = Permission::findByName($name);
        /** @phpstan-ignore-next-line */
        if ($permission) {
        }

        return Permission::create(['name' => $name]);
    }

    public static function update(string $name, array $attributes): bool
    {
        $permission = Permission::findByName($name);

        /** @phpstan-ignore-next-line */
        return $permission ? $permission->update($attributes) : false;
    }

    public static function delete(string $name): bool
    {
        $permission = Permission::findByName($name);

        /** @phpstan-ignore-next-line */
        return $permission ? $permission->delete() : null;
    }

    // public static function findByName(string $permissionName): ?PermissionInterface
    // {
    //     $guardName = $guardName ?? Guard::getDefaultName(static::class);
    //     $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);
    //     if (! $permission) {
    //         throw PermissionDoesNotExist::create($name, $guardName);
    //     }

    //     return $permission;
    // }
}
