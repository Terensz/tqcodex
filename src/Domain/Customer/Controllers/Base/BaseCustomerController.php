<?php

namespace Domain\Customer\Controllers\Base;

use Domain\Customer\Models\Contact;
use Domain\User\Controllers\Base\BaseUserManagementController;
use Domain\User\Services\UserService;

abstract class BaseCustomerController extends BaseUserManagementController
{
    // public function getUserObject(): User
    // {
    //     return UserService::getUser(UserService::ROLE_TYPE_ADMIN);
    // }

    public function getUserData(): array
    {
        $user = UserService::getUser(UserService::ROLE_TYPE_CUSTOMER);

        return [
            'name' => $user ? $user->getNameAttribute() : null,
            'email' => $user ? $user->getEmail() : null,
        ];

        // return [
        //     'name' => $user && $user instanceof Contact ? $user->name : null,
        //     'email' => $user && $user instanceof Contact ? $user->email : null,
        // ];

        // return [
        //     'name' => null,
        //     'email' => null,
        // ];
    }

    // public function getMenuData(): array
    // {
    //     return [
    //         [
    //             'title' => 'ManageContent',
    //             'items' => [
    //                 [
    //                     'routeName' => 'admin.entry-list',
    //                     'title' => 'EntryList',
    //                 ],
    //             ],
    //         ],
    //         [
    //             'title' => 'ManageUsers',
    //             'items' => [
    //                 [
    //                     'routeName' => 'customer.user-list',
    //                     'title' => 'UserList',
    //                 ],
    //             ],
    //         ],
    //     ];
    // }
}
