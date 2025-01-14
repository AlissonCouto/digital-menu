<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function neighborhoods(){
        return $this->hasMany(Neighborhood::class, 'rate_id', 'id');
    }
}
