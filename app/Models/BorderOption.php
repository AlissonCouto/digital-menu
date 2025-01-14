<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorderOption extends Model
{
    use HasFactory;

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'border_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
