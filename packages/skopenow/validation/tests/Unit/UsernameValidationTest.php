<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\UsernameValidation;

/**
 *
 */
class UsernameValidationTest extends \PHPUnit_Framework_TestCase
{
    private $validation;

    public function setUp()
    {
    }

    /**
     * test_if_username_is_valid
     * @param string $expected
     * @param string $username
     * @dataProvider provider_test_if_username_is_valid
     */
    public function test_if_username_is_valid($expected, $username)
    {
        $validation = new UsernameValidation($username);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_username_is_valid
     * @return array
     */
    public function provider_test_if_username_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'username',
                        'isValid' => true,
                        'normalized' => 'username',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['username']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'username@qts',
                        'isValid' => true,
                        'normalized' => 'username@qts',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['username@qts']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '.......',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 390,
                    ]),
                ]),
                new \ArrayIterator(['.......']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '****',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 390,
                    ]),
                ]),
                new \ArrayIterator(['****']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'username@qts.net',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 390,
                    ]),
                    new \ArrayIterator([
                        'input' => 'username@qts',
                        'isValid' => true,
                        'normalized' => 'username@qts',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => '$$$$',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 390,
                    ]),
                ]),
                new \ArrayIterator(['username@qts.net', 'username@qts', '$$$$']),
            ],
        ];
    }
}
