<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Pizza;
use Illuminate\Support\Facades\Storage;

class MenuKaart extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Menukaart';
    protected static ?string $slug = 'menu-kaart';
    protected string $view = 'filament.pages.menu-kaart';

    public array $pizzas = [];
    public array $cart = [];

    public function mount(): void
    {
        $this->pizzas = Pizza::where('status', 'op-voorraad')->get()->toArray();
        $this->cart = session()->get('cart', []);
    }

    public function getTotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function addToCart(int $pizzaId): void
    {
        $pizza = Pizza::find($pizzaId);

        if (!$pizza) return;

        if (!isset($this->cart[$pizzaId])) {
            $this->cart[$pizzaId] = [
                'name'     => $pizza->name,
                'price'    => $pizza->prijs,
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

    public function removeFromCart(int $pizzaId): void
    {
        if (isset($this->cart[$pizzaId])) {
            if ($this->cart[$pizzaId]['quantity'] > 1) {
                $this->cart[$pizzaId]['quantity']--;
            } else {
                unset($this->cart[$pizzaId]);
            }
            session()->put('cart', $this->cart);
        }
    }
}