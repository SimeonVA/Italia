<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $customer = Customer::firstOrCreate(
            ['name' => $data['customer_name']],
            [
                'email'   => $data['customer_email'] ?? null,
                'phone'   => $data['customer_phone'] ?? null,
                'address' => $data['customer_address'] ?? null,
            ]
        );

        $data['customer_id'] = $customer->id;
        $data['created_by']  = auth()->id();

        unset($data['customer_name'], $data['customer_email'], $data['customer_phone'], $data['customer_address']);

        return $data;
    }

    protected function afterCreate(): void
{
    session()->forget('cart');
}

protected function getRedirectUrl(): string
{
    $order = $this->record->load('orderItems.pizza');

    Stripe::setApiKey(config('services.stripe.secret'));

    $lineItems = $order->orderItems->map(function ($item) {
        return [
            'price_data' => [
                'currency'     => 'eur',
                'product_data' => [
                    'name' => $item->pizza->name,
                ],
                'unit_amount' => (int) ($item->price * 100),
            ],
            'quantity' => $item->quantity,
        ];
    })->toArray();

    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items'           => $lineItems,
        'mode'                 => 'payment',
        'success_url'          => route('stripe.success') . '?order_id=' . $order->id,
        'cancel_url'           => route('stripe.cancel') . '?order_id=' . $order->id,
        'metadata'             => ['order_id' => $order->id],
    ]);

    return $session->url;
}
}