<?php

use Skopenow\Location\Classes\CitiesFinder;
use Skopenow\Location\Classes\LocationDynamoDB;
use Skopenow\Location\Classes\LocationMongoDB;
use Illuminate\Support\Facades\Cache;

class CitiesFinderTest extends TestCase
{
    protected $citiesFinder;

    public function setup()
    {
        $this->citiesFinder = new CitiesFinder;
        
    }

    public function testGetCityEmptyCityOrState()
    {
        $cacheKey = 'USPopulations-cache-oyster bay,new york';
        Cache::pull($cacheKey);
        
        $data = false;
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCity")->willReturn($data);
        $expected = false;
        
        $this->assertFalse($this->citiesFinder->getCity("","New York",$dbMoc));
    }

    public function testGetCityDataFromCashe()
    {
        $cacheKey = 'USPopulations-cache-oyster bay,new york';
        Cache::pull($cacheKey);

        $data = ["oyster bay"];
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCity")->willReturn($data);

        Cache::put($cacheKey, $data, 60);
        $this->assertEquals($data,$this->citiesFinder->getCity("oyster bay","New York",$dbMoc));
        Cache::pull($cacheKey);
    }

    public function testGetCityDataCasheEmpty()
    {
        $cacheKey = 'USPopulations-cache-oyster bay,new york';
        Cache::pull($cacheKey);
        Cache::put($cacheKey, "empty", 60*60*24*7);

        $data = ["oyster bay"];
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("setGeoCodeCache")->willReturn(true);
        $dbMoc->method("getCity")->willReturn($data);

        $output = $this->citiesFinder->getCity("oyster bay", "New York", $dbMoc);

        $expected = ["oyster bay"];
        $this->assertEquals($expected,$this->citiesFinder->getCity("oyster bay","New York",$dbMoc));
    }

    public function testGetCityFoundData()
    {
        $cacheKey = 'USPopulations-cache-oyster bay,new york';
        Cache::pull($cacheKey);

        $cityData = array(
            "name"=>"",
            "lat"=>0,
            "lon"=>0,
            "zipCode"=>12,
            "population"=>1,
            "size"=>""
            );
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCity")->willReturn($cityData);

        Cache::put($cacheKey, false, 60*60*24*7);

        $expected = $cityData;
        $this->assertEquals($expected,$this->citiesFinder->getCity("oyster bay","New York",$dbMoc));
    }

    public function testGetCityNotFoundDataInDB()
    {
        $cacheKey = 'USPopulations-cache-oyster bay,new york';
        Cache::pull($cacheKey);

        $data = false;
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCity")->willReturn(false);

        Cache::put($cacheKey, false, 60*60*24*7);

        $this->assertFalse($this->citiesFinder->getCity("oyster bay","New York",$dbMoc));
    }

    public function testGetCityZipCodesEmptyCityOrState()
    {
        $cacheKey = 'getZipCode-cache-oyster bay-new york';
        Cache::pull($cacheKey);

        $data = false;
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCityZipCodes")->willReturn($data);
        
        $this->assertEquals([],$this->citiesFinder->getCityZipCodes("","New York",$dbMoc));
    }

    public function testGetCityZipCodesFromCashe()
    {
        $cacheKey = 'getZipCode-cache-oyster bay-new york';
        Cache::pull($cacheKey);

        $data = array(
            "name"=>"",
            "lat"=>0,
            "lon"=>0,
            "zipCode"=>12,
            "population"=>1,
            "size"=>""
            );

        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCityZipCodes")->willReturn($data);

        Cache::put($cacheKey, $data, 60*60*24*7);
        
        $expected = $data;
        $this->assertEquals($expected,$this->citiesFinder->getCityZipCodes("oyster bay","New York",$dbMoc));
    }

    public function testGetCityZipCodesNotFoundDataInDB()
    {
        $cacheKey = 'getZipCode-cache-oyster bay-new york';
        Cache::pull($cacheKey);

        $data = false;
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCityZipCodes")->willReturn($data);

        $expected = false;

        $this->assertEquals($expected,$this->citiesFinder->getCityZipCodes("oyster bay","New York",$dbMoc));
    }

    public function testGetCityZipCodesFoundData()
    {
        $cacheKey = 'getZipCode-cache-oyster bay-new york';
        Cache::pull($cacheKey);
        $cityData = array(
            "name"=>"",
            "lat"=>0,
            "lon"=>0,
            "zipCode"=>12,
            "population"=>1,
            "size"=>""
            );
        $dbMoc = $this->getMockBuilder(LocationDynamoDB::class)->getMock();
        $dbMoc->method("getCityZipCodes")->willReturn($cityData);
        
        $expected = $cityData;

        $this->assertEquals($expected,$this->citiesFinder->getCityZipCodes("oyster bay","New York",$dbMoc));
    }

    public function testGetNearestCities()
    {
        $data = [0=>["city"=>"oyster bay"]];
        $dbMoc = $this->getMockBuilder(LocationMongoDB::class)->getMock();
        $dbMoc->method("getNearestCities")->willReturn($data);

        $expected = ["oyster bay"];

        $this->assertEquals($expected,$this->citiesFinder->getNearestCities("38.964835", "-77.0883076", $dbMoc));
    }    

}
