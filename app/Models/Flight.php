<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departure_airport',
        'arrival_airport',
        'departure_time',
        'arrival_time',
        'flight_duration',
        'aircraft_type',
        'registration_number',
        'notes',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}