<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            [
                'name' => 'Jonathan',
                'email' => 'jonathan@test.com',
                'password' => 'test12345',
            ],
            [
                'name' => 'Misellin',
                'email' => 'misellinkwok@gmail.com',
                'password' => 'cemara88',
            ]
        ];
    
        User::factory()->createMany($users);

    }
}
