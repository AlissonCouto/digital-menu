<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaSize extends Model
{
    use HasFactory;

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'pizza_size_id', 'id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'price_pizza_sizes', 'pizza_size_id', 'product_id');
    }
}
