<?php

namespace App\Libraries\Trips\Models;

use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{

    protected $fillable = ['name'];
    
    /**
     * relation to flights
     */
    public function flights()
    {
        return $this->belongsToMany(Flights::class, 'trip_flights');
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
        ];
    }
}
