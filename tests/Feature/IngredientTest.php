<?php

use App\Models\Ingredient;
use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ingredient wordt correct opgeslagen', function () {
    $ingredient = Ingredient::factory()->create([
        'name' => 'Mozzarella',
        'inkoopprijs' => 2.50,
    ]);
    
    expect($ingredient->name)->toBe('Mozzarella');
    expect($ingredient->inkoopprijs)->toBe(2.50);
});

test('ingredient kan bij meerdere pizzas gebruikt worden', function () {
    $mozzarella = Ingredient::factory()->create();
    
    $margherita = Pizza::factory()->create();
    $pepperoni = Pizza::factory()->create();
    
    $mozzarella->pizzas()->attach([$margherita->id, $pepperoni->id]);
    
    expect($mozzarella->pizzas)->toHaveCount(2);
});