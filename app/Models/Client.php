<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;

    protected $guard = 'client';

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addresses(){
        return $this->hasMany(Address::class, 'client_id', 'id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'client_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

}