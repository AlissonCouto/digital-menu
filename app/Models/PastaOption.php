<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastaOption extends Model
{
    use HasFactory;

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'pasta_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
