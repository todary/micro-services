<?php
/**
 * SkillsEndorsersExtractorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Linkedin\Extractor\SkillsEndorsersExtractor;

class SkillsEndorsersExtractorTest extends TestCase
{
    public function testProcess()
    {
        
        $skills_json = new \ArrayIterator(json_decode(file_get_contents(dirname(__DIR__) . "/../../linkedin-skills-result.json")));
        $mock = $this->getMockBuilder(SkillsEndorsersExtractor::class)
                    ->setMethods(array('sendRequest'))
                    ->setConstructorArgs(array($skills_json))
                    ->getMock();
        $endorsers_result_txt = file_get_contents(dirname(__DIR__) . "/../../linked-endorsers-request.json");
        
        $mock->method('sendRequest')
                ->will($this->returnValue(json_decode($endorsers_result_txt, true)));
        
        $expected = json_decode(file_get_contents(dirname(__DIR__) . "/../../linked-endorsers-result.json"), true);
        $this->assertEquals(41, count($mock->Process()));
    }
    
    public function testProcessReturnEmpty()
    {
        $t = new SkillsEndorsersExtractor(new \ArrayIterator([]));
        
        $this->assertEquals([], $t->Process());
    }
    
    public function testGetRequestUrl()
    {
        $t = new SkillsEndorsersExtractor(new \ArrayIterator([]));
        
        $this->assertEquals("https://www.linkedin.com/voyager/api/identity/profiles/4627/endorsements?count=100&includeHidden=true&pagingStart=0&q=findEndorsementsBySkillId&skillId=23&start=0", $t->getRequestUrl(4627, 23));
    }
    
    public function testSendRequest()
    {
        $skills = new \ArrayIterator(json_decode(file_get_contents(dirname(__DIR__) . "/../../linkedin-skills-result.json"), true));
        $t = new SkillsEndorsersExtractor($skills);
        $options = $t->getCurlOptions();
        $responses = $t->sendRequest($options);
        
        $this->assertTrue(is_array($responses));
    }
}