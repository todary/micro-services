<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\EmailValidation;

/**
 *
 */
class EmailValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_email_is_valid
     * @param string $expected
     * @param string $email
     * @dataProvider provider_test_if_email_is_valid
     */
    public function test_if_email_is_valid($expected, $email)
    {
        $validation = new EmailValidation($email);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_email_is_valid
     * @return array
     */
    public function provider_test_if_email_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'email@qts.net',
                        'isValid' => true,
                        'normalized' => 'email@qts.net',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['email@qts.net']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'email@qts',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid email address.',
                    ]),
                ]),
                new \ArrayIterator(['email@qts']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'email@qts.',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid email address.',
                    ]),
                ]),
                new \ArrayIterator(['email@qts.']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'email@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid email address.',
                    ]),
                ]),
                new \ArrayIterator(['email@']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'email@qts.net',
                        'isValid' => true,
                        'normalized' => 'email@qts.net',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'email@qts.',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid email address.',
                    ]),
                    new \ArrayIterator([
                        'input' => 'email@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid email address.',
                    ]),
                ]),
                new \ArrayIterator(['email@qts.net', 'email@qts.', 'email@']),
            ],
        ];
    }
}
