<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $items = $this->data['items'] ?? [];
        
        $attach = [];
        foreach ($items as $item) {
            $attach[$item['pizza_id']] = ['quantity' => $item['quantity']];
        }
        
        $this->record->pizzas()->attach($attach);
    }
}