<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Pizza;
use Carbon\Carbon;

class MenuKaart extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Menukaart';
    protected static ?string $slug = 'menu-kaart';
    protected string $view = 'filament.pages.menu-kaart';

    public array $pizzas = [];
    public array $cart = [];
    public string $customerName = '';

    public function mount(): void
    {
        $this->pizzas = Pizza::where('status', 'op-voorraad')->get()->toArray();
        $this->cart = session()->get('cart', []);
    }

    public function isOpen(): bool
    {
        $nu = Carbon::now();
        return $nu->between(
            Carbon::today()->setTimeFromTimeString('12:00'),
            Carbon::today()->setTimeFromTimeString('21:30')
        );
    }

    public function getVerwachteTijdProperty(): string
    {
        return Carbon::now()->addMinutes(30)->format('H:i');
    }

    public function getTotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function addToCart(int $pizzaId): void
    {
        if (!$this->isOpen()) {
            $this->gesloten();
            return;
        }

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
        if (!isset($this->cart[$pizzaId])) return;

        if ($this->cart[$pizzaId]['quantity'] > 1) {
            $this->cart[$pizzaId]['quantity']--;
        } else {
            unset($this->cart[$pizzaId]);
        }

        session()->put('cart', $this->cart);
    }

    public function placeOrder(): void
    {
        if (empty($this->cart)) return;

        if (!$this->isOpen()) {
            $this->gesloten();
            return;
        }

        redirect()->to('/admin/orders/create');
    }

    private function gesloten(): void
    {
        Notification::make()
            ->title('Gesloten!')
            ->body('We zijn momenteel gesloten. Openingstijden: 15:00 – 21:30')
            ->danger()
            ->send();
    }
}