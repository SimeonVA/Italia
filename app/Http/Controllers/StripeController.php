<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::with('orderItems.pizza')->findOrFail($orderId);

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

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $order = Order::find($request->input('order_id'));
        if ($order) {
            $order->update(['status' => 'completed']);
        }

        return redirect('/admin/orders')->with('success', 'Betaling geslaagd!');
    }

    public function cancel(Request $request)
    {
        return redirect('/admin/orders')->with('error', 'Betaling geannuleerd.');
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;
            Order::find($orderId)?->update(['status' => 'completed']);
        }

        return response()->json(['status' => 'ok']);
    }
}