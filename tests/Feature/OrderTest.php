<?php

use App\Models\Order;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('kan een order aanmaken', function () {
    $order = Order::factory()->create();
    
    expect($order->status)->toBe('pending');
});

test('kan pizzas toevoegen aan order', function () {
    $order = Order::factory()->create();
    $pizza = Pizza::factory()->create();
    
    $order->pizzas()->attach($pizza->id, ['quantity' => 2]);
    
    expect($order->pizzas)->toHaveCount(1);
});

test('order berekent omzet voor completed orders', function () {
    $order = Order::factory()->create(['status' => 'completed']);
    $pizza = Pizza::factory()->create(['prijs' => 10.00]);
    
    $order->pizzas()->attach($pizza->id, ['quantity' => 2]);
    $order->refresh();
    
    expect($order->revenue)->toBe(20.0);
});

test('order berekent geen omzet voor pending orders', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    $pizza = Pizza::factory()->create(['prijs' => 10.00]);
    
    $order->pizzas()->attach($pizza->id, ['quantity' => 2]);
    $order->refresh();
    
    expect($order->revenue)->toBe(0.0);
});