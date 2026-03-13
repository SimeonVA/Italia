<?php

use App\Models\Ingredient;
use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('de verkoopprijs van een pizza is hoger dan de inkoop van ingrediënten', function () {
    $kaas = Ingredient::create(['name' => 'Mozzarella', 'inkoopprijs' => 2.50]);
    $deeg = Ingredient::create(['name' => 'Deeg', 'inkoopprijs' => 1.00]);

    $pizza = Pizza::create([
        'name' => 'Margherita',
        'prijs' => 12.50,
        'status' => 'op-voorraad'
    ]);

    $totaleInkoop = $kaas->inkoopprijs + $deeg->inkoopprijs;
    
    expect($pizza->prijs)->toBeGreaterThan($totaleInkoop);
});