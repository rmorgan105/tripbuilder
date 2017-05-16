<?php
namespace App\Libraries\IotaCodes;

use League\Fractal\TransformerAbstract;

class AirportTransformer extends TransformerAbstract
{
    /**
     * Fractal Transformer for Airport Resource
     *
     * @param \App\Libraries\IotaCodes\Airport $airport
     * @return array
     */
    public function transform(Airport $airport)
    {
        return [
            'id'        => $airport->code,
            'name'      => $airport->name,
            'links'   => [
                'rel'  => 'self',
                'uri' => '/airports/'.$airport->code,
            ]
        ];
    }
}
