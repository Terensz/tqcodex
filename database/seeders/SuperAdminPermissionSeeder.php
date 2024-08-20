<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Domain\User\Services\UserRoleService;
use Illuminate\Database\Seeder;

class SuperAdminPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserRoleService::recreateSuperAdminRoleHasPermissions();
    }
}
