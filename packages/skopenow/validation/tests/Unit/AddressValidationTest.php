<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\AddressValidation;

/**
 *
 */
class AddressValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_address_is_valid
     * @param string $expected
     * @param string $address
     * @dataProvider provider_test_if_address_is_valid
     */
    public function test_if_address_is_valid($expected, $address)
    {
        $validation = new AddressValidation($address);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_address_is_valid
     * @return array
     */
    public function provider_test_if_address_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '11 Oyster Bay , NY',
                        'isValid' => true,
                        'normalized' => '11 Oyster Bay , NY',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['11 Oyster Bay , NY']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Oyster Bay , NY',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 335,
                    ]),
                ]),
                new \ArrayIterator(['Oyster Bay , NY']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Oyster Bay , NY',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 335,
                    ]),
                    new \ArrayIterator([
                        'input' => '11 Oyster Bay , NY',
                        'isValid' => true,
                        'normalized' => '11 Oyster Bay , NY',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Oyster Bay , NY', '11 Oyster Bay , NY']),
            ],
        ];
    }
}
