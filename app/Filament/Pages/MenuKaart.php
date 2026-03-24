<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pizza;

class MenuKaart extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text'; 
    protected static ?string $navigationLabel = 'Menukaart';
    protected static ?string $slug = 'menu-kaart';
    protected string $view = 'filament.pages.menu-kaart';

    public array $pizzas = [];

    public function mount(): void
    {
        $this->pizzas = Pizza::where('status', 'op-voorraad')->get()->toArray();
    }

    public array $cart = [];

// Computed property voor het totaalbedrag
public function getTotalProperty()
{
    return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
}

public function addToCart($pizzaId)
{
    $pizza = Pizza::find($pizzaId);
    
    if (!isset($this->cart[$pizzaId])) {
        $this->cart[$pizzaId] = [
            'name' => $pizza->name,
            'price' => $pizza->price,
            'quantity' => 1,
        ];
    } else {
        $this->cart[$pizzaId]['quantity']++;
    }

    session()->put('cart', $this->cart);
    
    Notification::make()
        ->title($pizza->name . ' toegevoegd!')
        ->success()
        ->send();
} 
}