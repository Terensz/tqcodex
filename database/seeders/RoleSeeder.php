<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Domain\User\Models\Role;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserService;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (RoleService::ROLES as $roleType => $roleTypeRoles) {
            $guardName = UserService::getGuardName($roleType);
            foreach ($roleTypeRoles as $roleName) {
                // dump($roleName);
                Role::create([
                    'name' => $roleName,
                    'guard_name' => $guardName,
                ]);
            }
        }
    }
}
