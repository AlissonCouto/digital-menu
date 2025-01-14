<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function delivery_driver(){
        return $this->belongsTo(DeliveryDrive::class, 'deliveryman_id', 'id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function coupons(){
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function statuses(){
        return $this->hasMany(OrderStatus::class, 'order_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    // Acessors
    public function getDateAttribute($value)
    {
        $date = new \DateTime($value);
        return $date;
    }
    
}
