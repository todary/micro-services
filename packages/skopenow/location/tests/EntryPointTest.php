<?php

namespace Skopenow\Location;

use Skopenow\Location\EntryPoint;

class EntryPointTest extends \TestCase
{
    protected $enterPoint;
    
    public function setup()
    {
        config(["state.report_id"=>1111]);
        $this->entryPoint = new EntryPoint;
    }

    public function testCalculateDistance()
    {
        $firstlatLnginp = ["lat"=>30,   "lng"=>31];
        $secondlatLnginp = ["lat"=>29,"lng"=>30];
        $actually = $this->entryPoint->calculateDistance($firstlatLnginp, $secondlatLnginp);

        $expected = new \ArrayIterator(["distance"=>147410.95440009932]);

        $this->assertEquals($expected, $actually);
    }

    public function testFindCities()
    {
        $inputs = ["oyster bay, NY"];
        
        $city1=["name"=>"oyster bay", "latLng"=>["lat"=>30.25, "lng"=>24.215], "zipCode"=>21558, "population"=>1254, "size"=>false];
        

        $expected = new \ArrayIterator(["oyster bay, NY"=>$city1]);
        $this->assertEquals($expected, $this->entryPoint->findCities($inputs));
    }

    public function testFindAddress()
    {
        $inputs = ["oster bay, NY"];
        $actually = $this->entryPoint->findAddress($inputs);

        $address = ["title"=>"Oyster Bay, NY, USA", "city"=>null, "country"=>"NY", "latLng"=>["lat"=>40.789377799999997, "lng"=>-73.535854099999995], "zipCode"=>null];
 
        $expected = new \ArrayIterator(["oster bay, NY"=>$address]);
        $this->assertEquals($expected, $actually);
    }

    public function testFindLatLng()
    {
        $inputs = ["71 Pilgrim Avenue Chevy Chase, MD 20815"];
        $actually = $this->entryPoint->findLatLng($inputs);

        $latLng = ["lat"=>30, "lng"=>29];
        $expected = new \ArrayIterator(["71 Pilgrim Avenue Chevy Chase, MD 20815"=>false]);
        $this->assertEquals($expected, $actually);
    }

    public function testIsLocatedInUS()
    {
        $inputs = ["al","or"];
        $actually = $this->entryPoint->isLocatedInUS($inputs);

        $expected = new \ArrayIterator(["al"=>true,"or"=>true]);
        $this->assertEquals($expected, $actually);
    }

    public function testFindNearestCities()
    {
        $inputs = new \ArrayIterator();
        $inputs->append(["lat"=>30,"lng"=>29]);
        
        $actually = $this->entryPoint->findNearestCities($inputs);
                
        $expected = new \ArrayIterator();
        $nearst = new \ArrayIterator([
            "location"=>["lat"=>30, "lng"=>29, "radius"=>0.014492753623188],
            "cities"=>[]
            ]);

        $expected->append($nearst);
        //dd($actually, $expected);
        $this->assertEquals($expected, $actually);
    }

    public function testGetCityZipCodes()
    {
        $inputs = new \ArrayIterator(["Oyster Bay, New Yourk"]);
        $actually = $this->entryPoint->getCityZipCodes($inputs);

        $expected = new \ArrayIterator(["Oyster Bay, New Yourk"=>false]);
        
        $this->assertEquals($expected, $actually);
    }

    public function testGetStateAbv()
    {
        $inputs = new \ArrayIterator(["Alaska","Oregon"]);
        $actually = $this->entryPoint->getStateAbv($inputs);

        $expected = new \ArrayIterator(["Alaska"=>"AK","Oregon"=>"OR"]);
        
        $this->assertEquals($expected, $actually);
    }

    public function testGetStateName()
    {
        $inputs = new \ArrayIterator(["AK","OR"]);
        $actually = $this->entryPoint->getStateName($inputs);

        $expected = new \ArrayIterator(["AK"=>"Alaska","OR"=>"Oregon"]);
        
        $this->assertEquals($expected, $actually);
    }

    public function testGetStateNameByAreaCode()
    {
        $inputs = new \ArrayIterator(["304","419"]);
        $actually = $this->entryPoint->getStateNameByAreaCode($inputs);

        $expected = new \ArrayIterator(["304"=>"West Virginia","419"=>"Ohio"]);
        
        $this->assertEquals($expected, $actually);
    }

    public function testNormalizeStateName()
    {
        $inputs = new \ArrayIterator(["West Virginia","Ohio"]);
        $actually = $this->entryPoint->normalizeStateName($inputs);

        $expected = new \ArrayIterator(["West Virginia"=>"West Virginia","Ohio"=>"Ohio"]);
        
        $this->assertEquals($expected, $actually);
    }
}
