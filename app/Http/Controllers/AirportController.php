<?php

namespace App\Http\Controllers;

use App\Libraries\IotaCodes\Airport;
use App\Libraries\IotaCodes\AirportTransformer;
use App\Libraries\IotaCodes\Client as IotaClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

//use GuzzleHttp\Psr7\Request;

class AirportController extends Controller
{
   
    /**
     * @api {get} /airports list airports
     * @apiName List Airports
     * @apiGroup Airports
     * @apiDescription Fetch list of airports
     *
     * @apiParam (query param) {number} [page=1] page number to display
     * @apiParam (query param) {number} [per_page=10] number of items to display on the page
     *
     * @apiParam (query param) {string} [autocomplete] string to try and autocomplete
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/airports?page=1&per_page=10&autocomplete=aar
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceList(Request $request)
    {
        $iotaCodesClient = app('IotaCodesClient');
        $airportsCollection = $iotaCodesClient->listAirports($request->input('autocomplete'));
        if (! $airportsCollection) {
            return $this->returnErrorMessage('failed to fetch airport list from IOTA api', 400);
        }
    
        //add pagination
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $paginator = new LengthAwarePaginator(
            $airportsCollection->forPage($page, $per_page),
            $airportsCollection->count(),
            $per_page,
            $page
        );
    
        $airports = (new Collection($paginator, new AirportTransformer(), 'airports'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator));
        
        return $this->JsonApiResponse($airports, 200);
    }
    
    /**
     * @api {get} /airports/:code get airport
     * @apiName Get Airport
     * @apiGroup Airports
     * @apiDescription Get details of an airport by it's code
     *
     * @apiParam (query param) {string} code 3 letter airport code
     *
     * @apiExample {curl} example
     *  curl -i http://localhost:8080/airports/YUL
     *
     * @param \Illuminate\Http\Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAirport(Request $request, $code)
    {
        $iotaCodesClient = app('IotaCodesClient');
    
        $airport = $iotaCodesClient->getAirport($code);
        if (is_null($airport)) {
            return $this->returnErrorMessage('not a valid airport code', 400);
        }
        
        $result = new Item($airport, new AirportTransformer(), 'airports');
    
        return $this->JsonApiResponse($result, 200);
    }
}
