<?php

namespace Domain\User\Services;

use Domain\Project\Services\PermissionSeederDataService;

class PermissionSeederService
{
    public static function getInitialPermissionSeederData()
    {
        // $rolePermissionSeederData = [];

        // foreach (RoleService::ROLES as $roleType => $roleTypeRoles) {
        //     $guardName = UserService::getGuardName($roleType);
        //     foreach ($roleTypeRoles as $roleName) {
        //         $rolePermissionSeederData[$guardName]['User'.$roleName] = [
        //             PermissionService::POSSIBLE_PREFIXES => [
        //                 PermissionService::PREFIX_VIEW,
        //                 PermissionService::PREFIX_CREATE,
        //                 PermissionService::PREFIX_EDIT,
        //                 PermissionService::PREFIX_DELETE,
        //             ],
        //         ];
        //         $rolePermissionSeederData[$guardName]['Role'.$roleName] = [
        //             PermissionService::POSSIBLE_PREFIXES => [
        //                 PermissionService::PREFIX_VIEW,
        //                 PermissionService::PREFIX_CREATE,
        //                 PermissionService::PREFIX_EDIT,
        //                 PermissionService::PREFIX_DELETE,
        //             ],
        //         ];
        //     }
        // }

        return array_merge_recursive(
            PermissionSeederDataService::INITIAL_PERMISSION_SEEDER_DATA
        );
    }
}
