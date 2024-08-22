<?php

namespace Domain\Admin\Controllers\Base;

use Domain\Admin\Models\Admin;
use Domain\User\Controllers\Base\BaseUserManagementController;
use Domain\User\Services\UserService;

class BaseAdminController extends BaseUserManagementController
{
    // public function getUserObject(): User
    // {
    //     return UserService::getUser(UserService::ROLE_TYPE_ADMIN);
    // }

    public function getAdminData(): array
    {
        $admin = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        return [
            'name' => $admin && $admin instanceof Admin ? $admin->name : null,
            'email' => $admin && $admin instanceof Admin ? $admin->email : null,
        ];
    }

    public function getMenuData(): array
    {
        return [
            [
                'title' => 'ManageContent',
                'items' => [
                    [
                        'routeName' => 'admin.entry-list',
                        'title' => 'EntryList',
                    ],
                ],
            ],
            [
                'title' => 'ManageAdmins',
                'items' => [
                    [
                        'routeName' => 'admin.admin-list',
                        'title' => 'AdminList',
                    ],
                ],
            ],
        ];
    }
}
