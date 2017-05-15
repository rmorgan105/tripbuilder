<?php
namespace Trips;
use App\Libraries\Trips\Models\Flights;
use \FunctionalTester;

class FlightModelCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function canSave(FunctionalTester $I)
    {
        $trip = new Flights(['destination' => 'YUL']);
        
        $trip->save();
    }
}
