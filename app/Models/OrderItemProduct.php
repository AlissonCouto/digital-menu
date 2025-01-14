<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemProduct extends Model
{
    use HasFactory;

    public function ingredients_removed()
    {
        return $this->hasMany(IngredientRemoved::class, 'order_item_product_id', 'id');
    }

    public function additional_ingredients()
    {
        return $this->hasMany(AdditionalIngredient::class, 'order_item_product_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
