<?php

namespace App\Http\Controllers;

use App\Libraries\IotaCodes\Client as IotaClient;
use App\Libraries\Trips\Models\Flights;
use App\Libraries\Trips\Models\Trips;
use App\Libraries\Trips\TripTransformer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
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
    
    /**
     * @api {get} /trips list trips
     * @apiName list trips
     * @apiGroup Trips
     * @apiDescription This api returns a paginated list of trips found
     *
     * @apiParam (query params) {number} [page=1] page to fetch
     * @apiParam (query param) {number} [per_page=10] number of results to fetch per page
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/trips?page=1&per_page=10
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    
    /**
     * @api {get} /trips/:id get trip
     * @apiName get trip
     * @apiGroup Trips
     * @apiDescription Fetch details of a specific trip
     *
     * @apiParam (url) {number} id the id of the trip to fetch
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/trips/1
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrip(Request $request, $id)
    {
        $trip = Trips::find($id);
    
        if (! $trip) {
            return $this->returnErrorMessage("trip id $id not found", 400);
        }
        
        $result = new Item($trip, new TripTransformer(), 'trips');
    
        return $this->JsonApiResponse($result, 200, 'flights');
    }
    
    /**
     * @api {post} /trips/:id/flights add flight
     * @apiName add flight
     * @apiGroup Trips
     * @apiDescription send a destination to this endpoint to add a flight to the trip
     *
     * @apiParam (form data) {string} destination the destination of the new flight
     * @apiParam (url) {number} id the id of the trip to fetch
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/trips/1/flights
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFlight(Request $request, $id)
    {
        $trip = Trips::find($id);
        $destination = $request->input('destination');
    
        if (! $destination) {
            return $this->returnErrorMessage('destination not set', 400);
        }
    
        $client = app('IotaCodesClient');
        $airport = $client->getAirport($destination);
        if (is_null($airport)) {
            return $this->returnErrorMessage('destination not a valid airport code', 400);
        }
        
        $flight = new Flights(['destination' => $destination]);
        $flight->save();
        $trip->flights()->attach($flight->id);
    
        $result = new Item($trip, new TripTransformer(), 'trips');
        return $this->JsonApiResponse($result, 201, 'flights');
    }
}
