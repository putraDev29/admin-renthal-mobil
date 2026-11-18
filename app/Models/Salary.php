<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'driver_id',
        'car_id',
        'recipient_name',
        'transaction_date',
        'amount',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
