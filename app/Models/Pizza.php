<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'beschrijving',
        'prijs',
        'status',
        'image',
    ];

    protected $casts = [
        'prijs' => 'float',
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * Kostprijs van de pizza
     * = som van inkoopprijzen van ingrediënten
     */
    public function getCostPriceAttribute(): float
    {
        return $this->ingredients->sum('inkoopprijs');
    }

    /**
     * Scope voor menukaart
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'op-voorraad');
    }
}
