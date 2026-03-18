<?php

use App\Models\Order;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('order kan worden aangemaakt', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    
    expect($order->status)->toBe('pending');
});

test('pizzas kunnen aan order toegevoegd worden', function () {
    $order = Order::factory()->create();
    $pizza = Pizza::factory()->create();
    
    $order->pizzas()->attach($pizza->id, ['quantity' => 3]);
    
    expect($order->pizzas)->toHaveCount(1);
    expect($order->pizzas->first()->pivot->quantity)->toBe(3);
});

test('alleen voltooide orders tellen mee voor omzet', function () {
    $pending = Order::factory()->create(['status' => 'pending']);
    $completed = Order::factory()->create(['status' => 'completed']);
    
    $pizza = Pizza::factory()->create(['prijs' => 10.00]);
    
    $pending->pizzas()->attach($pizza->id, ['quantity' => 1]);
    $completed->pizzas()->attach($pizza->id, ['quantity' => 1]);
    
    $pending->refresh();
    $completed->refresh();
    
    expect($pending->revenue)->toBe(0.0);
    expect($completed->revenue)->toBe(10.0);
});

test('bestellingen van vandaag worden correct geteld', function () {
    Order::factory()->create(['created_at' => now()]);
    Order::factory()->create(['created_at' => now()->subHours(2)]);
    
    Order::factory()->create(['created_at' => now()->subDay()]);
    
    $vandaag = Order::whereDate('created_at', now()->toDateString())->get();
    
    expect($vandaag)->toHaveCount(2);
});

test('lopende bestellingen worden correct getoond', function () {
    Order::factory()->create(['status' => 'pending']);
    Order::factory()->create(['status' => 'preparing']);
    Order::factory()->create(['status' => 'ready']);
    Order::factory()->create(['status' => 'completed']);
    
    $lopend = Order::whereIn('status', ['pending', 'preparing', 'ready'])->get();
    
    expect($lopend)->toHaveCount(3);
});

test('winst wordt correct berekend', function () {
    $order = Order::factory()->create(['status' => 'completed']);
    $pizza = Pizza::factory()->create(['prijs' => 10.00]);
    
    $ingredient = Ingredient::factory()->create(['inkoopprijs' => 3.00]);
    $pizza->ingredients()->attach($ingredient->id);
    
    $order->pizzas()->attach($pizza->id, ['quantity' => 1]);
    $order->refresh();
    
    expect($order->revenue)->toBe(10.0);
    expect($order->cost)->toBe(3.0);
    expect($order->profit)->toBe(7.0);
});