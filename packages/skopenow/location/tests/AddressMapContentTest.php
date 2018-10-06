<?php

use Skopenow\Location\Classes\AddressMapContent;

class AddressMapContentTest extends TestCase
{
    protected $addressMapContent;

    public function setup()
    {        
        $this->addressMapContent = new AddressMapContent();
    }

    public function testGetMapContent()
    {
        $queryData = array('address'=>"oster bay, NY");
        $accountPassword = "";

        $mapResult = $this->addressMapContent->getMapContent($queryData, $accountPassword);
        $result = json_decode($mapResult['content'], true);
        $this->assertArrayHasKey("results", $result);
    }

    public function testGetMapContentNewYourk()
    {
        $queryData = array('address'=>"new york, new york");
        $accountPassword = "";

        $mapResult = $this->addressMapContent->getMapContent($queryData, $accountPassword);
        $result = json_decode($mapResult['content'], true);
        $this->assertArrayHasKey("results", $result);
    }
}
