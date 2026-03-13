<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pizza; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Pizza::create([
            'name' => 'Margherita',
            'beschrijving' => 'De klassieker met tomatensaus en mozzarella.',
            'prijs' => 10.00,
            'status' => 'op-voorraad',
        ]);
    }
}