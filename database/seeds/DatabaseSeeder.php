<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
    
        factory(App\Libraries\Trips\Models\Trips::class, 5)
            ->create()
            ->each(function ($trip) {
                $flights = factory(App\Libraries\Trips\Models\Flights::class, 2)
                    ->create()
                    ->each(function ($flight) use ($trip) {
                        $trip->flights()->attach($flight->id);
                    });
                
            });
    }
}
