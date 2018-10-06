<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\OccupationValidation;

/**
 *
 */
class OccupationValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_occupation_is_valid
     * @param string $expected
     * @param string $occupation
     * @dataProvider provider_test_if_occupation_is_valid
     */
    public function test_if_occupation_is_valid($expected, $occupation)
    {
        $validation = new OccupationValidation($occupation);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_occupation_is_valid
     * @return array
     */
    public function provider_test_if_occupation_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Web Developer',
                        'isValid' => true,
                        'normalized' => 'Web Developer',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Web Developer']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Web Developer, QTS',
                        'isValid' => true,
                        'normalized' => 'Web Developer, QTS',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Web Developer, QTS']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Web, Developer, QTS',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 380,
                    ]),
                ]),
                new \ArrayIterator(['Web, Developer, QTS']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Web Developer',
                        'isValid' => true,
                        'normalized' => 'Web Developer',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'Web Developer, QTS',
                        'isValid' => true,
                        'normalized' => 'Web Developer, QTS',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'Web, Developer, QTS',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 380,
                    ]),
                ]),
                new \ArrayIterator([
                    'Web Developer',
                    'Web Developer, QTS',
                    'Web, Developer, QTS',
                ]),
            ],
        ];
    }
}
