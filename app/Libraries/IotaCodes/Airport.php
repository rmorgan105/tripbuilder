<?php
namespace App\Libraries\IotaCodes;

class Airport
{
    
    public $name;
    
    public $code;
    
    protected function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }
    
    public static function create($name, $code)
    {
        return new self($name, $code);
    }
}
