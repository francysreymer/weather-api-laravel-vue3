<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Get the user that owns the location.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the forecasts for the location.
     */
    public function forecasts()
    {
        return $this->hasMany(Forecast::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($location) {
            $location->forecasts()->delete();
        });
    }
}
