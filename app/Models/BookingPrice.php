<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPrice extends Model
{
    protected $fillable = [
        'booking_id',
        'name',
        'price',
        'qty',
        'subtotal'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}