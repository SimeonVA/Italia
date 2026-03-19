<?php

use App\Models\Ingredient;
use App\Models\User;
use App\Filament\Resources\IngredientResource\Pages\CreateIngredient;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create ingredient', function () {
    $user = User::factory()->create();
    
    \Livewire\Livewire::actingAs($user)
        ->test(CreateIngredient::class)
        ->fillForm([
            'name' => 'Mozzarella',
            'inkoopprijs' => 2.50,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Ingredient::class, [
        'name' => 'Mozzarella',
        'inkoopprijs' => 2.50,
    ]);
});