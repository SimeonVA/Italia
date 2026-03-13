<?php

use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);   

test('een pizza moet een naam hebben', function () {
    $this->expectException(QueryException::class);

    Pizza::create([
        'prijs' => 10.00,
        'status' => 'op-voorraad'
    ]);
});

test('pizza namen moeten uniek zijn', function () {
    Pizza::create([
        'name' => 'Margherita',
        'prijs' => 10.00,
    ]);

    $this->expectException(QueryException::class);

    Pizza::create([
        'name' => 'Margherita',
        'prijs' => 12.00,
    ]);
});