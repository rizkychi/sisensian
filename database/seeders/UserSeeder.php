<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@sisensian.com',
                'password' => Hash::make('123'),
                'role' => 'superadmin',
            ],
            // [
            //     'username' => 'dummy',
            //     'email' => 'dummy@sisensian.com',
            //     'password' => Hash::make('123'),
            //     'role' => 'user',
            // ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
