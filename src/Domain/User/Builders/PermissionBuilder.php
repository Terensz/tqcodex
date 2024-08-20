<?php

namespace Domain\User\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Models\Permission;
use Domain\User\Services\UserService;

class PermissionBuilder extends BaseBuilder
{
    /**
     * Admin can list all Permissions, regardless of anything.
     */
    public function listableByAdmin()
    {
        return $this;
    }

    /**
     * Customers can only "list" their own Permissions.
     */
    public function listableByCustomer()
    {
        $contact = UserService::getContact();

        return $this
            ->valid()
            ->where('id', $contact ? $contact->id : null);
    }

    /**
     * Permissions are always valid.
     */
    public function valid()
    {
        return $this;
    }

    // public static function getListQuery()
    // {
    //     return Permission::query();
    // }
}
