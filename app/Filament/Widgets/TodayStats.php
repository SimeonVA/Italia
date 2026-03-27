<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class TodayStats extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 3;
    }

    public static function canView(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $ordersToday = Order::with('orderItems.pizza.ingredients')->whereDate('created_at', $today)->get();
        $completedOrders = $ordersToday->where('status', 'completed');

        $totalRevenue = $completedOrders->sum->revenue;
        $totalCost = $completedOrders->sum->cost;
        $totalProfit = $completedOrders->sum->profit;

        $ingredientCounts = [];
        foreach ($completedOrders as $order) {
            foreach ($order->orderItems as $item) {
                foreach ($item->pizza->ingredients as $ingredient) {
                    $name = $ingredient->name;
                    $ingredientCounts[$name] = ($ingredientCounts[$name] ?? 0) + $item->quantity;
                }
            }
        }

        $pizzaOrderCount = [];
        foreach ($completedOrders as $order) {
            foreach ($order->orderItems as $item) {
                $name = $item->pizza->name;
                $pizzaOrderCount[$name] = ($pizzaOrderCount[$name] ?? 0) + $item->quantity;
            }
        }

        arsort($pizzaOrderCount);
        $top3 = array_slice($pizzaOrderCount, 0, 3, true);

        $top3Html = '<ol class="list-decimal pl-4 mt-2">';
        foreach ($top3 as $name => $count) {
            $top3Html .= "<li>{$name} ({$count}x)</li>";
        }
        $top3Html .= '</ol>';

        arsort($ingredientCounts);
        $ingredientHtml = '<ul class="list-disc pl-4 mt-2 max-h-40 overflow-y-auto">';
        foreach ($ingredientCounts as $name => $count) {
            $ingredientHtml .= "<li>{$name} ({$count}x)</li>";
        }
        $ingredientHtml .= '</ul>';

        $chartData = $completedOrders->pluck('revenue')->toArray();
        if (count($chartData) < 2) $chartData = [0, $totalRevenue];

        return [
            Stat::make('Aantal bestellingen', $ordersToday->count()),

            Stat::make('Lopende bestellingen', $ordersToday->where('status', 'pending')->count()),

            Stat::make('Top 3 pizza\'s', '')
                ->description(new HtmlString($top3Html)),

            Stat::make('Totale omzet', '€ ' . number_format($totalRevenue, 2, ',', '.'))
                ->chart($chartData)
                ->extraAttributes(['class' => 'col-span-2']),

            Stat::make('Totale winst', '€ ' . number_format($totalProfit, 2, ',', '.'))
                ->chart($chartData)
                ->extraAttributes(['class' => 'col-span-1']),

            Stat::make('Totale kostprijs', '€ ' . number_format($totalCost, 2, ',', '.')),

            Stat::make('Gebruikte ingrediënten', '')
                ->description(new HtmlString($ingredientHtml))
                ->extraAttributes(['class' => 'col-span-2']),
        ];
    }
}