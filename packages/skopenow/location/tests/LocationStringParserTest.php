<?php

use Skopenow\Location\Classes\LocationStringParser;

class LocationStringParserTest extends TestCase
{
    
    protected $locationStringParser;

    public function setup()
    {
        $this->locationStringParser = new LocationStringParser();
    }

    public function testExtractState()
    {
        $keyword = 'Oster Bay, NY';
        $state = $this->locationStringParser->extractState($keyword);
        $this->assertEquals('NY', $state);

        $keyword = 'Oster Bay, New York';
        $state = $this->locationStringParser->extractState($keyword);
        $this->assertEquals('New York', $state);

        $keyword = 'Oster Bay New York';
        $state = $this->locationStringParser->extractState($keyword);
        $this->assertEquals('', $state);

        $keyword = '';
        $state = $this->locationStringParser->extractState($keyword);
        $this->assertEquals('', $state);
    }

    public function testExtractCity()
    {
        $keyword = 'Oster Bay, New York';
        $city = $this->locationStringParser->extractCity($keyword);
        $this->assertEquals('Oster Bay', $city);
    
        $keyword = 'Nassau County, New York';
        $city = $this->locationStringParser->extractCity($keyword);
        $this->assertEquals('Nassau County', $city);


        $keyword = '';
        $city = $this->locationStringParser->extractCity($keyword);
        $this->assertEquals('', $city);

        $keyword = 'Nassau County';
        $city = $this->locationStringParser->extractCity($keyword);
        $this->assertEquals('', $city);

        $keyword = '92 Sunken Orchard ln, Oyster Bay, NY';
        $city = $this->locationStringParser->extractCity($keyword);
        $this->assertEquals('Oyster Bay', $city);

        
    }
}
