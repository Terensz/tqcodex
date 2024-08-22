<?php

namespace Domain\User\Services;

use Domain\Admin\Models\Admin;
use Domain\Customer\Models\Contact;
use Domain\User\Models\Permission;
use Domain\User\Models\Role;
use Exception;

class UserRoleService
{
    /**
     * Get user object
     */
    public static function getAdminIfPermitted(?Admin $user = null): Admin
    {
        if (! $user) {
            $user = UserService::getAdmin();
        }
        if (! $user instanceof Admin) {
            throw new \Exception('Missing admin.');
        }

        return $user;
    }

    public static function getCustomerIfPermitted(?Contact $user = null): Contact
    {
        if (! $user) {
            $user = UserService::getContact();
        }
        if (! $user instanceof Contact) {
            throw new Exception('Missing customer.');
        }

        return $user;
    }

    /**
     * assign role
     */
    public static function assignRoleToAdmin(string $roleName, ?Admin $user = null): Admin
    {
        $user = self::getAdminIfPermitted($user);
        $user->assignRole($roleName);
        return $user->refresh();
    }

    public static function assignRoleToCustomer(string $roleName, ?Contact $user): Contact
    {
        $user = self::getCustomerIfPermitted($user);
        $user->assignRole($roleName);

        return $user->refresh();
    }

    /**
     * sync roles
     */
    public static function syncRolesToAdmin(array $roleNames, ?Admin $user = null): Admin
    {
        $user = self::getAdminIfPermitted($user);
        $user->syncRoles($roleNames);

        return $user->refresh();
    }

    public static function syncRolesToCustomer(array $roleNames, ?Contact $user): Contact
    {
        $user = self::getCustomerIfPermitted($user);
        $user->syncRoles($roleNames);

        return $user->refresh();
    }

    /**
     * remove role
     */
    public static function removeRoleFromAdmin($roleName, ?Admin $user = null)
    {
        $user = self::getAdminIfPermitted($user);

        return $user->removeRole($roleName);
    }

    public static function removeRoleFromCustomer($roleName, ?Contact $user = null)
    {
        $user = self::getCustomerIfPermitted($user);

        return $user->removeRole($roleName);
    }

    /**
     * has role
     */
    public static function adminHasRole($roleName, ?Admin $user = null)
    {
        $user = self::getAdminIfPermitted($user);

        return $user->hasRole($roleName);
    }

    public static function customerHasRole($searchedRole, ?Contact $user = null)
    {
        $user = self::getCustomerIfPermitted($user);
        foreach ($user->roles()->get() as $roleObject) {
            if ($roleObject instanceof Role && $roleObject->name == $searchedRole) {
                return true;
            }
        }

        return false;
    }

    /**
     * has permission
     */
    public static function adminHasPermission($searchedPermission, ?Admin $user = null): bool
    {
        $user = self::getAdminIfPermitted($user);
        foreach (self::getAllPermissionsOfAdmin($user) as $permission) {
            if ($permission->name === $searchedPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * has permission
     */
    public static function customerHasPermission($searchedPermission, ?Contact $user = null)
    {
        $user = self::getCustomerIfPermitted($user);
        foreach (self::getAllPermissionsOfCustomer($user) as $permission) {
            if ($permission->name === $searchedPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * get all permissions
     */
    public static function getAllPermissionsOfAdmin(?Admin $user = null)
    {
        $user = self::getAdminIfPermitted($user);

        return $user->getAllPermissions();
    }

    public static function getAllPermissionsOfCustomer(?Contact $user = null)
    {
        $user = self::getCustomerIfPermitted($user);

        return $user->getAllPermissions();
    }

    // Admin only

    public static function getAllowedAdminRoleNames(?Admin $user = null)
    {
        $user = UserRoleService::getAdminIfPermitted($user);
        $allowedRoleNamesToView = [];
        foreach (RoleService::getRoleNames() as $roleName) {
            // $roleViewPermission = $prefix.PermissionService::PERMISSION_PART_SEPARATOR.'Role'.$roleName;
            $roleViewPermission = PermissionService::createRolePermissionNames($roleName, PermissionService::PREFIX_VIEW);
            if (UserRoleService::adminHasPermission($roleViewPermission, $user)) {
                $allowedRoleNamesToView[] = $roleName;
            }
        }

        return $allowedRoleNamesToView;
    }

    public static function recreateRegisteredCustomerRoleHasPermissions(): void
    {
        $registeredCustomerRole = Role::where(['name' => RoleService::ROLE_REGISTERED_CUSTOMER])->first();
        if ($registeredCustomerRole instanceof Role) {
            $registeredCustomerRole->permissions()->detach();
            foreach (Permission::where(['guard_name' => UserService::GUARD_CUSTOMER])->get() as $permission) {
                if ($permission instanceof Permission) {
                    $registeredCustomerRole->permissions()->attach($permission->id);
                }
            }
        }
    }

    public static function recreateSuperAdminRoleHasPermissions(): void
    {
        $superAdminRole = Role::where(['name' => RoleService::ROLE_SUPER_ADMIN])->first();
        if ($superAdminRole instanceof Role) {
            $superAdminRole->permissions()->detach();
            foreach (Permission::where(['guard_name' => UserService::GUARD_ADMIN])->get() as $permission) {
                if ($permission instanceof Permission) {
                    $superAdminRole->permissions()->attach($permission->id);
                }
            }

            $superAdminRole->save();

            return;
        }

        throw new Exception('SuperAdmin role not found in the database!');
    }
}
