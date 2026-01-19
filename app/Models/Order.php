<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = ['status'];

    public function pizzas(): BelongsToMany
    {
        return $this->belongsToMany(Pizza::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getRevenueAttribute(): float
    {
        return $this->pizzas->sum(function ($pizza) {
            return $pizza->price * $pizza->pivot->quantity;
        });
    }

    public function getCostAttribute(): float
    {
        return $this->pizzas->sum(function ($pizza) {
            return $pizza->cost_price * $pizza->pivot->quantity;
        });
    }

    public function getProfitAttribute(): float
    {
        return $this->revenue - $this->cost;
    }
}
