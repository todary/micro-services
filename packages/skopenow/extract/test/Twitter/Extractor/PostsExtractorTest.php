<?php

/**
 * PostsExtractorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Twitter\Extractor\PostsExtractor;

class PostsExtractorTest extends TestCase
{
    public function testProcess()
    {
        $profile = "RobDouglas";
        $postsLink= "https://www.twitter.com/".$profile;
        config(['state.report_id' => 011002047]);
        config(['state.combination_id' => 10075947]);
        
        $mock = $this->getMockBuilder(PostsExtractor::class)
            ->setConstructorArgs([$postsLink])
            ->setMethods(array('sendRequest'))
            ->getMock();
        
        $result = file_get_contents(dirname(__DIR__) . "/../../twitter-posts-result.html");
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $result]));
        
        $expected = array ( 
                            array ( 
                                    'tweet_id' => 904440574488563717,
                                    'permalinkUrl' => 'http://twitter.com/RobDouglas/status/904440574488563717'
                                ),
                            array ( 
                                    'tweet_id' => 904149791713751040,
                                    'permalinkUrl' => 'http://twitter.com/RobDouglas/status/904149791713751040' 
                                ),
                            array ( 
                                'tweet_id' => 903779767178199040,
                                'permalinkUrl' => 'http://twitter.com/RobDouglas/status/903779767178199040'
                                )
            );
        $this->assertEquals($expected[0]['permalinkUrl'], $mock->Process()[0]['permalinkUrl']);
    }
}