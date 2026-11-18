<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    protected $fillable = [

        'car_id',
        'maintenance_date',

        'km_vehicle',

        'oil_spec',
        'previous_oil_km',
        'next_oil_km',

        'front_right_tire',
        'front_left_tire',
        'rear_right_tire',
        'rear_left_tire',

        'workshop_name',

        'cost',

        'notes'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}