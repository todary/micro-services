<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 12/11/17
 * Time: 05:14 م
 */

use Skopenow\Api\Library\Auth\KeyAuthMethod;
use Skopenow\Api\Library\AuthUser\AuthUser;

class KeyAuthMethodTest extends TestCase
{

    /**
     * @param $user
     */
    public function testauthAPI()
    {
        $mockCollection = Mockery::mock(AuthUser::class);
        $mockCollection->shouldReceive('get_collection_ids')->once()->andReturn([1, 2, 3, 4, 5, 6]);

        $headers['x-api-key']='e8f9e1de352e11cbeb9635ab470ec798f575fbe7';
        $authUserObject = new AuthUser($headers);

    }
}
