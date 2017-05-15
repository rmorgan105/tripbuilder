<?php
namespace App\Libraries\Trips;

use App\Libraries\Trips\Models\Flights;

use League\Fractal\TransformerAbstract;

class FlightTransform extends TransformerAbstract
{
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
            'links'      => [
                'rel' => 'self',
                'uri' => '/flights/'.$flight->id,
            ]
        ];
    }
}
