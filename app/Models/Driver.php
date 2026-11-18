<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'drivers';

    protected $fillable = [
        'kode_driver',
        'nama',
        'no_hp',
        'email',
        'alamat',
        'no_sim',
        'masa_berlaku_sim',
        'status',
    ];

    protected $casts = [
        'masa_berlaku_sim' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getNamaLengkapAttribute()
    {
        return $this->kode_driver . ' - ' . $this->nama;
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}