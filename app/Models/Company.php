<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'company_id', 'id');
    }

    public function delivery_charges()
    {
        return $this->hasMany(DeliveryCharge::class, 'company_id', 'id');
    }

    public function neighborhoods()
    {
        return $this->hasMany(Neighborhood::class, 'company_id', 'id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'company_id', 'id');
    }

    public function delivery_drivers()
    {
        return $this->hasMany(DeliveryDrive::class, 'company_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id', 'id');
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'company_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'company_id', 'id');
    }

    public function pizza_sizes()
    {
        return $this->hasMany(PizzaSize::class, 'company_id', 'id');
    }

    public function borders()
    {
        return $this->hasMany(BorderOption::class, 'company_id', 'id');
    }

    public function pastas()
    {
        return $this->hasMany(PastaOption::class, 'company_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'company_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'company_id', 'id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'company_id', 'id');
    }
}
