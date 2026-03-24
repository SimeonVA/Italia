<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'customer_name', 'created_by'];

    public function pizzas(): BelongsToMany
    {
        return $this->belongsToMany(Pizza::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'preparing', 'ready']);
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

    public function getProfitAttribute(): float
    {
        return $this->revenue - $this->cost;
    }
}