<?php

namespace App\Libraries\Trips\Models;

use Illuminate\Database\Eloquent\Model;

class Flights extends Model
{
    
    protected $fillable = ['destination'];

    public function trips()
    {
        return $this->belongsToMany(Trips::class, 'trip_flights');
    }
    
    public function toArray()
    {
        return [
            'id'    => $this->id,
            'destination'   => $this->destination,
        ];
    }
}
