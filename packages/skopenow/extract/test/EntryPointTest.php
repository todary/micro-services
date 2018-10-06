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


use Skopenow\Extract\EntryPoint;

class EntryPointTest extends TestCase 
{
//    public function testExtractFacebookPosts()
//    {
//        require_once dirname(__FILE__) . '/../../../../../proxy_code/reg_actions.php';
//        $person_id = 1002057;
//        $link = "https://www.facebook.com/rob.douglas.7923";
//        config(['state.report_id' => $person_id]);
//        $entrypoint = new EntryPoint();
//        
//        $res = $entrypoint->extractFacebookPosts($link);
//        
//        $this->assertEquals(5, count($res->offsetGet('Posts')));
//    }
    
    public function testExtractFacebookUserImages()
    {
        require_once dirname(__FILE__) . '/../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923";
        $person_id = 1002057;
            config(['state.report_id' => $person_id]);
            $entrypoint = new EntryPoint();
            $extractedData = $entrypoint->extractFacebookUserImages($link, 1);
            
            $this->assertEquals(2, count(iterator_to_array($extractedData)));
    }
}