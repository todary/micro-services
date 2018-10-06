<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\NameValidation;

/**
 * Test Name Validation Class
 */
class NameValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_name_validation_functionalities
     * @param type $expected
     * @param type|null $names
     * @dataProvider provider_test_name_validation_functionalities
     */
    public function test_name_validation_functionalities($expected, $names = null)
    {
        $validation = new NameValidation($names);

        $this->assertEquals(
            $expected,
            $validation->validate()
        );
    }

    /**
     * provider_test_name_validation_functionalities
     * @return array
     */
    public function provider_test_name_validation_functionalities()
    {
        return [
            [new \ArrayIterator((array) false), new \ArrayIterator],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 325,
                    ]),
                ]),
                new \ArrayIterator(['']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'John',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 325,
                    ]),
                ]),
                new \ArrayIterator(['John']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Peter Mark',
                        'isValid' => true,
                        'normalized' => 'Peter Mark',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Peter Mark']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Peter Mark-John',
                        'isValid' => true,
                        'normalized' => 'Peter Mark-John',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Peter Mark-John']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Peter Mark jr',
                        'isValid' => true,
                        'normalized' => 'Peter Mark jr',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Peter Mark jr']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Peter Mark John',
                        'isValid' => true,
                        'normalized' => 'Peter Mark John',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['Peter Mark John']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '$$ $$',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 324,
                    ]),
                ]),
                new \ArrayIterator(['$$ $$']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '-- --',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 324,
                    ]),
                ]),
                new \ArrayIterator(['-- --']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'tt tt',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 324,
                    ]),
                ]),
                new \ArrayIterator(['tt tt']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'Peter Mark John',
                        'isValid' => true,
                        'normalized' => 'Peter Mark John',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'Mark John',
                        'isValid' => true,
                        'normalized' => 'Mark John',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'tt tt',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 324,
                    ]),
                    new \ArrayIterator([
                        'input' => 'Peter Mark jr',
                        'isValid' => true,
                        'normalized' => 'Peter Mark jr',
                        'error' => null,
                    ]),
                ]),

                new \ArrayIterator([
                    'Peter Mark John',
                    'Mark John',
                    'tt tt',
                    'Peter Mark jr',
                ]),
            ],
        ];
    }
}
