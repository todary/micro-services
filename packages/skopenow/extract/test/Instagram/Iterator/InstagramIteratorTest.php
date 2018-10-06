<?php
/**
 * InstagramIteratorTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

use Skopenow\Extract\Instagram\Iterator\InstagramIterator;

class InstagramIteratorTest extends TestCase 
{
    public function testGetIterator()
    {
        $iterator = new InstagramIterator;
        
        $iterator->addResult(array ( 
                         '__typename' => 'GraphImage',
                         'id' => 1594620601007233518, 
                         'comments_disabled' => '',
                         'dimensions' => array ( 
                                'height' => 1080,
                                'width' => 1080 ),
                         'gating_info' => '',
                         'media_preview' => 'ACoqwgKUj9KnWPPXj0pWUZ/wrMqxXxRtqwqjq2f6Ypki7Tj0/wD10BYplcU2pnqHNWiS99q3EADA7+4/Hp+FK7BBv69qoA4qxMBgEH8PwpWKvoWJTK67wAFHOB78/p+dT2RV42BxnPPr7fnSxP8A6OQQeB09eKit7ciQRoeWUFz6DrgfhgZ9TSK21KMhGTj9ahrpJ7FJeSMYGBjqMdK51l2kr6HH5VSIFC1rERR4kkwWxwPX3x/nFZv8Qq9cD9yPZiBUvoUtLmgiK+Q4IJGQf58Z/lnPtS2aDc7jpwoPsB/+qo9oaAEjJCpgntzVm2/1f4n+dW0ktCbt7k+a5ScASN/vN/M11a1yU/8ArG/3j/OpQM//2Q==',
                         'owner' => array ( 
                                'id' => 1920718607 
                             ), 
                         'thumbnail_src' => 'https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/s640x640/sh0.08/e35/21294376_737347319797041_6941913502600658944_n.jpg',
                         'thumbnail_resources' => array ( ),
                         'is_video' => '',
                         'code' => '',
                         'BYhOvzqjlnu',
                         'date' => 1504313610, 
                         'display_src' => 'https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/e35/21294376_737347319797041_6941913502600658944_n.jpg',
                         'caption' => '#fromosutoido',
                         'comments' => array (
                             'count' => 2 
                             ),
                         'likes' => array ( 
                             'count' => 58 
                             ) 
                         ));
        
        $expected = new \ArrayIterator(array(array ( 
                         '__typename' => 'GraphImage',
                         'id' => 1594620601007233518, 
                         'comments_disabled' => '',
                         'dimensions' => array ( 
                                'height' => 1080,
                                'width' => 1080 ),
                         'gating_info' => '',
                         'media_preview' => 'ACoqwgKUj9KnWPPXj0pWUZ/wrMqxXxRtqwqjq2f6Ypki7Tj0/wD10BYplcU2pnqHNWiS99q3EADA7+4/Hp+FK7BBv69qoA4qxMBgEH8PwpWKvoWJTK67wAFHOB78/p+dT2RV42BxnPPr7fnSxP8A6OQQeB09eKit7ciQRoeWUFz6DrgfhgZ9TSK21KMhGTj9ahrpJ7FJeSMYGBjqMdK51l2kr6HH5VSIFC1rERR4kkwWxwPX3x/nFZv8Qq9cD9yPZiBUvoUtLmgiK+Q4IJGQf58Z/lnPtS2aDc7jpwoPsB/+qo9oaAEjJCpgntzVm2/1f4n+dW0ktCbt7k+a5ScASN/vN/M11a1yU/8ArG/3j/OpQM//2Q==',
                         'owner' => array ( 
                                'id' => 1920718607 
                             ), 
                         'thumbnail_src' => 'https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/s640x640/sh0.08/e35/21294376_737347319797041_6941913502600658944_n.jpg',
                         'thumbnail_resources' => array ( ),
                         'is_video' => '',
                         'code' => '',
                         'BYhOvzqjlnu',
                         'date' => 1504313610, 
                         'display_src' => 'https://ig-s-b-a.akamaihd.net/h-ak-igx/t51.2885-15/e35/21294376_737347319797041_6941913502600658944_n.jpg',
                         'caption' => '#fromosutoido',
                         'comments' => array (
                             'count' => 2 
                             ),
                         'likes' => array ( 
                             'count' => 58 
                             ) 
                         )));
        
        $this->assertEquals($expected , $iterator->getIterator());
    }
}
