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

    public function mount(int|string $record): void
    {
        parent::mount($record);
        
        $this->form->fill([
            'status' => $this->record->status,
            'items' => $this->record->pizzas->map(fn ($p) => [
                'pizza_id' => $p->id,
                'quantity' => $p->pivot->quantity,
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