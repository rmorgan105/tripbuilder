<?php

namespace App\Http\Controllers;

use App\Libraries\Trips\FlightTransform;
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

class FlightController extends Controller
{
    /**
     * @api {get} /flights list flights
     * @apiName List Flights
     * @apiGroup Flights
     * @apiDescription List all defined flights
     *
     * @apiParam (query params) {number} [page=1] page to fetch
     * @apiParam (query params) {number} [per_page=10] number of results to fetch per page
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/flights?page=1&per_page=10
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listFlights(Request $request)
    {
        $flightCollection = Flights::all();

        //add pagination
        $per_page = $request->input('per_page', 10);
        $per_page = (empty($per_page)) ? 10 : $per_page;
        $page = $request->input('page', 1);
        $paginator = new LengthAwarePaginator(
            $flightCollection->forPage($page, $per_page),
            $flightCollection->count(),
            $per_page,
            $page
        );

        $result = (new Collection($paginator, new FlightTransform(), 'trips'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->JsonApiResponse($result, 200);
    }

    /**
     * @api {get} /flights/:id get flight
     * @apiName Get Flight
     * @apiGroup Flights
     * @apiDescription get details of a specific flight
     *
     * @apiParam (url) {number} id the id of the flight to fetch
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/flights/1
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFlight(Request $request, $id)
    {
        $flight = Flights::find($id);

        if (! $flight) {
            return $this->returnErrorMessage("flight id $id not found", 400);
        }

        $result = new Item($flight, new FlightTransform(), 'flights');

        return $this->JsonApiResponse($result, 200);
    }

    /**
     * @api {delete} /flights/:id delete a flight
     * @apiName Delete a Flight
     * @apiGroup Flights
     * @apiDescription delete a flight and remove it from any trips
     *
     * @apiParam (url) {number} id the id of the flight to fetch
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFlight(Request $request, $id)
    {
        /** @var \App\Libraries\Trips\Models\Flights $flight */
        $flight = Flights::find($id);
        if (! is_null($flight)) {
            $deleted = $flight->delete();
        }

        return response()->json('', 204);
    }
}
