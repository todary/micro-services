<?php

use Skopenow\Location\Classes\LatLng;
use Skopenow\Location\Classes\CitiesFinder;
use Skopenow\Location\Classes\AddressFinder;
use Skopenow\Location\Classes\LocationStringAnalyzer;
use Skopenow\Location\Classes\LocationStringParser;
use Skopenow\Location\Classes\City;
use Skopenow\Location\Classes\Address;
use Skopenow\Location\Classes\GeoHelper;

class GeoHelperTest extends TestCase
{
    protected $geoHelper;

    public function setup()
    {
        $this->citiesFinderMock = $this->createMock(CitiesFinder::class);
        $this->addressFinderMock = $this->createMock(AddressFinder::class);
        $this->locationAnalyzerMock = $this->createMock(LocationStringAnalyzer::class);
        $this->locationParserMock = $this->createMock(LocationStringParser::class);
        
        $this->geoHelper = new GeoHelper(
            $this->citiesFinderMock,
            $this->addressFinderMock,
            $this->locationAnalyzerMock,
            $this->locationParserMock
        );
    }

    public function testCalculateDistance()
    {
        $distance = $this->geoHelper->calculateDistance(new LatLng(30, 29), new LatLng(31, 30));
        $this->assertEquals(146775, intval($distance));
    }

    public function testFindCity()
    {
        $this->citiesFinderMock
            ->method('getCity')
            ->willReturn($this->createMock(City::class));

        $this->locationParserMock
            ->method('extractCity')
            ->willReturn('oyster bay');

        $this->locationParserMock
            ->method('extractState')
            ->willReturn('New York');

        $city = $this->geoHelper->findCity('oyster bay, New York');
        $this->assertInstanceOf(City::class, $city);
    }

    public function testFindCities()
    {
        $this->citiesFinderMock
            ->method('getCity')
            ->willReturn($this->createMock(City::class));

        $this->locationParserMock
            ->method('extractCity')
            ->willReturn('oyster bay');

        $this->locationParserMock
            ->method('extractState')
            ->willReturn('New York');

        $cityStrings = [
            'Oyster Bay, New York',
            'Nassau County, New York',
            'Nassau County, NY',
        ];
        $cities = $this->geoHelper->findCities($cityStrings);
        //dd($cities);

        $this->assertInstanceOf(City::class, $cities["Oyster Bay, New York"]);
        $this->assertInstanceOf(City::class, $cities["Nassau County, New York"]);
        $this->assertInstanceOf(City::class, $cities["Nassau County, NY"]);
    }

    public function testFindAddress()
    {
        $this->addressFinderMock
            ->method('find')
            ->willReturn($this->createMock(Address::class));
     
        $address = $this->geoHelper->findAddress('299 SW 8th St, Miami, FL 33130, USA');
        $this->assertInstanceOf(Address::class, $address);
    }

    public function testFindLatLngForAddress()
    {
            //test address string input
            $addressMock = $this->createMock(Address::class);
            $addressMock->method('getLatLng')
                ->willReturn($this->createMock(LatLng::class));

            $this->locationAnalyzerMock
                ->method('isCityString')
                ->willReturn(false);

            $this->locationAnalyzerMock
                ->method('isAddressString')
                ->willReturn(true);

            $this->addressFinderMock
                ->method('find')
                ->willReturn($addressMock);

            $latLng = $this->geoHelper->findLatLng('299 SW 8th St, Miami, FL 33130, USA');
            $this->assertInstanceOf(LatLng::class, $latLng);
    }

    public function testFindLatLngForCity()
    {
        //test city string input
        $cityMock = $this->createMock(City::class);
        $cityMock->method('getLatLng')
            ->willReturn($this->createMock(LatLng::class));

        $this->locationAnalyzerMock
            ->method('isCityString')
            ->willReturn(true);

        $this->locationAnalyzerMock
            ->method('isAddressString')
            ->willReturn(false);

        $this->citiesFinderMock
            ->method('getCity')
            ->willReturn($cityMock);

        $this->locationParserMock
            ->method('extractCity')
            ->willReturn('oyster bay');

        $this->locationParserMock
            ->method('extractState')
            ->willReturn('New York');

        $latLng = $this->geoHelper->findLatLng('oyster bay, New York');
        $this->assertInstanceOf(LatLng::class, $latLng);
    }

    public function testFindLatLngForNeitherCityNorAddress()
    {
        $this->locationAnalyzerMock
            ->method('isCityString')
            ->willReturn(false);

        $this->locationAnalyzerMock
            ->method('isAddressString')
            ->willReturn(false);


        $latLng = $this->geoHelper->findLatLng('oyster bay, New York');
        $this->assertNull($latLng);
    }

    public function testIsLocatedInUS()
    {
        $this->locationParserMock
            ->method('extractState')
            ->willReturn('New York');

        $result = $this->geoHelper->isLocatedInUS('ny');
        $this->assertTrue($result);

        $result = $this->geoHelper->isLocatedInUS('');
        $this->assertFalse($result);
    }

    public function testIsLocatedInUSFailure()
    {
        $this->locationParserMock
            ->method('extractState')
            ->willReturn('OAB');

        $result = $this->geoHelper->isLocatedInUS('OAB');
        $this->assertFalse($result);
    }

    public function testIsLocatedInUSFailure2()
    {
        $this->locationParserMock
            ->method('extractState')
            ->willReturn(false);

        $result = $this->geoHelper->isLocatedInUS('OAB');
        $this->assertFalse($result);
    }

    public function testFindNearestCities()
    {
        $this->citiesFinderMock
            ->method('getNearestCities')
            ->willReturn([]);
        $cities = $this->geoHelper->findNearestCities(29, 30, 100);
        $this->assertEquals([], $cities);
    }

    public function testGetCityZipCodes()
    {
        $expectedZipCodes = ['13255', '25536'];
        $this->citiesFinderMock
            ->method('getCityZipCodes')
            ->willReturn($expectedZipCodes);

        $this->locationParserMock
            ->method('extractCity')
            ->willReturn('oyster bay');

        $this->locationParserMock
            ->method('extractState')
            ->willReturn('New York');

        $zipCodes = $this->geoHelper->getCityZipCodes('oyster bay, New York');
        $this->assertEquals($expectedZipCodes, $zipCodes);
    }

    public function testGetStateAbv()
    {
        $abv = $this->geoHelper->getStateAbv('New York');
        $this->assertEquals('NY', $abv);

        $abv = $this->geoHelper->getStateAbv('NY');
        $this->assertEquals('NY', $abv);
    }

    public function testGetStateName()
    {
        $stateName = $this->geoHelper->getStateName('NY');
        $this->assertEquals('New York', $stateName);
        $stateName = $this->geoHelper->getStateName('New York');
        $this->assertEquals('New York', $stateName);
        $stateName = $this->geoHelper->getStateName('New York 101');
        $this->assertEquals("", $stateName);
    }


    public function testGetStateNameByAreaCode()
    {
        $stateName = $this->geoHelper->getStateNameByAreaCode('201');
        $this->assertEquals('New Jersey', $stateName);
        $stateName = $this->geoHelper->getStateNameByAreaCode('2001');
        $this->assertNull($stateName);
    }

    

    public function testNormalizeStateName()
    {
        $stateName = $this->geoHelper->normalizeStateName('new york city');
        $this->assertEquals('New York City, New York', $stateName);
        $stateName = $this->geoHelper->normalizeStateName('New Jersey');
        $this->assertEquals('New Jersey', $stateName);
    }
}
