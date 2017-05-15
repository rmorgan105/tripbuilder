<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Libraries\Trips\Models\Trips::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text(10),
    ];
});

$factory->define(App\Libraries\Trips\Models\Flights::class, function (Faker\Generator $faker) {
    return [
        'destination' => $faker->countryISOAlpha3, //TODO change this
    ];
});
