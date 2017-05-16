<?php
namespace App\Libraries\IotaCodes;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Collection;

class Client
{
    /**
     * Guzzle Client
     * @var Guzzle
     */
    protected $guzzle;
    
    /**
     * Client constructor.
     * @param \GuzzleHttp\Client $guzzle
     * @param array $config
     */
    public function __construct(Guzzle $guzzle, array $config = [])
    {
        $this->guzzle = $guzzle;
    }
    
    /**
     * Factory Method
     *
     * @return \App\Libraries\IotaCodes\Client
     */
    public static function create()
    {
        return new self(new Guzzle([
            'base_uri' => 'https://iatacodes.org',
            'timeout'  => 5.0,
            'verify'   => false
        ]));
    }
    
    /**
     * Get list of airports
     *
     * @param string $autoComplete
     * @return \Illuminate\Support\Collection|false
     */
    public function listAirports($autoComplete = '')
    {
        $uri = '/api/v6/airports';
        $key = 'all.airports';
        $minutes = 60;
    
        $response = app('cache')->get($key, function () use ($key, $minutes, $uri) {
            try {
                /** @var \Psr\Http\Message\ResponseInterface $response */
                $response = $this->guzzle->get($uri, [
                    'query' => [
                        'lang'    => 'en',
                        'api_key' => '8105c628-a86c-41af-85da-828bcf8190e0'
                    ],
                ]);
    
            } catch (\Exception $e) {
                return false;
            }
            
            $result = $response->getBody()->getContents();
            if (empty($result)) {
                return false;
            }
            app('cache')->put($key, $result, $minutes);
            return $result;
        });
        
        if(! $response) {
            return false;
        }
        $result = $this->toAirportCollection($response);
        if ($autoComplete != '') {
            $result = $this->autoCompleteMap($result, $autoComplete);
        }
        return $result;
    }
    
    /**
     * @param $code
     * @return Collection
     */
    public function getAirport($code)
    {
        $uri = '/api/v6/airports';
        $cacheKey = 'airport.'.$code;
        $cacheMinutes = 60;
    
        $response = app('cache')->get($cacheKey, function () use ($cacheKey, $cacheMinutes, $uri, $code) {
            try {
                /** @var \Psr\Http\Message\ResponseInterface $response */
                $response = $this->guzzle->get($uri, [
                    'query' => [
                        'code'    => $code,
                        'lang'    => 'en',
                        'api_key' => '8105c628-a86c-41af-85da-828bcf8190e0'
                    ],
                ]);
            
            } catch (\Exception $e) {
                return new Collection();
            }
        
            $result = $response->getBody()->getContents();
            app('cache')->put($cacheKey, $result, $cacheMinutes);
            return $result;
        });
    
        return $result = $this->toAirportCollection($response)->first();
    }
    
    /**
     * @param $json string
     * @return \Illuminate\Support\Collection
     */
    private function toAirportCollection($json)
    {
        $data = json_decode($json);
        if (! $data) {
            return new Collection();
        }
        $result = (new Collection($data->response))
            ->sortBy('name')
            ->map(function ($item, $key) {
            return Airport::create($item->name, $item->code);
        });
        
        return $result;
    }
    
    /**
     * @param \Illuminate\Support\Collection $collection
     * @param $autoComplete
     * @return Collection
     */
    private function autoCompleteMap(Collection $collection, $autoComplete)
    {
        return $collection->filter(function ($value, $key) use ($autoComplete) {
            return strpos(strtolower($value->name), strtolower($autoComplete)) !== false;
        });
    }
}
