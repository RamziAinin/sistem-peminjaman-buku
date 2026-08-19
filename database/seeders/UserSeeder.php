<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Operator Perpus',
            'email' => 'admin@perpus.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Paduka Roni',
            'email' => 'roni@gmail.com',
            'password' => Hash::make('roni123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Edward',
            'email' => 'edward@gmail.com',
            'password' => Hash::make('edward123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Perpus',
            'email' => 'dinarpuspkl@gmail.com',
            'password' => Hash::make('dinarpus2026'),
            'role' => 'admin',
        ]);
    }
}