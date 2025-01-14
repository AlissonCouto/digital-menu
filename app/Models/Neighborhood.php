<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    public function delivery_charges(){
        return $this->belongsTo(DeliveryCharge::class, 'rate_id', 'id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function addresses(){
        return $this->hasMany(Address::class, 'neighborhood_id', 'id');
    }
}
