<?php
namespace App\Libraries\IotaCodes;

class Airport
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $code;
    
    /**
     * Airport constructor.
     * @param $name
     * @param $code
     */
    protected function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }
    
    /**
     * Airport factory method
     * @param $name
     * @param $code
     * @return \App\Libraries\IotaCodes\Airport
     */
    public static function create($name, $code)
    {
        return new self($name, $code);
    }
}
