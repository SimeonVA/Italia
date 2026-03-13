<?php

use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('een bezoeker kan de pizza(s) zien', function () {
    $this->withoutMiddleware();

    Pizza::create([
        'name' => 'Margherita',
        'prijs' => 12.50,
    ]);

    $response = $this->get('/admin/menu-kaart');

    $response->assertStatus(200);
    $response->assertSee('Margherita');
});

test('de menukaart toont alleen pizza’s die op voorraad zijn', function () {
    $this->withoutMiddleware();

    Pizza::create([
        'name' => 'Margherita',
        'beschrijving' => 'Lekkere kaas pizza',
        'prijs' => 12.50,
        'status' => 'op-voorraad',
    ]);

    Pizza::create([
        'name' => 'Geheime Pizza',
        'beschrijving' => 'Nog niet klaar',
        'prijs' => 15.00,
        'status' => 'in concept',
    ]);

    $response = $this->get('/admin/menu-kaart');

    $response->assertStatus(200);
    $response->assertSee('Margherita');
    $response->assertDontSee('Geheime Pizza');
});