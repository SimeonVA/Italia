<?php

namespace App\Filament\Resources\OrderResource\Schemas;

use App\Models\Customer;
use App\Models\Pizza;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('customer_id')
                ->label('Klant')
                ->options(Customer::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),

            Repeater::make('orderItems')
                ->label('Bestelde pizzas')
                ->relationship('orderItems')
                ->schema([
                    Select::make('pizza_id')
                        ->label('Pizza')
                        ->options(Pizza::where('status', 'op-voorraad')->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $pizza = Pizza::find($state);
                            if ($pizza) $set('price', $pizza->prijs);
                        }),

                    TextInput::make('quantity')
                        ->label('Aantal')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required(),

                    TextInput::make('price')
                        ->label('Prijs')
                        ->numeric()
                        ->prefix('€')
                        ->required(),
                ])
                ->columns(3)
                ->minItems(1)
                ->addActionLabel('Pizza toevoegen'),
        ]);
    }
}