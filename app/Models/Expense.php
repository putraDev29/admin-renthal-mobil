<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'car_id',
        'transaction_date',
        'category',
        'title',
        'amount',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
