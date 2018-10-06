<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\BirthdateValidation;

/**
 *
 */
class BirthdateValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_date_of_birth_is_valid
     * @param string $expected
     * @param string $date_of_birth
     * @dataProvider provider_test_if_date_of_birth_is_valid
     */
    public function test_if_date_of_birth_is_valid($expected, $date_of_birth)
    {
        $validation = new BirthdateValidation($date_of_birth);

        $this->assertEquals(
            $expected,
            $validation->validate()
        );
    }

    /**
     * provider_test_if_date_of_birth_is_valid
     * @return array
     */
    public function provider_test_if_date_of_birth_is_valid()
    {
        return [
            [
                new \ArrayIterator([new \ArrayIterator([
                    'input' => '2006/01/01',
                    'isValid' => true,
                    'normalized' => '2006/01/01',
                    'error' => null,
                ])]),
                new \ArrayIterator(['2006/01/01']),
            ],
            [
                new \ArrayIterator([new \ArrayIterator([
                    'input' => 'dob@qts',
                    'isValid' => false,
                    'normalized' => null,
                    'error' => 'Input must be valid date.',
                ])]),
                new \ArrayIterator(['dob@qts']),
            ],
            [
                new \ArrayIterator([new \ArrayIterator([
                    'input' => 'dob@qts.',
                    'isValid' => false,
                    'normalized' => null,
                    'error' => 'Input must be valid date.',
                ])]),
                new \ArrayIterator(['dob@qts.']),
            ],
            [
                new \ArrayIterator([new \ArrayIterator([
                    'input' => 'dob@',
                    'isValid' => false,
                    'normalized' => null,
                    'error' => 'Input must be valid date.',
                ])]),
                new \ArrayIterator(['dob@']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => '1/1/2006',
                        'isValid' => true,
                        'normalized' => '1/1/2006',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'dob@qts.',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid date.',
                    ]),
                    new \ArrayIterator([
                        'input' => 'dob@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid date.',
                    ]),
                ]),
                new \ArrayIterator(['1/1/2006', 'dob@qts.', 'dob@']),
            ],
        ];
    }
}
