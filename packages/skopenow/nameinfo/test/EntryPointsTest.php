<?php

/**
 * EntryPointTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */
namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\EntryPoint;

class EntryPointTest extends \TestCase
{
    public function testNameSplitSuccess()
    {
        $entryPoint = new EntryPoint();

        $input = array(
        'input' => 'Rob Douglas jr',
            'splitted' => array
    (
        0 => array
        (
            'firstName' => "rob",
            'middleName' => "",
            'lastName' => "douglas"
        )

    )
        );
        $expected = new \ArrayIterator([$input]);
        $data = json_decode('["Rob Douglas jr"]');
        $nameSplitterIterator = new \ArrayIterator($data);
        $actual = $entryPoint->nameSplit($nameSplitterIterator);
        $this->assertEquals($expected, $actual);
    }

    public function testNickNames()
    {
        $entryPoint = new EntryPoint();

        $nickNamesIterator = new \ArrayIterator([]);
        $output = $entryPoint->nickNames($nickNamesIterator);

        $this->assertEquals($nickNamesIterator, $output);
    }

    public function testUniqueName()
    {
        $entryPoint = new EntryPoint();

        $data = [
                                        
                                            [
                                                "firstName" => "Kazi",
                                                "middleName" => "",
                                                "lastName" => "Magarde"
                                            ]
    ];

    $uniqueNamesIterator = new \ArrayIterator($data);
    $actual = $entryPoint->uniqueName($uniqueNamesIterator);
    $expected = new \ArrayIterator(
                [
                    [
                     'input' => array
                        (
                            'firstName' => 'kazi',
                            'middleName' => '', 
                            'lastName' => 'magarde'
                        ),
                    'unique' => 0
                        ]
                ]
            );
        $this->assertEquals($expected, $actual);
    }
    
    public function testSearchPiplWithFirstAndLastNamesWithCache()
    {   
        $cacheKey = 'Kazi,Magarde';
        \Cache::put($cacheKey."_pipl", 0, 60*24);
        
        $entryPoint = new EntryPoint();
        $actual = $entryPoint->SearchPiplWithFirstAndLastNames("Kazi", "Magarde"); 
        $expected = array(
    'resultsCount' => 0,
    'gender' => false
            );
        
        $this->assertEquals($expected, $actual);
    }
    
    public function testSearchPiplWithFirstAndLastNamesWithoutCache()
    {   
        $cacheKey = 'Kazi,Magarde';
        \Cache::forget($cacheKey."_pipl");
        
        $entryPoint = new EntryPoint();
        $actual = $entryPoint->SearchPiplWithFirstAndLastNames("Kazi", "Magarde"); 
        $expected = array(
    'resultsCount' => 0,
    'gender' => false
            );
        
        $this->assertEquals($expected, $actual);
    }
    
    public function testSearchHowManyOfMeWithCache()
    {
        $cacheKey = 'Kazi,Magarde';
        \Cache::put($cacheKey."_howmany", 1, 60*24);    

        $entryPoint = new EntryPoint();
        $actual = $entryPoint->SearchHowManyOfMe("Kazi", "Magarde"); 
        $expected = array(
    'resultsCount' => 1
            );
       
        $this->assertEquals($expected, $actual);
    }
    
    public function testSearchHowManyOfMeWithoutCache()
    {
        $cacheKey = 'Kazi,Magarde';
        \Cache::forget($cacheKey."_howmany");
        
        $entryPoint = new EntryPoint();
        $actual = $entryPoint->SearchHowManyOfMe("Kazi", "Magarde"); 
        $expected = array(
    'resultsCount' => 1
            );
        
        $this->assertEquals($expected, $actual);
    }
}