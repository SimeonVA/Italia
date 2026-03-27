<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'customer_id', 'created_by'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
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
        if ($this->status !== 'completed') return 0;
        return $this->orderItems->sum(fn ($item) => $item->price * $item->quantity);
    }

    public function getProfitAttribute(): float
    {
        if ($this->status !== 'completed') return 0;
        return $this->orderItems->sum(fn ($item) => ($item->price - $item->pizza->cost_price) * $item->quantity);
    }
}