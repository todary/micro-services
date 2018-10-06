<?php

/**
 * This is the PiplUniqueNameSearchTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

 namespace Skopenow\NameInfoTest;

 use Skopenow\NameInfo\UniqueName\Search\PiplUniqueNameSearch;

 class PiplUniqueNameSearchTest extends \TestCase
 {
    public function testSearchResultWithCache()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::put($cacheKey."_pipl", 19, 60*24);

//        $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
//            ->setConstructorArgs(["Rob", "Douglas", "1231321"])
//            ->setMethods(array('getResults'))
//            ->getMock();
//
//        $result = file_get_contents(dirname(__DIR__) . "/../../result.json");
//
//        $PiplMock->method('getResults')
//                ->will($this->returnValue(['resultsCount' => 19, "gender" => ""]));

        $piple = new PiplUniqueNameSearch("Rob", "Douglas", "CONTACT-gmcr1h343kx5nk01ncew52aw");
        
        $this->assertEquals(['resultsCount' => 19, "gender" => ""], $piple->search());
    }

    public function testSearchResultWithoutCache()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");
        $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas", "1231321"])
            ->setMethods(array('sendRequest'))
            ->getMock();

        $result = file_get_contents(dirname(__DIR__) . "/../../result.json");

        $PiplMock->method('sendRequest')
                ->will($this->returnValue(['body' => $result]));

        $this->assertEquals(['resultsCount' => 19, "gender" => "male"], $PiplMock->search());
    }

    public function testSearchResponseError()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

        $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas", "1231321"])
            ->setMethods(array('sendRequest'))
            ->getMock();
        
        $PiplMock->method('sendRequest')
                ->will($this->returnValue(["error_no"=>123, "body" => ""]));
        $this->assertEquals(false, $PiplMock->search());
    }
//
    public function testSearchResponseInvalidStatusCode()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

        $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas", "1231321"])
            ->setMethods(array('checkResponse'))
            ->getMock();

        $PiplMock->method('checkResponse')
                ->will($this->returnValue(false));

        $this->assertEquals(false, $PiplMock->search());
    }
//
    public function testSearchResponseEmpty()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

        $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas", "1231321"])
            ->setMethods(array('sendRequest'))
            ->getMock();

        $PiplMock->method('sendRequest')
                ->will($this->returnValue([]));

        $this->assertEquals(false, $PiplMock->search());
    }
//
     public function testSearchResponseErrorStatusCode()
     {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

         $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
             ->setConstructorArgs(["Kazi", "Magarde", "1231321"])
             ->setMethods(array('sendRequest'))
             ->getMock();

         $PiplMock->method('sendRequest')
             ->will($this->returnValue(["body" => '{"@search_id": 1708231223541758460953415615848374091, "@http_status_code": 400}']));
         $this->assertEquals(false, $PiplMock->search());
     }
//
     public function testSearchResultSuccess()
     {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

         $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
             ->setConstructorArgs(["Rob", "Douglas", "CONTACT-gmcr1h343kx5nk01ncew52aw"])
             ->setMethods(array('sendRequest'))
             ->getMock();

         $result = file_get_contents(dirname(__DIR__) . "/../../result.json");

         $PiplMock->method('sendRequest')
             ->will($this->returnValue(["body" => $result]));

         $this->assertEquals(array('resultsCount' => 19, "gender" => "male"), $PiplMock->search());
     }
//
     public function testSearchResultNoData()
     {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

         $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
             ->setConstructorArgs(["Rob", "Douglas", "CONTACT-gmcr1h343kx5nk01ncew52aw"])
             ->setMethods(array('sendRequest'))
             ->getMock();

         $result = file_get_contents(dirname(__DIR__) . "/../../result2.json");

         $PiplMock->method('sendRequest')
             ->will($this->returnValue(["body" => $result]));

         $this->assertEquals(array('resultsCount' => 0), $PiplMock->search());
     }
//
     public function testSearchResultErrorNo()
     {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

         $PiplMock = $this->getMockBuilder(PiplUniqueNameSearch::class)
             ->setConstructorArgs(["Rob", "Douglas", "asd"])
             ->setMethods(array('sendRequest'))
             ->getMock();

         $result = file_get_contents(dirname(__DIR__) . "/../../result.json");

         $PiplMock->method('sendRequest')
             ->will($this->returnValue(["body" => "", "error_no" => 22]));

         $this->assertEquals(false, $PiplMock->search());
     }
//
    public function testSearchValidationMissingParams()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

        $pipl = new PiplUniqueNameSearch("A", "", "123");
        $this->assertEquals(false, $pipl->search());
    }
//
     public function testSearchValidationParamsLength()
     {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_pipl");

         $pipl = new PiplUniqueNameSearch("A", "B", "123");
         $this->assertEquals(false, $pipl->search());
     }
 }