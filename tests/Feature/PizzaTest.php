<?php

use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('alleen pizzas op voorraad zijn zichtbaar', function () {
    Pizza::factory()->create(['status' => 'op-voorraad']);
    Pizza::factory()->create(['status' => 'in concept']);
    
    $zichtbaar = Pizza::available()->get();
    
    expect($zichtbaar)->toHaveCount(1);
});

test('pizza heeft de benodigde velden', function () {
    $pizza = Pizza::factory()->create([
        'name' => 'Margherita',
        'prijs' => 8.50,
    ]);
    
    expect($pizza->name)->toBe('Margherita');
    expect($pizza->prijs)->toBe(8.50);
});

test('pizza kan ingrediënten toegevoegd krijgen', function () {
    $pizza = Pizza::factory()->create();
    $ingredient = Ingredient::factory()->create();
    
    $pizza->ingredients()->attach($ingredient->id);
    
    expect($pizza->ingredients)->toHaveCount(1);
});

test('kostprijs wordt berekend', function () {
    $pizza = Pizza::factory()->create();
    
    $kaas = Ingredient::factory()->create(['inkoopprijs' => 1.50]);
    $tomaat = Ingredient::factory()->create(['inkoopprijs' => 0.50]);
    
    $pizza->ingredients()->attach([$kaas->id, $tomaat->id]);
    $pizza->refresh();
    
    expect($pizza->cost_price)->toBe(2.00);
});