<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'inkoopprijs' => fake()->randomFloat(2, 0.5, 5),
        ];
    }
}