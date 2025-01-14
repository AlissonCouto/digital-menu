<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function state(){
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function neighborhoods(){
        return $this->hasMany(Neighborhood::class, 'city_id', 'id');
    }

    public function addresses(){
        return $this->hasMany(Address::class, 'city_id', 'id');
    }
}
