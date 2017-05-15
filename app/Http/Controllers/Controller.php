<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class Controller extends BaseController
{
    /**
     * Convert the response to Json
     *
     * @param \League\Fractal\Resource\Item $resource
     * @param int $statusCode
     * @param string $includes
     * @return \Illuminate\Http\JsonResponse
     */
    protected function JsonApiResponse(ResourceInterface $resource, $statusCode, $includes = '')
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer('http://docker.dev:8080'));
        $manager->parseIncludes($includes);
        
        return response()->json($manager->createData($resource)->toArray(), $statusCode);
    }
    
    /**
     * format an error message
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnErrorMessage($message, $statusCode = 400)
    {
        return response()->json([
            'error_message' => $message
        ], $statusCode);
    }
}
