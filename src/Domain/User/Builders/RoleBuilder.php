<?php

namespace Domain\User\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Models\Role;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;

class RoleBuilder extends BaseBuilder
{
    /**
     * Admin can list Roles, regardless of anything.
     */
    public function listableByAdmin()
    {
        $allowedRoleNames = UserRoleService::getAllowedAdminRoleNames();

        return $this->whereIn('name', $allowedRoleNames);
    }

    /**
     * Customers cannot list Roles.
     */
    public function listableByCustomer()
    {
        return $this
            ->valid()
            ->where('id', null);
    }

    public function valid()
    {
        return $this;
    }

    public function adminRoles()
    {
        $user = UserRoleService::getAdminIfPermitted();

        return $this
            ->where('guard_name', UserService::GUARD_ADMIN)
            ->whereIn('name', UserRoleService::getAllowedAdminRoleNames($user));
        // ->orWhereDoesntHave('roles');
    }

    public function customerRoles()
    {
        return $this
            ->where('guard_name', UserService::GUARD_CUSTOMER);
        // ->whereIn('name', UserRoleService::getAllowedRoleNames($user))
    }

    // public static function getListQuery(?User $user = null)
    // {
    //     $user = UserRoleService::getAdminIfPermitted($user);
    //     $allowedRoleNames = UserRoleService::getAllowedAdminRoleNames($user);

    //     return Role::query()->whereIn('name', $allowedRoleNames);
    // }

    // public static function getListQueryAll()
    // {
    //     return Role::query();
    // }

    // public static function getAdminRolesQuery(?User $user = null)
    // {
    //     $user = UserRoleService::getAdminIfPermitted($user);

    //     return Role::query()
    //         ->where('guard_name', UserService::GUARD_ADMIN)
    //         ->whereIn('name', UserRoleService::getAllowedAdminRoleNames($user));
    //     // ->orWhereDoesntHave('roles');
    // }

    // public static function getCustomerRolesQuery(?User $user = null)
    // {
    //     $user = UserRoleService::getAdminIfPermitted($user);

    //     return Role::query()
    //         ->where('guard_name', UserService::GUARD_CUSTOMER);
    //     // ->whereIn('name', UserRoleService::getAllowedRoleNames($user))
    // }
}
