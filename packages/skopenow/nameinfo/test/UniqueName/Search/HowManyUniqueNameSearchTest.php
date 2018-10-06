<?php

/**
 * This is the HowManyOfMeUniqueNameSearch class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

 namespace Skopenow\NameInfoTest;

 use Skopenow\NameInfo\UniqueName\Search\HowManyOfMeUniqueNameSearch;

 class HowManyOfMeUniqueNameSearchTest extends \TestCase
 {
    public function testSearchResultWithCache()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::put($cacheKey."_howmany", 8.0, 60*24);

        $PiplMock = $this->getMockBuilder(HowManyOfMeUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas"])
            ->setMethods(array('getResults'))
            ->getMock();

        $result = file_get_contents(dirname(__DIR__) . "/../../result.html");

        $PiplMock->method('getResults')
                ->will($this->returnValue(['resultsCount' => 8.0]));

        $this->assertEquals(['resultsCount' => 8.0], $PiplMock->search());
    }

    public function testSearchgetResultsValidateError()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_howmany");

        $howMany = new HowManyOfMeUniqueNameSearch("Rob", "");
        $searchData = $howMany->search();
        // $expected = ["resultsCount" => 921.0];

        $mock = $this->getMockBuilder(HowManyOfMeUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", ""])
            ->setMethods(array('validate'))
            ->getMock();

        $mock->method('validate')
                  ->will($this->returnValue(false));

        $this->assertFalse($mock->search());
    }

    public function testSearchSendHttpRequestResponseError()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_howmany");

        // $howMany = new HowManyOfMeUniqueNameSearch("Rob", "Douglas");
        // $searchData = $howMany->search();
        // var_dump($searchData);
        // $expected = ["resultsCount" => 921.0];

        $mock = $this->getMockBuilder(HowManyOfMeUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas"])
            ->setMethods(array('sendRequest'))
            ->getMock();

        $mock->method('sendRequest')
                  ->will($this->returnValue(["error_no"=>123, "body" => ""]));

        $this->assertEquals(false, $mock->search());
    }

    public function testSearchSendHttpRequestResponseSuccess()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_howmany");

        // $howMany = new HowManyOfMeUniqueNameSearch("Rob", "Douglas");
        // $searchData = $howMany->search();
        // var_dump($searchData);
        $expected = file_get_contents(dirname(__DIR__) . "/../../result.html");;

        $mock = $this->getMockBuilder(HowManyOfMeUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas"])
            ->setMethods(array('curl_content'))
            ->getMock();

        $mock->method('curl_content')
                  ->will($this->returnValue(["body" => $expected]));

        $this->assertEquals(['resultsCount' => 8.0], $mock->search());
    }

    public function testSearchFindMatchSuccess()
    {
        $cacheKey = 'Rob,Douglas';
        \Cache::forget($cacheKey."_howmany");

        // $howMany = new HowManyOfMeUniqueNameSearch("Rob", "Douglas");
        // $searchData = $howMany->search();
        // var_dump($searchData);
        $expected = file_get_contents(dirname(__DIR__) . "/../../result.html");;

        $mock = $this->getMockBuilder(HowManyOfMeUniqueNameSearch::class)
            ->setConstructorArgs(["Rob", "Douglas"])
            ->setMethods(array('findMatchAndSearch'))
            ->getMock();

        $mock->method('findMatchAndSearch')
                  ->will($this->returnValue(['resultsCount' => 8.0]));

        $this->assertEquals(['resultsCount' => 8.0], $mock->search());
    }
 }