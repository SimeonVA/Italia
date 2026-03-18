<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Tomatensaus', 'inkoopprijs' => 0.50],
            ['name' => 'Mozzarella', 'inkoopprijs' => 1.50],
            ['name' => 'Basilicum', 'inkoopprijs' => 0.30],
            ['name' => 'Pepperoni', 'inkoopprijs' => 2.00],
            ['name' => 'Champignons', 'inkoopprijs' => 1.20],
            ['name' => 'Paprika', 'inkoopprijs' => 0.80],
            ['name' => 'Ui', 'inkoopprijs' => 0.40],
            ['name' => 'Olijven', 'inkoopprijs' => 1.00],
            ['name' => 'Ham', 'inkoopprijs' => 1.80],
            ['name' => 'Ananas', 'inkoopprijs' => 0.90],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}