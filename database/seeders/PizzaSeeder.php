<?php

namespace Database\Seeders;

use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class PizzaSeeder extends Seeder
{
    public function run(): void
    {
        $tomaat = Ingredient::where('name', 'Tomatensaus')->first();
        $kaas = Ingredient::where('name', 'Mozzarella')->first();
        $basilicum = Ingredient::where('name', 'Basilicum')->first();
        $pepperoni = Ingredient::where('name', 'Pepperoni')->first();
        $champignons = Ingredient::where('name', 'Champignons')->first();
        $paprika = Ingredient::where('name', 'Paprika')->first();
        $ui = Ingredient::where('name', 'Ui')->first();
        $olijven = Ingredient::where('name', 'Olijven')->first();
        $ham = Ingredient::where('name', 'Ham')->first();
        $ananas = Ingredient::where('name', 'Ananas')->first();

        $pizzas = [
            [
                'name' => 'Margherita',
                'beschrijving' => 'Klassieke pizza met tomaat, mozzarella en basilicum',
                'prijs' => 8.50,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $basilicum->id],
            ],
            [
                'name' => 'Pepperoni',
                'beschrijving' => 'Pizza met tomaat, mozzarella en pepperoni',
                'prijs' => 10.00,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $pepperoni->id],
            ],
            [
                'name' => 'Funghi',
                'beschrijving' => 'Pizza met tomaat, mozzarella en champignons',
                'prijs' => 9.50,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $champignons->id],
            ],
            [
                'name' => 'Quattro Stagioni',
                'beschrijving' => 'Pizza met tomaat, mozzarella, ham, champignons en olijven',
                'prijs' => 12.00,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $ham->id, $champignons->id, $olijven->id],
            ],
            [
                'name' => 'Vegetariana',
                'beschrijving' => 'Pizza met tomaat, mozzarella, paprika, champignons en ui',
                'prijs' => 11.00,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $paprika->id, $champignons->id, $ui->id],
            ],
            [
                'name' => 'Hawaii',
                'beschrijving' => 'Pizza met tomaat, mozzarella, ham en ananas',
                'prijs' => 10.50,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $ham->id, $ananas->id],
            ],
            [
                'name' => 'Diavola',
                'beschrijving' => 'Pikante pizza met tomaat, mozzarella en pepperoni',
                'prijs' => 11.50,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $pepperoni->id, $paprika->id],
            ],
            [
                'name' => 'Capricciosa',
                'beschrijving' => 'Pizza met tomaat, mozzarella, ham en champignons',
                'prijs' => 11.00,
                'status' => 'op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $ham->id, $champignons->id],
            ],
            [
                'name' => 'Speciale',
                'beschrijving' => 'Pizza met alles erop en eraan',
                'prijs' => 13.50,
                'status' => 'in concept',
                'ingredients' => [$tomaat->id, $kaas->id, $pepperoni->id, $ham->id, $champignons->id, $paprika->id],
            ],
            [
                'name' => 'Calzone',
                'beschrijving' => 'Gevouwen pizza met tomaat, mozzarella en ham',
                'prijs' => 10.00,
                'status' => 'niet-op-voorraad',
                'ingredients' => [$tomaat->id, $kaas->id, $ham->id],
            ],
        ];

        foreach ($pizzas as $data) {
            $ingredients = $data['ingredients'];
            unset($data['ingredients']);
            
            $pizza = Pizza::create($data);
            $pizza->ingredients()->attach($ingredients);
        }
    }
}