<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Domain\User\Models\Permission;
use Domain\User\Services\PermissionSeederService;
use Domain\User\Services\PermissionService;
use Domain\User\Services\UserService;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $initialPermissionSeederData = PermissionSeederService::getInitialPermissionSeederData();
        // dump('PermissionSeeder');
        // dump($initialPermissionSeederData);
        foreach (UserService::GUARDS as $guardName) {
            if (isset($initialPermissionSeederData[$guardName])) {
                foreach ($initialPermissionSeederData[$guardName] as $suffix => $suffixConfigRow) {
                    foreach ($suffixConfigRow[PermissionService::POSSIBLE_PREFIXES] as $prefix) {
                        $permissionName = PermissionService::createPermissionName($prefix, $suffix);
                        // dump($permissionName);
                        Permission::create([
                            'name' => $permissionName,
                            'guard_name' => $guardName,
                        ]);
                    }
                }
            }
        }
    }
}
