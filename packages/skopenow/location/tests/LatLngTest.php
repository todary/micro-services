<?php

use Skopenow\Location\Classes\LatLng;

class LatLngTest extends TestCase
{

    protected $latLng;
    
    public function setup()
    {
        $this->latLng = new LatLng(30, 29);
    }

    public function testGetLat()
    {
        $this->assertEquals(30, $this->latLng->getLat());
    }

    public function testGetLng()
    {
        $this->assertEquals(29, $this->latLng->getLng());
    }
}
