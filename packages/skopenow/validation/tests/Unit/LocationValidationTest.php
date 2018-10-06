<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\LocationValidation;

/**
 *
 */
class LocationValidationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test_location_validation_functionalities
     * @param type $expected
     * @param type|null $locations
     * @dataProvider provider_test_location_validation_functionalities
     */
    public function test_location_validation_functionalities(
        $expected,
        $locations = null
    ) {
        $validation = new LocationValidation($locations);

        $this->assertEquals(
            $expected,
            $validation->validate()
        );
    }

    /**
     * provider_test_location_validation_functionalities
     * @return array
     */
    public function provider_test_location_validation_functionalities()
    {
        return [
            [new \ArrayIterator((array) false), new \ArrayIterator],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Y',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator(['Y']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Y,',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]), new \ArrayIterator(['Y,']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '--',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]), new \ArrayIterator(['--']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Oyster Bay, NY',
                        'isValid' => true,
                        'normalized' => 'Oyster Bay, NY',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Oyster Bay, NY']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Oyster Bay, N',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator(['Oyster Bay, N']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '11111',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator(['11111']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'tt, tt, tt',
                        'isValid' => true,
                        'normalized' => 'tt, tt, tt',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['tt, tt, tt']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'tt tt tt',
                        'isValid' => true,
                        'normalized' => 'tt tt, tt',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['tt tt tt']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'tttttt',
                        'isValid' => true,
                        'normalized' => 'tttttt',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['tttttt']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'tt,,',
                        'isValid' => true,
                        'normalized' => 'tt',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['tt,,']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => ', us',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator([', us']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '1, usa',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator(['1, usa']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => ', United States',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator([', United States']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => ', , , ',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                ]),
                new \ArrayIterator([', , , ']),
            ],

            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'y',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                    new \ArrayIterator([
                        'input' => 'Y,',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                    new \ArrayIterator([
                        'input' => '--',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 330,
                    ]),
                    new \ArrayIterator([
                        'input' => 'tt, tt',
                        'isValid' => true,
                        'normalized' => 'tt, tt',
                        'error' => null,
                    ]),
                ]),

                new \ArrayIterator(['y', 'Y,', '--', 'tt, tt']),
            ],
        ];
    }
}
