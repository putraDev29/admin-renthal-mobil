<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [

        'booking_id',
        'car_id',
        'source_type',
        'source_id',
        'type',
        'title',
        'amount',
        'description',
        'transaction_date'

    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'source_id');
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class, 'source_id');
    }
}
