<?php

use App\Models\Ingredient;
use App\Models\Pizza;

test('ingredient heeft naam en inkoopprijs', function () {
    $ingredient = Ingredient::factory()->create();
    
    expect($ingredient->name)->not->toBeEmpty();
    expect($ingredient->inkoopprijs)->toBeGreaterThan(0);
});

test('ingredient kan aan meerdere pizzas gekoppeld worden', function () {
    $ingredient = Ingredient::factory()->create();
    $pizzas = Pizza::factory()->count(3)->create();
    
    $ingredient->pizzas()->attach($pizzas);
    
    expect($ingredient->pizzas)->toHaveCount(3);
});