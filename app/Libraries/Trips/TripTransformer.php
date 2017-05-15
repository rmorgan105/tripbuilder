<?php
namespace App\Libraries\Trips;

use App\Libraries\Trips\Models\Flights;
use App\Libraries\Trips\Models\Trips;
use League\Fractal\TransformerAbstract;

class TripTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'flights'
    ];
    
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Trips $trip)
    {
        return [
            'id'         => $trip->id,
            'name'       => $trip->name,
            'updated_at' => $trip->updated_at,
            'flights'    => $trip->flights()->get(),
            'links'      => [
                'rel' => 'self',
                'uri' => '/trips/'.$trip->code,
            ]
        ];
    }
    
    /**
     * Include Author
     *
     * @return \League\Fractal\Resource\ResourceAbstract
     */
    public function includeFlights(Trips $trip)
    {
        $flights = $trip->flights()->get();
        
        return $this->collection($flights, new FlightTransform());
    }
}
