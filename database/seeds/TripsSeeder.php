<?php

use Illuminate\Database\Seeder;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dd('here');
        factory(App\Libraries\Trips\Models\Trips::class, 5)
            ->create()
            ->each(function ($trip) {
                $trip->flights->save(factory(App\Libraries\Trips\Models\Flights::class, 2)->make());
            });
    }
}
