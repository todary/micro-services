<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\AgeValidation;

/**
 *
 */
class AgeValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_age_is_valid
     * @param string $expected
     * @param string $age
     * @dataProvider provider_test_if_age_is_valid
     */
    public function test_if_age_is_valid($expected, $age)
    {
        $validation = new AgeValidation($age);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_age_is_valid
     * @return array
     */
    public function provider_test_if_age_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '1',
                        'isValid' => true,
                        'normalized' => '1',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['1']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '0',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 355,
                    ]),
                ]),
                new \ArrayIterator(['0']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '-1',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 355,
                    ]),
                ]),
                new \ArrayIterator(['-1']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'age@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 355,
                    ]),
                ]),
                new \ArrayIterator(['age@']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '120',
                        'isValid' => true,
                        'normalized' => '120',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => '150',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 355,
                    ]),
                    new \ArrayIterator([
                        'input' => '--',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 355,
                    ]),
                ]),
                new \ArrayIterator(['120', '150', '--']),
            ],
        ];
    }
}
