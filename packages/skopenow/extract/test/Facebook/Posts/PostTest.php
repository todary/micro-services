<?php

/**
 * PostTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */


use Skopenow\Extract\Facebook\Posts\Post;
use Skopenow\Extract\Facebook\Posts\Iterator\PostIterator;

class PostTest extends TestCase 
{
    public function testExtract()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $person_id = 1002057;
        $link = "https://www.facebook.com/rob.douglas.7923";
        config(['state.report_id' => $person_id]);
        
        $mock = $this->getMockBuilder(Post::class)
                ->setMethods(array('sendRequest', 'callGraph'))
                ->setConstructorArgs(array($link, new PostIterator()))
                ->getMock();
        
        $result = file_get_contents(dirname(__FILE__).'/../../../facebook-result.html');
        $facebook_graph_res = file_get_contents(dirname(__FILE__).'/../../../facebook-graph-search.json');
        
        $mock->method('sendRequest')
                ->will($this->returnValue(["body" => $result]));
        $mock->method('callGraph')
                ->will($this->returnValue(json_decode($facebook_graph_res, true)));
        
        $expected = new \ArrayIterator(
                    array
        (
            'Posts' => array
                (
                    array
                        (
                            'postBody' => '<a class=\"profileLink\" href=\"https://www.facebook.com/kim.k.douglas?fref=mentions\" data-hovercard=\"/ajax/hovercard/user.php?id=1626421190&extragetparams=%7B%22fref%22%3A%22mentions%22%7D\" data-hovercard-prefer-more-content-show=\"1\">Kim Koenigsberg Douglas</a>',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101392332132088',
                            'type' => 'post',
                            'date' => 'Wednesday, November 23, 2016 at 12:23am',
                            'time_stamp' => 1479853380
                        ),

                    array
                        (
                            'postBody' => '',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101383967420038',
                            'type' => 'post',
                            'date' => 'Wednesday, November 16, 2016 at 12:44pm',
                            'time_stamp' => 1479293040
                        ),

                    array
                        (
                            'postBody' => '<a class="profileLink" href="https://www.facebook.com/mattryanschwartz?fref=mentions" data-hovercard="/ajax/hovercard/user.php?id=1370700156&extragetparams=%7B%22fref%22%3A%22mentions%22%7D" data-hovercard-prefer-more-content-show="1">Matthew Ryan</a> you are not allowed to watch this until you watch my top 50 list of Anime movies... you just won&#039;t fully appreciate it.',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101052654603578',
                            'type' => 'post',
                            'date' => 'Sunday, December 6, 2015 at 2:41pm',
                            'time_stamp' => 1449405660
                        ),

                    array
                        (
                            'postBody' => 'Amazon Startup Feature: ', 
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101050373370188',
                            'type' => 'post',
                            'date' => 'Thursday, December 3, 2015 at 12:39pm',
                            'time_stamp' => 1449139140
                        ),

                    array
                        (
                            'postBody' => '<a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DsqA577_IoBk&h=ATMxa9BiUT8WrJb3Lr5Lzr-IX11WtR2zhkwkCgA54venpK7fdM2liZrrBHuX4elaoJuOKV3Gq3XOxdtmD1lFVugYVmftaVUbpWMv_PeF-WnvN2O-\-\loTW3SyJF0QijK7inppHeiStBJ0iTexSBRM42E8-60_YF9qevV7iIK1Ck6oYfBhlT1s1aSNh9GM4SDI2X-5JTapFPQIiYjWoyxpXqvPFPkE_fIOF9TMS34eZglZkupYKf3PiXmi5zN6CYtK9niyoF0bVn0oO8lId92ReIn4" target="_blank" data-ft="{"tn":"-U"}" data-lynx-mode="async">https://www.youtube.com/watch?v=sqA577_IoBk</a> <a class="profileLink" href="https://www.facebook.com/mattryanschwartz?fref=mentions" data-hovercard="/ajax/hovercard/user.php?id=1370700156&extragetparams=%7B%22fref%22%3A%22mentions%22%7D" data-hovercard-prefer-more-content-show="1">Matthew Ryan</a>',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101015682965038',
                            'type' => 'post',
                            'date' => 'Wednesday, October 14, 2015 at 10:35am',
                            'time_stamp' => 1444808100
                        ),

                    array
                        (
                            'postBody' => 'See you there!',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10101011619553148',
                            'type' => 'post',
                            'date' => 'Thursday, October 8, 2015 at 12:52pm',
                            'time_stamp' => 1444297920
                        ),

                    array
                        (
                            'postBody' => '<a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D4TYemDa1-EM%26feature%3Dyoutu.be&h=ATNVxwj6LeJSB8SpED6TLDv1uZJzkiZZRrdD5Bsgt2e8n2jJ0tslYtnkLhjJUu7721UosjTB7a3sHlQzg-4UGwC25YnlUOo1mLhqf15Q5R7W-iTFmWg6_h_b_VOKLEequz0HyBnzvkA9bxbuWLHGa7euv-p0SvNP6Xkb0M4Pgtqq4DtLKnt2sz7mHzSL5Y1Lq24tW4xifGxJVMFdPKA9lhvUroB_AbbTme3UU-QvOcDtGcfv9nx3JL2gYHt6fyKz00k1fWKlz3J__m5a8-wRXvGU" target="_blank" data-ft="{"tn":"-U"}" data-lynx-mode="async">https://www.youtube.com/watch?v=4TYemDa1-EM&feature=youtu.be</a>',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10100985708993118',
                            'type' => 'post',
                            'date' => 'Thursday, September 3, 2015 at 2:56pm',
                            'time_stamp' => 1441281360
                        ),

                    array
                        (
                            'postBody' => '',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10100985512586718',
                            'type' => 'post',
                            'date' => 'Thursday, September 3, 2015 at 9:58am',
                            'time_stamp' => 1441263480
                        ),

                    array
                        (
                            'postBody' => '',
                            'link' => 'https://www.facebook.com/rob.douglas.7923/posts/10100974992988078',
                            'type' => 'post',
                            'date' => 'Friday, August 21, 2015 at 2:48pm',
                            'time_stamp' => 1440157680
                        )

                ),

            'requestOptions' => array
                (
                    'pageIndex' => 0,
                    'offset' => 9,
                    'profileID' => 4713141
                )

        )
                );
        $mock->setRequestOptions(['offset' => 5]);
        $res = iterator_to_array($mock->Extract()->loopResults()->getResults());
        $this->assertEquals(9, count($res['Posts']));
    }
    
    public function testSetSessId()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new Post($link, new PostIterator());
        
        $this->assertInstanceOf(Post::class, $post->setSessId("ddddd"));
    }
    
    public function testSetRequestOptions()
    {
        $link = "https://www.facebook.com/rob.douglas.7923";
        $post = new Post($link, new PostIterator());
        
        $this->assertInstanceOf(Post::class, $post->setRequestOptions([]));
    }
    
    public function testGetRequestUrl()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $link = "https://www.facebook.com/rob.douglas.7923?get=1";
        $post = new Post($link, new PostIterator());
        
        $this->assertEquals("https://www.facebook.com/rob.douglas.7923?__sid=&get=1", $post->getRequestUrl($link));
    }
    
    public function testLoopResults()
    {
        $person_id = 1002057;
        $link = "https://www.facebook.com/rob.douglas.7923";
        config(['state.report_id' => $person_id]);
        
        $mock = $this->getMockBuilder(Post::class)
                ->setMethods(array('sendRequest', 'callGraph', 'Extract'))
                ->setConstructorArgs(array($link, new PostIterator()))
                ->getMock();
        
        $result = file_get_contents(dirname(__FILE__).'/../../../facebook-result.html');
        $facebook_graph_res = file_get_contents(dirname(__FILE__).'/../../../facebook-graph-search.json');
        
        $mock->method('sendRequest')
                ->will($this->returnValue(["body" => $result]));
        $mock->method('callGraph')
                ->will($this->returnValue(json_decode($facebook_graph_res, true)));
        
        $mock->Extract();
    }
    
    public function testSendRequest()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $person_id = 1002057;
        $link = "https://www.facebook.com/rob.douglas.7923";
        config(['state.report_id' => $person_id]);
        
        $post = new Post($link, new PostIterator());
        
        $link = $post->getFullLink($link);
        $requestUrl = $post->getRequestUrl($link);

        $options = $post->getCurlOptions();
        $html = $post->sendRequest($requestUrl, $options);
        
        $this->assertTrue(isset($html["body"]));
    }
    
    public function testSendRequestError()
    {
        require_once dirname(__FILE__) . '/../../../../../../../proxy_code/reg_actions.php';
        $person_id = 1002057;
        $link = "https://www.facebook.com/rob.douglas.7923";
        config(['state.report_id' => $person_id]);
        
        $post = new Post($link, new PostIterator());
        
        $link = $post->getFullLink($link);
        $requestUrl = $post->getRequestUrl($link);
        $requestUrl .= $requestUrl."?get=1?get=1";
        $options = [];
        $html = $post->sendRequest($requestUrl, $options);
        
        $this->assertTrue(empty($html["body"]));
    }
}
