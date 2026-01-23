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
        if ($this->status !== 'completed') {
            return 0;
        }

        return $this->pizzas->sum(
            fn ($pizza) => $pizza->prijs * $pizza->pivot->quantity
        );
    }

    public function getCostAttribute(): float
    {
        if ($this->status !== 'completed') {
            return 0;
        }

        return $this->pizzas->sum(
            fn ($pizza) => $pizza->cost_price * $pizza->pivot->quantity
        );
    }

    /**
     * Winst
     */
    public function getProfitAttribute(): float
    {
        return $this->revenue - $this->cost;
    }
}
