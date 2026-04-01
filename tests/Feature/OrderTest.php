<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pizza;
use App\Models\Customer;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('order behoort toe aan een klant', function () {
    $customer = Customer::factory()->create();
    $order = Order::factory()->create(['customer_id' => $customer->id]);

    expect($order->customer->id)->toBe($customer->id);
});

test('order heeft order items', function () {
    $order = Order::factory()->create();
    $pizza = Pizza::factory()->create(['prijs' => 10.00]);

    OrderItem::create([
        'order_id' => $order->id,
        'pizza_id' => $pizza->id,
        'quantity' => 2,
        'price'    => 10.00,
    ]);

    expect($order->orderItems)->toHaveCount(1);
    expect($order->orderItems->first()->quantity)->toBe(2);
});

test('alleen voltooide orders tellen mee voor omzet', function () {
    $pending   = Order::factory()->create(['status' => 'pending']);
    $completed = Order::factory()->create(['status' => 'completed']);
    $pizza     = Pizza::factory()->create(['prijs' => 10.00]);

    OrderItem::create(['order_id' => $pending->id,   'pizza_id' => $pizza->id, 'quantity' => 1, 'price' => 10.00]);
    OrderItem::create(['order_id' => $completed->id, 'pizza_id' => $pizza->id, 'quantity' => 1, 'price' => 10.00]);

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

    $lopend = Order::pending()->get();

    expect($lopend)->toHaveCount(3);
});

test('winst wordt correct berekend', function () {
    $order      = Order::factory()->create(['status' => 'completed']);
    $pizza      = Pizza::factory()->create(['prijs' => 10.00]);
    $ingredient = Ingredient::factory()->create(['inkoopprijs' => 3.00]);

    $pizza->ingredients()->attach($ingredient->id);

    OrderItem::create([
        'order_id' => $order->id,
        'pizza_id' => $pizza->id,
        'quantity' => 1,
        'price'    => 10.00,
    ]);

    $order->refresh();

    expect($order->revenue)->toBe(10.0);
    expect($order->profit)->toBe(7.0);
});