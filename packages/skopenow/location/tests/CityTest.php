<?php

use Skopenow\Location\Classes\LatLng;
use Skopenow\Location\Classes\City;

class CityTest extends TestCase
{
    protected $city;
    
    public function setup()
    {
        $this->latLngMock = $this->createMock(LatLng::class);
        $this->city = new City('Osterbay', $this->latLngMock, '11230', 10000, 'bigCity');
    }

    public function testGetName()
    {
        $this->assertEquals('Osterbay', $this->city->getName());
    }

    public function testGetLatLng()
    {
        $this->assertEquals($this->latLngMock, $this->city->getLatLng());
    }

    public function testGetZipCode()
    {
        $this->assertEquals('11230', $this->city->getZipCode());
    }

    public function testGetPopulation()
    {
        $this->assertEquals(10000, $this->city->getPopulation());
    }

    public function testGetSize()
    {
        $this->assertEquals('bigCity', $this->city->getSize());
    }

    public function testIsBigCity()
    {
        $this->assertTrue($this->city->isBigCity());
        $this->city->setSize('smallCity');
        $this->assertFalse($this->city->isBigCity());
    }

    public function testSetLatLng()
    {
        $this->city->setLatLng($this->latLngMock);
        $this->assertEquals($this->latLngMock, $this->city->getLatLng());
    }

    public function testSetZipCode()
    {
        $this->city->setZipCode('12540');
        $this->assertEquals('12540', $this->city->getZipCode());
    }

    public function testSetName()
    {
        $this->city->setName('New York');
        $this->assertEquals('New York', $this->city->getName());
    }

    public function testSetPopulation()
    {
        $this->city->setPopulation(20000);
        $this->assertEquals(20000, $this->city->getPopulation());
    }

    public function testSetSize()
    {
        $this->city->setSize('smallCity');
        $this->assertEquals('smallCity', $this->city->getSize());
    }
}
