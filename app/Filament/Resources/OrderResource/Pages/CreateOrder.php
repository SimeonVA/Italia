<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->selectedPizzas = $data['selected_pizzas'] ?? [];
        unset($data['selected_pizzas']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $attach = [];
        foreach ($this->selectedPizzas as $item) {
            $attach[$item['pizza_id']] = ['quantity' => $item['quantity']];
        }
        $this->record->pizzas()->attach($attach);
    }
}