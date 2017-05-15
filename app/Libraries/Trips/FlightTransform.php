<?php
namespace App\Libraries\Trips;

use App\Libraries\Trips\Models\Flights;

use League\Fractal\TransformerAbstract;

class FlightTransform extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trips'
    ];
    
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Flights $flight)
    {
        return [
            'id'         => $flight->id,
            'name'       => $flight->destination,
            'trips'      => $flight->trips()->get(),
            'links'      => [
                'rel' => 'self',
                'uri' => '/flights/'.$flight->id,
            ]
        ];
    }
    
    /**
     * Include Trips
     *
     * @return \League\Fractal\Resource\ResourceAbstract
     */
    public function includeTrips(Flights $flight)
    {
        $flights = $flight->trips()->get();
        
        return $this->collection($flights, new TripTransformer());
    }
}
