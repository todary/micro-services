<?php

use Skopenow\Location\Classes\LocationStringAnalyzer;

class LocationStringAnalyzerTest extends TestCase
{
    
    protected $geoHelper;

    public function setup()
    {
        $this->locationStringAnalyzer = new LocationStringAnalyzer();
    }

    public function testIsAddressString()
    {
        $testString = '299 SW 8th St, Miami, FL 33130, USA';
        $result = $this->locationStringAnalyzer->isAddressString($testString);
        $this->assertTrue($result);

        $testString = '';
        $result = $this->locationStringAnalyzer->isAddressString($testString);
        $this->assertFalse($result);

        $testString = 'Oysterbay, New York';
        $result = $this->locationStringAnalyzer->isAddressString($testString);
        $this->assertFalse($result);
    }

    public function testIsCityString()
    {
        $testString = 'Oysterbay, New York';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertTrue($result);

        $testString = 'New York';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertTrue($result);

        $testString = 'Nassau County, NY';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertTrue($result);

        $testString = 'Nassau County, N';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertFalse($result);

        $testString = 'Nassau County NY';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertTrue($result);

        $testString = '125';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertFalse($result);
        

        $testString = '299 SW 8th St, Miami, FL 33130, USA';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertFalse($result);


        $testString = 'a';
        $result = $this->locationStringAnalyzer->isCityString($testString);
        $this->assertFalse($result);
    }
}
