<?php

namespace App\Filament\Resources\OrderResource\Forms;

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
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),

            Repeater::make('items')
                ->label('Bestelde pizzas')
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
                        ->default(1)
                        ->required(),
                ])
                ->columns(2)
                ->minItems(1)
                ->addActionLabel('Pizza toevoegen'),
        ]);
    }
}