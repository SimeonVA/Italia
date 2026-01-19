<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterSave(): void
    {
        $this->syncPizzas();
    }

    protected function syncPizzas(): void
    {
        $items = collect($this->data['items'])
            ->mapWithKeys(fn ($item) => [
                $item['pizza_id'] => ['quantity' => $item['quantity']],
            ])
            ->toArray();

        $this->record->pizzas()->sync($items);
    }
}
