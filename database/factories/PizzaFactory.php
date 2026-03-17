<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PizzaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'beschrijving' => fake()->sentence(),
            'prijs' => fake()->randomFloat(2, 5, 15),
            'status' => 'op-voorraad',
        ];
    }
}