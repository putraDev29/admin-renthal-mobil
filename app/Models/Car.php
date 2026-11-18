<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'unit',
        'name',
        'plate_number',
        'total_unit',
        'price',
        'image',
        'tax',
        'kir',
        'status'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function maintenances()
    {
        return $this->hasMany(
            VehicleMaintenance::class,
            'car_id'
        );
    }

    public function lastMaintenance()
    {
        return $this->hasOne(
            VehicleMaintenance::class,
            'car_id'
        )->latestOfMany();
    }
}
