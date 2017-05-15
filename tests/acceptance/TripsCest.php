<?php


class TripsCest
{
    protected $uri = '/trips';
    
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function listTripsReturnsJson(AcceptanceTester $I)
    {
        $params = [];
        
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
    }
    
    public function listTripReturns1ItemWhenPerPageIs1(AcceptanceTester $I)
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
        $I->assertCount(1, $data->data, $data);
    }
    
    public function listTripReturns2ItemWhenPerPageIs2(AcceptanceTester $I)
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
        $I->assertCount(2, $data->data, $data);
    }
    
    public function getTripReturnsTripJson(AcceptanceTester $I)
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
        $I->assertEquals('trips', $data['data']['type']);
        $I->assertEquals($tripId, $data['data']['id']);
    }
    
    public function getTripReturns400IfTripIdNotFound(AcceptanceTester $I)
    {
        $params = [];
        $uri = $this->uri . '/999999';
    
        $I->sendGET($uri, $params);
        $r = $I->grabResponse();
    
        $I->seeResponseCodeIs(400, $r);
        $I->seeResponseIsJson();
    }
    
    public function addFlightAttachesNewFlightOnTrip(AcceptanceTester $I)
    {
        $I->sendGET($this->uri, []);
        $r = $I->grabResponse();
        $data = json_decode($r, true);
        $tripId = $data['data'][0]['id'];
        $nbFlights = count($data['data'][0]['attributes']['flights']);
        
        $params = [
            'destination' => 'YUL'
        ];
        $uri = $this->uri . '/' . $tripId .'/flights';
        
        $I->sendPost($uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(201, $r);
        $I->seeResponseIsJson();
    
        $data = json_decode($r, true);
        $I->assertCount($nbFlights+1, $data['data']['attributes']['flights']);
    }
}
