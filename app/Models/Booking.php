<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'car_id',
        'driver_id',
        'booking_code',
        'start_date',
        'end_date',
        'description',
        'total_price',
        'dp_amount',
        'status',
        'payment_status',
        'destination',
        'pickup',
        'open_status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function bookingPrices()
    {
        return $this->hasMany(BookingPrice::class);
    }
}