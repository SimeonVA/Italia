<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'beschrijving',
        'prijs',
        'status',
    ];

/*
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_pizza', 'pizza_id', 'ingredient_id')
                    ->withTimestamps();
    }
*/
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'op-voorraad');
    }
}
