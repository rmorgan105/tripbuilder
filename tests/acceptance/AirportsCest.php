<?php


class AirportsCest
{
    protected $uri = '/airports';
    
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function listAirportsReturnsJson(AcceptanceTester $I)
    {
        $params = [];
        
        $I->sendGET($this->uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
    }
    
    public function listAirportsReturns1ItemWhenPerPageIs1(AcceptanceTester $I)
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
    
    public function listAirportsReturns2ItemWhenPerPageIs2(AcceptanceTester $I)
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
    
    public function getAirportReturnsTripJson(AcceptanceTester $I)
    {
        $I->sendGET($this->uri, []);
        $r = $I->grabResponse();
        $data = json_decode($r, true);
        $airportId = $data['data'][0]['id'];
        
        $params = [];
        $uri = $this->uri . '/' . $airportId;
        
        $I->sendGET($uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(200, $r);
        $I->seeResponseIsJson();
        
        $data = json_decode($r, true);
        $I->assertEquals('airports', $data['data']['type']);
        $I->assertEquals($airportId, $data['data']['id']);
    }
    
    public function getAirportReturns400IfTripIdNotFound(AcceptanceTester $I)
    {
        $params = [];
        $uri = $this->uri . '/999999';
        
        $I->sendGET($uri, $params);
        $r = $I->grabResponse();
        
        $I->seeResponseCodeIs(400, $r);
        $I->seeResponseIsJson();
    }
}
