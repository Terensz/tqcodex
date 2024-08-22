<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Domain\Admin\Models\Admin;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::create([
            'firstname' => 'Balázs',
            'lastname' => 'Kelemen',
            'email' => 'kelbal@gmail.com',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('Test#88aa'),
            'remember_token' => Str::random(16),
        ]);

        $user1 = Admin::where(['email' => 'admin@trianity.dev'])->first();
        if ($user1 instanceof Admin) {
            UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user1);
        }

        Admin::create([
            'firstname' => 'Ferenc',
            'lastname' => 'Papp',
            'email' => 'terencecleric@gmail.com',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('Alma5167429Alma'),
            'remember_token' => Str::random(16),
        ]);

        $user2 = Admin::where(['email' => 'terencecleric@gmail.com'])->first();
        if ($user2 instanceof Admin) {
            UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user2);
        }

        \Domain\Admin\Models\Admin::factory(10)->createUntilNotTaken();
    }
}
