<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function orders(){
        return $this->hasMany(Order::class, 'coupon_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
