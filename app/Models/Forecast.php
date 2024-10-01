<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_id',
        'date_forecast',
        'main',
        'description',
        'icon',
        'temperature',
        'feels_like',
        'min_temperature',
        'max_temperature',
        'pressure',
        'humidity',
        'wind_speed',
        'cloudiness',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the location that owns the forecast.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
