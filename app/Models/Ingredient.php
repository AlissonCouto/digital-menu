<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public function products_ingredients(){
        return $this->belongsToMany(Product::class, 'product_ingredients', 'product_id', 'ingredient_id');
    }

    public function products_additionals(){
        return $this->belongsToMany(Product::class, 'product_additionals', 'product_id', 'ingredient_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
