<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\PhoneValidation;

/**
 *
 */
class PhoneValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_phone_is_valid
     * @param string $expected
     * @param string $phone
     * @dataProvider provider_test_if_phone_is_valid
     */
    public function test_if_phone_is_valid($expected, $phone)
    {
        $validation = new PhoneValidation($phone);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_phone_is_valid
     * @return array
     */
    public function provider_test_if_phone_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '(222) 222-2222',
                        'isValid' => true,
                        'normalized' => '2222222222',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['(222) 222-2222']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '(222) 222-222',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 340,
                    ]),
                ]),
                new \ArrayIterator(['(222) 222-222']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '(222) 222-2222',
                        'isValid' => true,
                        'normalized' => '2222222222',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => '(222) 222-222',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 340,
                    ]),
                ]),
                new \ArrayIterator(['(222) 222-2222', '(222) 222-222']),
            ],
        ];
    }
}
