<?php

use App\Models\Pizza;
use App\Models\Ingredient;
use App\Models\User;
use App\Filament\Resources\PizzaResource\Pages\CreatePizza;
use App\Filament\Resources\PizzaResource\Pages\EditPizza;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('alleen pizzas op voorraad zijn zichtbaar', function () {
    Pizza::factory()->create(['status' => 'op-voorraad']);
    Pizza::factory()->create(['status' => 'in concept']);
    
    $zichtbaar = Pizza::available()->get();
    
    expect($zichtbaar)->toHaveCount(1);
});

test('kan pizza aanmaken via filament', function () {
    $user = User::factory()->create();
    
    \Livewire\Livewire::actingAs($user)
        ->test(CreatePizza::class)
        ->fillForm([
            'name' => 'Margherita',
            'beschrijving' => 'Klassieke pizza',
            'prijs' => 8.50,
            'status' => 'op-voorraad',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('pizzas', [
        'name' => 'Margherita',
        'prijs' => 8.50,
    ]);
});

test('kan ingrediënten toevoegen aan bestaande pizza', function () {
    $user = User::factory()->create();
    $pizza = Pizza::create([
        'name' => 'Margherita',
        'beschrijving' => 'Test',
        'prijs' => 8.50,
        'status' => 'op-voorraad',
    ]);
    
    $ingredient = Ingredient::create([
        'name' => 'Mozzarella',
        'inkoopprijs' => 1.50,
    ]);
    
    \Livewire\Livewire::actingAs($user)
        ->test(\App\Filament\Resources\PizzaResource\Pages\EditPizza::class, ['record' => $pizza->id])
        ->set('data.ingredients', [$ingredient->id])
        ->call('save')
        ->assertHasNoFormErrors();
    
    $pizza->refresh();
    expect($pizza->ingredients)->toHaveCount(1);
});

test('pizza heeft de benodigde velden', function () {
    $pizza = Pizza::factory()->create([
        'name' => 'Margherita',
        'prijs' => 8.50,
    ]);
    
    expect($pizza->name)->toBe('Margherita');
    expect($pizza->prijs)->toBe(8.50);
});

test('kostprijs wordt berekend', function () {
    $pizza = Pizza::factory()->create();
    
    $kaas = Ingredient::factory()->create(['inkoopprijs' => 1.50]);
    $tomaat = Ingredient::factory()->create(['inkoopprijs' => 0.50]);
    
    $pizza->ingredients()->attach([$kaas->id, $tomaat->id]);
    $pizza->refresh();
    
    expect($pizza->cost_price)->toBe(2.00);
});