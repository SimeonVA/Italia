<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status'      => 'pending',
            'customer_id' => Customer::factory(),
            'created_by'  => null,
        ];
    }
}