<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
