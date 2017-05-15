<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Trips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('destination');
            $table->timestamps();
        });
        
        Schema::create('trip_flights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trips_id')->unsigned();
            $table->integer('flights_id')->unsigned();
            $table->timestamps();
            
            $table->unique(['trips_id', 'flights_id']);
            
            $table->foreign('trips_id')->references('id')->on('trips');
        });
        
        Schema::create('airports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->string('code', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trip_flights');
        Schema::drop('trips');
        Schema::drop('flights');
        Schema::drop('airports');
    }
}
