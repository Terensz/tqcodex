<?php

namespace Domain\Admin\Controllers\Base;

use Domain\Admin\Models\User;
use Domain\User\Controllers\Base\BaseUserManagementController;
use Domain\User\Services\UserService;

class BaseAdminController extends BaseUserManagementController
{
    // public function getUserObject(): User
    // {
    //     return UserService::getUser(UserService::ROLE_TYPE_ADMIN);
    // }

    public function getUserData(): array
    {
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        return [
            'name' => $user && $user instanceof User ? $user->name : null,
            'email' => $user && $user instanceof User ? $user->email : null,
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
                'title' => 'ManageUsers',
                'items' => [
                    [
                        'routeName' => 'admin.user-list',
                        'title' => 'UserList',
                    ],
                ],
            ],
        ];
    }
}
