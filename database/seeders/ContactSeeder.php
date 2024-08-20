<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\Customer\ContactFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContactSeeder extends Seeder
{
    public $count = 60;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = ContactFactory::new()->createUntilNotTaken([
            'firstname' => 'István',
            'lastname' => 'Holbok',
            'email' => 'admin@trianity.dev',
            'email_verified_at' => now(),
            'password' => Hash::make('Test#88aa'),
        ]);

        $user2 = ContactFactory::new()->createUntilNotTaken([
            'firstname' => 'Ferenc',
            'lastname' => 'Papp',
            'email' => 'terencecleric@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Alma5167429Alma'),
        ]);

        for ($i = 0; $i < $this->count; $i++) {
            ContactFactory::new()->createUntilNotTaken();
        }
    }
}
