<?php

namespace App\Filament\Resources\OrderResource\Schemas;

use App\Models\Customer;
use App\Models\Pizza;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $cart = session()->get('cart', []);

        $cartItems = collect($cart)->map(fn($item, $id) => [
            'pizza_id' => (int) $id,
            'quantity' => $item['quantity'],
            'price'    => $item['price'],
        ])->values()->toArray();

        return $schema->components([
            TextInput::make('customer_name')
                ->label('Naam')
                ->required(),

            TextInput::make('customer_email')
                ->label('E-mailadres')
                ->email()
                ->required(),

            TextInput::make('customer_phone')
                ->label('Telefoonnummer')
                ->tel()
                ->required(),

            TextInput::make('customer_address')
                ->label('Adres')
                ->required(),

            Select::make('status')
                ->options([
                    'pending'   => 'Pending',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),

            Repeater::make('orderItems')
                ->label('Bestelde pizza\'s')
                ->relationship('orderItems')
                ->schema([
                    Select::make('pizza_id')
                        ->label('Pizza')
                        ->options(Pizza::where('status', 'op-voorraad')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('quantity')
                        ->label('Aantal')
                        ->numeric()
                        ->minValue(1)
                        ->required(),

                    TextInput::make('price')
                        ->label('Prijs')
                        ->numeric()
                        ->prefix('€')
                        ->required(),
                ])
                ->default($cartItems)
                ->columns(3)
                ->minItems(1)
                ->addActionLabel('Pizza toevoegen'),
        ]);
    }
}