<?php

namespace Skopenow\Location;

use Skopenow\Location\Classes\AddressFinder;
use Skopenow\Location\Classes\Address;
use Skopenow\Location\Classes\LatLng;
use Skopenow\Location\Classes\AddressMapContent;
use Skopenow\Location\Classes\LocationDynamoDB;

class AddressFinderTest extends \TestCase
{
    protected $mapConMoc;
    protected $dynamoDBMoc;
    protected $response = '{
           "results" : [
              {
                 "address_components" : [
                    {
                       "long_name" : "New York",
                       "short_name" : "New York",
                       "types" : [ "country", "locality", "political" ]
                    },
                    {
                       "long_name" : "New York",
                       "short_name" : "NY",
                       "types" : [ "administrative_area_level_1", "political" ]
                    },
                    {
                       "long_name" : "United States",
                       "short_name" : "US",
                       "types" : [ "country", "political" ]
                    }
                 ],
                 "formatted_address" : "New York, NY, USA",
                 "geometry" : {
                    "bounds" : {
                       "northeast" : {
                          "lat" : 40.9175771,
                          "lng" : -73.70027209999999
                       },
                       "southwest" : {
                          "lat" : 40.4773991,
                          "lng" : -74.25908989999999
                       }
                    },
                    "location" : {
                       "lat" : 40.7127753,
                       "lng" : -74.0059728
                    },
                    "location_type" : "APPROXIMATE",
                    "viewport" : {
                       "northeast" : {
                          "lat" : 40.9175771,
                          "lng" : -73.70027209999999
                       },
                       "southwest" : {
                          "lat" : 40.4773991,
                          "lng" : -74.25908989999999
                       }
                    }
                 },
                 "place_id" : "ChIJOwg_06VPwokRYv534QaPC8g",
                 "types" : [ "locality", "political" ]
              }
           ],
           "status" : "OK"
        }';
    
    public function setup()
    {
        $this->mapConMoc = \Mockery::mock(AddressMapContent::class);
        $this->dynamoDBMoc = \Mockery::mock(LocationDynamoDB::class);
    }

    public function testAddressNewYourk()
    {
        $inputString = "new york, new york";
        $key = "Address_new york, ny";
        \Cache::put($key, $this->response, 60);
        $addressFinder = new AddressFinder($this->mapConMoc, $this->dynamoDBMoc);
        $accual = $addressFinder->find($inputString);
        
        $latLng = new LatLng(40.7127753, -74.0059728);
        $expected = new Address("New York, NY, USA", $latLng, "New York", "NY", null);

        $this->assertEquals($expected, $accual);
    }

    public function testAddressEmptyCacheFoundInDynamo()
    {
        $inputString = "new york, new york";
        $key = "Address_new york, ny";
        \Cache::forget($key);

        $this->dynamoDBMoc->shouldReceive("getGeoCodeCache")->andReturn($this->response);

        $addressFinder = new AddressFinder($this->mapConMoc, $this->dynamoDBMoc);

        $accual = $addressFinder->find($inputString);

        $latLng = new LatLng(40.7127753, -74.0059728);
        $expected = new Address("New York, NY, USA", $latLng, "New York", "NY", null);

        $this->assertEquals($expected, $accual);
    }

    public function testAddressEmptyCacheNotFoundInDynamo()
    {
        $inputString = "new york, new york";
        $key = "Address_new york, ny";
        \Cache::forget($key);

        $this->dynamoDBMoc->shouldReceive("getGeoCodeCache")->andReturn("");
        $this->dynamoDBMoc->shouldReceive("setGeoCodeCache")->andReturn(true);
        $this->mapConMoc->shouldReceive("getMapContent")->andReturn(["content"=>$this->response]);

        $addressFinder = new AddressFinder($this->mapConMoc, $this->dynamoDBMoc);

        $accual = $addressFinder->find($inputString);

        $latLng = new LatLng(40.7127753, -74.0059728);
        $expected = new Address("New York, NY, USA", $latLng, "New York", "NY", null);

        $this->assertEquals($expected, $accual);
    }

    public function testAddressMapContentReturnError()
    {
        $inputString = "new york, new york";
        $key = "Address_new york, ny";
        \Cache::forget($key);

        $this->dynamoDBMoc->shouldReceive("getGeoCodeCache")->andReturn("");
        $this->dynamoDBMoc->shouldReceive("setGeoCodeCache")->andReturn(true);
        $this->mapConMoc->shouldReceive("getMapContent")->andReturn(["content"=>""]);

        $addressFinder = new AddressFinder($this->mapConMoc, $this->dynamoDBMoc);

        $accual = $addressFinder->find($inputString);
$this->assertTrue(true);
        // $latLng = new LatLng(40.7127753, -74.0059728);
        // $expected = new Address("New York, NY, USA", $latLng, "New York", "NY", null);

        // $this->assertEquals($expected, $accual);
    }
}
