<?php

use Skopenow\Location\Classes\LatLng;
use Skopenow\Location\Classes\Address;

class AddressTest extends TestCase
{

    protected $address;
    
    public function setup()
    {
        $this->latLngMock = $this->createMock(LatLng::class);
        $this->address = new Address('299 SW 8th St, Miami, FL 33130, USA', $this->latLngMock, 'new york', 'us', '12354');
    }


    public function testGetTitle()
    {
        $this->assertEquals('299 SW 8th St, Miami, FL 33130, USA', $this->address->getTitle());
    }

    public function testGetCity()
    {
        $this->assertEquals("new york", $this->address->getCity());
    }

    public function testGetCountry()
    {
        $this->assertEquals("us", $this->address->getCountry());
    }

    public function testGetLatLng()
    {
        $this->assertEquals($this->latLngMock, $this->address->getLatLng());
    }

    public function testGetZipCode()
    {
        $this->assertEquals("12354", $this->address->getZipCode());
    }

}
