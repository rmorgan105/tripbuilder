<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/airports', 'AirportController@resourceList');
$app->get('/airports/{code}', 'AirportController@getAirport');

$app->get('/trips', 'TripController@listTrips');
$app->get('/trips/{id}', 'TripController@getTrip');

$app->get('/trips/{id}/flights', 'TripController@addFlight');
$app->post('/trips/{id}/flights', 'TripController@addFlight');

$app->get('/flights', 'FlightController@listFlights');
$app->get('/flights/{id}', 'FlightController@getFlight');
$app->delete('/flights/{id}', 'FlightController@removeFlight');
