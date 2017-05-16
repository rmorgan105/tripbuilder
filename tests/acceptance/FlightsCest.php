<?php


class FlightsCest
{
    protected $uri = '/flights';
    
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function listFlightsReturnsJson(AcceptanceTester $I)
    {
        $params = [];
    
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
    
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
    }
    
    public function listFlightsReturns1ItemWhenPerPageIs1(AcceptanceTester $I)
    {
        $params = [
            'page' => 1,
            'per_page' => 1
        ];
        
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
        
        $data = json_decode($r);
        $I->assertCount(1, $data->data, json_encode($data));
    }
    
    public function listFlightsReturns2ItemWhenPerPageIs2(AcceptanceTester $I)
    {
        $params = [
            'page' => 1,
            'per_page' => 2
        ];
        
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
        
        $data = json_decode($r);
        $I->assertCount(2, $data->data, json_encode($data));
    }
    
    public function listFlightsReturns0ItemWhenPageIsBeyondEndOfCollection(AcceptanceTester $I)
    {
        $params = [
            'page' => 99999,
            'per_page' => 2
        ];
        
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
        
        $data = json_decode($r);
        $I->assertCount(0, $data->data, json_encode($data));
    }
    
    
    public function getFlightReturnsTripJson(AcceptanceTester $I)
    {
        $I->sendGET($this->uri, []);
        $r = $I->grabResponse();
        $data = json_decode($r, true);
        $tripId = $data['data'][0]['id'];
        
        $params = [];
        $uri = $this->uri . '/' . $tripId;
        
        $I->sendGET($uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
        
        $data = json_decode($r, true);
        $I->assertEquals('flights', $data['data']['type']);
        $I->assertEquals($tripId, $data['data']['id']);
    }
    
    public function getFlightReturns400IfTripIdNotFound(AcceptanceTester $I)
    {
        $params = [];
        $uri = $this->uri . '/999999';
        
        $I->sendGET($uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(400, $r);
        $I->seeResponseIsJson();
    }
    
    public function removeFlightRemovesFlightFromTrips(AcceptanceTester $I)
    {
        $tripId = $this->getFirstTripId($I);
        $r = $this->addFlightToTrip($I, 'YUL', $tripId);
        $data = json_decode($r, true);
        
        $flightId = $data['data']['attributes']['flights'][0]['id'];
        $uri = $this->uri .'/'.$flightId;
    
        $I->sendDELETE($uri);
        
        $I->seeResponseCodeIs(204);
    }
    
    private function getFirstTripId(AcceptanceTester $I)
    {
        $uri = '/trips/';
        $I->sendGET($this->uri, []);
        $r = $I->grabResponse();
        $data = json_decode($r, true);
        $tripId = $data['data'][0]['id'];
        return $tripId;
    }
    
    private function addFlightToTrip(AcceptanceTester $I, $destination, $tripId)
    {
        $uri = '/trips/' . $tripId .'/flights';
    
        $I->sendPost($uri, [
            'destination' => $destination
        ]);
        return $I->grabResponse();
    }
}
