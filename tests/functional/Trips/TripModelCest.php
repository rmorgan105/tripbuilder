<?php
namespace Trips;
use App\Libraries\Trips\Models\Flights;
use App\Libraries\Trips\Models\Trips;
use \FunctionalTester;

class TripModelCest
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
        $trip = new Trips(['name' => 'My First Trip']);
    
        $I->assertTrue($trip->save());
    }
    
    public function canSaveFlightRelation(FunctionalTester $I)
    {
        $flight = $I->have(Flights::class)->first();
        $trip = $I->have(Trips::class)->first();
        $I->assertInstanceOf(Trips::class, $trip);
        
        $trip->flights()->attach($flight->id);
        
        $I->assertEquals($flight->id, $trip->flights()->first()->id);
    }
}
