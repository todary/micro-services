<?php

/**
 * YoutubeTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Twitter\Extractor\MediaExtractor;

class MediaExtractorTest extends TestCase
{
    public function testProcess()
    {
        $profile = "waelE2020";
            $mediaUrl= "https://www.twitter.com/".$profile."/media";
            config(['state.report_id' => 011002047]);
            config(['state.combination_id' => 10075947]);
        
        $mock = $this->getMockBuilder(MediaExtractor::class)
            ->setConstructorArgs([$mediaUrl])
            ->setMethods(array('sendRequest'))
            ->getMock();
        
        $result = file_get_contents(dirname(__DIR__) . "/../../twitter-media-result.html");
        
        $mock->method('sendRequest')
                ->will($this->returnValue(['body' => $result]));
        
        $expected = array ( 
                            'imagestweets' => array ( 
                                        array ( 
                                            'tweet_id' => 857984490920128513,
                                            'permalinkUrl' => 'http://twitter.com/waelE2020/status/857984490920128513',
                                            'imageUrl' => array ( 
                                                'imageUrl' => 'https://pbs.twimg.com/media/C-gsRV9XsAEFPND.jpg'
                                                ) 
                                            ),
                                        array ( 
                                             'tweet_id' => 857977626618724352,
                                             'permalinkUrl' => 'http://twitter.com/waelE2020/status/857977626618724352',
                                             'imageUrl' => array (
                                                   'imageUrl' => 'https://pbs.twimg.com/media/C-gmFQGXYAEf6qb.jpg'
                                                 ) 
                                            )
                                ),
                            'vidoetweets' => array()
            );
        $this->assertEquals($expected['imagestweets'][0]['permalinkUrl'], $mock->Process()['imagestweets'][0]['permalinkUrl']);
    }
}