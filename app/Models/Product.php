<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $pizza_price;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function order_items()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_products', 'product_id', 'order_item_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients', 'product_id', 'ingredient_id');
    }

    public function ingredients_checked()
    {
        $ids = $this->ingredients()->get()->pluck('id');

        return $ids;
    } // ingredientsCheckeds()

    public function additionals_checked()
    {
        $ids = $this->additionals()->get()->pluck('id');

        return $ids;
    } // additionals_checked()

    public function ingredientsNames()
    {
        $ingredients = $this->ingredients()->get();

        $names = '';
        if ($ingredients->count() != 0) {
            $names = implode(', ', $ingredients->pluck('name')->toArray());
        }

        return $names;
    }

    public function additionals()
    {
        return $this->belongsToMany(Ingredient::class, 'product_additionals', 'product_id', 'ingredient_id');
    }

    public function pizza_size()
    {
        return $this->belongsToMany(PizzaSize::class, 'price_pizza_sizes', 'product_id', 'pizza_size_id');
    }

    public function prices()
    {
        $prices = [];

        $pizza_size = $this->pizza_size()->get();

        foreach ($pizza_size as $row) {

            $pricePizzaSize = PricePizzaSize::where([
                [
                    'pizza_size_id',
                    '=',
                    $row->id
                ],
                [
                    'product_id',
                    '=',
                    $this->id
                ]
            ])->first();

            $prices[$pricePizzaSize->pizza_size_id] = [
                'price' => $pricePizzaSize->price
            ];
        }

        return $prices;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
