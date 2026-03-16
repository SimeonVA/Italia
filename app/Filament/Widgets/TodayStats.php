<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class TodayStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $ordersToday = Order::with('pizzas.ingredients')->whereDate('created_at', $today)->get();
        $completedOrders = $ordersToday->where('status', 'completed');

        $totalRevenue = $completedOrders->sum->revenue;
        $totalCost = $completedOrders->sum->cost;
        $totalProfit = $completedOrders->sum->profit;

        $ingredientCounts = [];
        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                foreach ($pizza->ingredients as $ingredient) {
                    $name = $ingredient->name;
                    if (!isset($ingredientCounts[$name])) {
                        $ingredientCounts[$name] = 0;
                    }
                    $ingredientCounts[$name] += $pizza->pivot->quantity;
                }
            }
        }

        $pizzaOrderCount = [];
        foreach ($completedOrders as $order) {
            foreach ($order->pizzas as $pizza) {
                if (!isset($pizzaOrderCount[$pizza->name])) {
                    $pizzaOrderCount[$pizza->name] = 0;
                }
                $pizzaOrderCount[$pizza->name]++;
            }
        }

        arsort($pizzaOrderCount);
        $top3 = array_slice($pizzaOrderCount, 0, 3, true);

        $top3Html = '<ol class="list-decimal pl-4">';
        foreach ($top3 as $name => $orders) {
            $top3Html .= "<li>{$name}</li>";
        }
        $top3Html .= '</ol>';

        $ingredientHtml = '<ul class="list-disc pl-4">';
        foreach ($ingredientCounts as $name => $count) {
            $ingredientHtml .= "<li>{$name} ({$count}x)</li>";
        }
        $ingredientHtml .= '</ul>';

        return [
            Stat::make('Aantal bestellingen', $ordersToday->count()),
            Stat::make('Lopende bestellingen', $ordersToday->where('status', 'pending')->count()),
            Stat::make('Totale omzet', '€ ' . number_format($totalRevenue, 2, ',', '.')),
            Stat::make('Totale kostprijs', '€ ' . number_format($totalCost, 2, ',', '.')),
            Stat::make('Totale winst', '€ ' . number_format($totalProfit, 2, ',', '.')),
            Stat::make('Top 3 pizza\'s', '')->description(new HtmlString($top3Html)),
            Stat::make('Gebruikte ingrediënten', '')->description(new HtmlString($ingredientHtml)),
        ];
    }
}