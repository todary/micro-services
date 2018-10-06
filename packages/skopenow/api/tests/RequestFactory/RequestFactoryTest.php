<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 23/10/17
 * Time: 01:37 م
 */

require('vendor/autoload.php');

use Symfony\Component\HttpKernel\Client;

class RequestFactoryTest extends TestCase
{


//    public function testCreateRequest()
//    {
//
//        $parameter = array(
//            'input' =>
//                array(
//                    'name' =>
//                        array(
//                            0 => 'test1',
//                            1 => 'test2',
//                        ),
//                ),
//            'filters' =>
//                array(
//                    'nssame' => 'all',
//                    'location' => '',
//                    'family' => '1',
//                    'exact' => '',
//                    'hide' =>
//                        array(
//                            0 => 'ip',
//                            1 => 'photos',
//                        ),
//                ),
//            'output' =>
//                array(
//                    'type' => '',
//                    'destination' => '',
//                    'url' => '',
//                    'email' => '',
//                    'ftp' => '',
//                ),
//        );
//
//        $client = $this->call('POST', '/v1/search', $parameter)
//                       ->header('Content-Type','application/json')
//                       ->header('x-api-key','test')
//                       ->sendHeaders();
//
//        $this->assertEquals(json_decode($client->getContent()), null);
//
//    }
}
