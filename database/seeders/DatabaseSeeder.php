<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //UserSeeder::class,
            CountrySeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            SuperAdminPermissionSeeder::class,
            CustomerPermissionSeeder::class,
        ]);

        /**
         * @todo: if dev env
         */
        if (app()->environment('local') || app()->environment('testing')) {
            $this->call([
                UserSeeder::class,
                ContactSeeder::class,
                OrganizationSeeder::class,
                CompensationItemSeeder::class,
                CommunicationSeeder::class,
            ]);
        }
    }
}

// Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
// Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
// Artisan::call('db:seed', ['--class' => 'SuperAdminPermissionSeeder']);
// Artisan::call('db:seed', ['--class' => 'UserRoleSeeder']);
