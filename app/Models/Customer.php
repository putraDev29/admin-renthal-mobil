<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nik',
        'gender',
        'phone',
        'address'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}