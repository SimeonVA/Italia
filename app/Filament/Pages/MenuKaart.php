<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Pizza;
use App\Models\Order;
use App\Models\OrderItem;

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

    public string $customerName = '';

    public function placeOrder(): void
    {
        if (empty($this->cart)) return;
        if (empty($this->customerName)) {
            Notification::make()
                ->title('Vul een naam in!')
                ->danger()
                ->send();
            return;
        }

        $customer = Customer::firstOrCreate(
            ['name' => $this->customerName]
        );

        $order = Order::create([
            'status'      => 'pending',
            'customer_id' => $customer->id,
            'created_by'  => auth()->id(),
        ]);

        foreach ($this->cart as $pizzaId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'pizza_id' => $pizzaId,
                'quantity' => $item['quantity'],
                'price'    => $item['price'],
            ]);
        }

        $this->cart = [];
        $this->customerName = '';
        session()->forget('cart');

        Notification::make()
            ->title('Bestelling geplaatst!')
            ->success()
            ->send();
    }
}