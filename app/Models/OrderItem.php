<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_item_products', 'order_item_id', 'product_id');
    }

    public function order_item_products()
    {
        return $this->hasMany(OrderItemProduct::class, 'order_item_id', 'id');
    }

    /* Order_item_products N X 1 order_item */

    public function size()
    {
        return $this->belongsTo(PizzaSize::class, 'pizza_size_id', 'id');
    }

    public function pasta()
    {
        return $this->belongsTo(PastaOption::class, 'pasta_id', 'id');
    }

    public function border()
    {
        return $this->belongsTo(BorderOption::class, 'border_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
