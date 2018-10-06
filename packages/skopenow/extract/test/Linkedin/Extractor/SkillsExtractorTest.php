<?php

/**
 * SkillsExtractorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Linkedin\Extractor\SkillsExtractor;

class SkillsExtractorTest extends TestCase
{
    public function testProcess()
    {
        $profileId = "jkevinscott";
        
        $mock = $this->getMockBuilder(SkillsExtractor::class)
                    ->setMethods(array('sendRequest'))
                    ->setConstructorArgs(array($profileId))
                    ->getMock();
        
        $result = file_get_contents(dirname(__DIR__) . "/../../linkedin-skills-request-reponse.json");
        
        $mock->method('sendRequest')
                ->will($this->returnValue(json_decode($result)));
        $expected = array ( 
                            array ( 
                                    'name' => 'Software Engineering',
                                    'skillId' => '10',
                                    'profileId' => 'ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc'
                                ),
                            array ( 
                                    'name' => 'Distributed Systems',
                                    'skillId' => '7',
                                    'profileId' => 'ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc' 
                                ),
                            array ( 
                                    'name' => 'Java',
                                    'skillId' => '35',
                                    'profileId' => 'ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc' 
                                ),
                            array ( 
                                    'name' => 'Leadership',
                                    'skillId' => '36',
                                    'profileId' => 'ACoAAACK3usBrG5QSCrPcFkuMIIzmWv0AsQyMFc'
                                )
            );
        $this->assertEquals($expected[1]['name'], $mock->Process()[1]['name']);
    }
    
    public function testSendRequest()
    {
        $profileId = "jkevinscott";
        $extractor = new SkillsExtractor($profileId);
        $requestUrl = $extractor->getRequestUrl();
        $options = $extractor->getCurlOptions();
        $response = $extractor->sendRequest($requestUrl, $options);
        
        $this->assertTrue(is_array(json_decode($response, true)['elements']));
    }
}