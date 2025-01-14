<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDrive extends Model
{
    use HasFactory;

    protected $table = 'delivery_drivers';

    public function address()
    {
        return $this->hasOne(Address::class, 'deliveryman_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'deliveryman_id', 'id');
    }
}
