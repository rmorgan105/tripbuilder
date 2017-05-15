<?php

namespace App\Http\Controllers;

use App\Libraries\Trips\FlightTransform;
use App\Libraries\Trips\Models\Flights;
use App\Libraries\Trips\Models\Trips;
use App\Libraries\Trips\TripTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class FlightController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function listFlights(Request $request)
    {
        $flightCollection = Flights::all();
        //TODO: add pagination
    
        $result = new Collection($flightCollection, new FlightTransform(), 'flights');
    
        return $this->JsonApiResponse($result, 200);
    }
    
    public function getFlight(Request $request, $id)
    {
        $flight = Flights::find($id);
        
        $result = new Item($flight, new FlightTransform(), 'flights');
    
        return $this->JsonApiResponse($result, 200);
    }
    
    public function removeFlight(Request $request, $id)
    {
        /** @var \App\Libraries\Trips\Models\Flights $flight */
        $flight = Flights::find($id);
        $deleted = $flight->delete();
    
//        $result = new Item($flight, [
//            'id' => $id,
//            'message' => 'flight removed'
//        ], 'message');
        $result = new Item($flight, new FlightTransform(), 'flights');
        
        return $this->JsonApiResponse($result, 200);
    }
    
    /**
     * Convert the response to Json
     *
     * @param \League\Fractal\Resource\Item $resource
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function JsonApiResponse(ResourceInterface $resource, $statusCode)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer('http://docker.dev:8080'));
        $manager->parseIncludes('flights');
        
        return response()->json($manager->createData($resource)->toArray(), $statusCode);
    }
}
