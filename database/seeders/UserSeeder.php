<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'SimeonVA',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Mario Rossi',
            'email' => 'mario@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => false, 
        ]);
    }
}