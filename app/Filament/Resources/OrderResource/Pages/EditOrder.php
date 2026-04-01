<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // app/Filament/Resources/OrderResource/Pages/EditOrder.php

// app/Filament/Resources/OrderResource/Pages/EditOrder.php

public function mount($record): void
{
    parent::mount($record);
    
    $this->form->fill([
        'status' => $this->record->status,
        // We gebruiken hier orderItems (met kleine 'o') omdat dat je relatie is
        'items' => $this->record->orderItems->map(fn ($item) => [
            'pizza_id' => $item->pizza_id,
            'quantity' => $item->quantity,
            // Als je ook de prijs wilt meenemen:
            'price'    => $item->price, 
        ])->toArray(),
    ]);
}

    protected function afterSave(): void
    {
        $items = $this->data['items'] ?? [];
        
        $sync = [];
        foreach ($items as $item) {
            $sync[$item['pizza_id']] = ['quantity' => $item['quantity']];
        }
        
        $this->record->pizzas()->sync($sync);
    }

}