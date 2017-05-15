<?php

namespace App\Http\Controllers;

use App\Libraries\IotaCodes\Airport;
use App\Libraries\IotaCodes\AirportTransformer;
use App\Libraries\IotaCodes\Client;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Fetch list of airports
     *
     * @api {get} /airports
     * @apiName List Airports
     *
     * @apiParam (pagination) {number} [page] page number to display
     * @apiParam (pagination) {number} [per_page] number of items to display on the page
     *
     * @apiParam (autocomplete) {string} [autocomplete] string to try and autocomplete
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceList(Request $request)
    {
        $client = Client::create();
        $collection = $client->listAirports($request->input('autocomplete'));
    
        //add pagination
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $paginator = new LengthAwarePaginator(
            $collection->forPage($page, $per_page),
            $collection->count(),
            $per_page,
            $page
        );
    
        $airports = (new Collection($paginator, new AirportTransformer(), 'airports'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator));
        
        return $this->JsonApiResponse($airports, 200);
    }
    
    /**
     * Get details of an airport by it's code
     *
     * @api {get} /airports/:code
     * @apiName Get Airport from code
     *
     * @apiParam {string} code 3 letter airport code
     *
     * @param \Illuminate\Http\Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAirport(Request $request, $code)
    {
        $client = Client::create();
        
        $result = $client->getAirport($code);
        $result = new Item($result, new AirportTransformer(), 'airports');
    
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
        
        return response()->json($manager->createData($resource)->toArray(), $statusCode);
    }
}
