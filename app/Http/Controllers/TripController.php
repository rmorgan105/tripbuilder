<?php

namespace App\Http\Controllers;

use App\Libraries\Trips\Models\Flights;
use App\Libraries\Trips\Models\Trips;
use App\Libraries\Trips\TripTransformer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class TripController extends Controller
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

    public function listTrips(Request $request)
    {
        $tripsCollection = Trips::all();
        
        //add pagination
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $paginator = new LengthAwarePaginator(
            $tripsCollection->forPage($page, $per_page),
            $tripsCollection->count(),
            $per_page,
            $page
        );
        
        $result = (new Collection($paginator, new TripTransformer(), 'trips'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator));
    
        return $this->JsonApiResponse($result, 200);
    }
    
    public function getTrip(Request $request, $id)
    {
        $trip = Trips::find($id);
    
        if (! $trip) {
            return $this->returnErrorMessage("trip id $id not found", 400);
        }
        
        $result = new Item($trip, new TripTransformer(), 'trips');
    
        return $this->JsonApiResponse($result, 200, 'flights');
    }
    
    public function addFlight(Request $request, $id)
    {
        $trip = Trips::find($id);
        $destination = $request->input('destination');
        
        if (! $destination) {
            return $this->returnErrorMessage('destination not set', 400);
        }
        
        $flight = new Flights(['destination' => $destination]);
        $flight->save();
        $trip->flights()->attach($flight->id);
    
        $result = new Item($trip, new TripTransformer(), 'trips');
        return $this->JsonApiResponse($result, 201, 'flights');
    }
    
    /**
     * Convert the response to Json
     *
     * @param \League\Fractal\Resource\Item $resource
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function JsonApiResponse(ResourceInterface $resource, $statusCode, $includes = '')
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer('http://docker.dev:8080'));
        $manager->parseIncludes($includes);
        
        return response()->json($manager->createData($resource)->toArray(), $statusCode);
    }

    protected function returnErrorMessage($message, $statusCode = 400)
    {
        return response()->json([
            'error_message' => $message
        ], $statusCode);
    }
}
