<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Pizza;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $pizzas = Pizza::where('status', 'op-voorraad')->get();

        for ($i = 0; $i < 5; $i++) {
            $order = Order::create([
                'status' => fake()->randomElement(['pending', 'completed']),
                'created_at' => now(),
            ]);

            $randomPizzas = $pizzas->random(rand(1, 3));
            
            foreach ($randomPizzas as $pizza) {
                $order->pizzas()->attach($pizza->id, [
                    'quantity' => rand(1, 3),
                ]);
            }
        }
    }
}