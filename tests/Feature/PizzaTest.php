<?php

use App\Models\Pizza;
use App\Models\Ingredient;

test('alleen pizzas op voorraad staan op menukaart', function () {
    Pizza::factory()->create(['status' => 'op-voorraad']);
    Pizza::factory()->create(['status' => 'in concept']);
    Pizza::factory()->create(['status' => 'niet-op-voorraad']);
    
    expect(Pizza::available()->count())->toBe(1);
});

test('pizza heeft naam beschrijving en prijs', function () {
    $pizza = Pizza::factory()->create();
    
    expect($pizza->name)->not->toBeEmpty();
    expect($pizza->beschrijving)->not->toBeEmpty();
    expect($pizza->prijs)->toBeGreaterThan(0);
});

test('pizza kan ingrediënten hebben', function () {
    $pizza = Pizza::factory()->create();
    $ingredients = Ingredient::factory()->count(3)->create();
    
    $pizza->ingredients()->attach($ingredients);
    
    expect($pizza->ingredients)->toHaveCount(3);
});

test('pizza kostprijs wordt correct berekend', function () {
    $pizza = Pizza::factory()->create();
    $ingredient1 = Ingredient::factory()->create(['inkoopprijs' => 1.50]);
    $ingredient2 = Ingredient::factory()->create(['inkoopprijs' => 2.00]);
    
    $pizza->ingredients()->attach([$ingredient1->id, $ingredient2->id]);
    $pizza->refresh();
    
    expect($pizza->cost_price)->toBe(3.50);
});