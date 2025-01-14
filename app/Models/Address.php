<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function neighborhood(){
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id', 'id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function delivery_drive(){
        return $this->belongsTo(DeliveryDrive::class, 'deliveryman_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
