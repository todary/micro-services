<?php
namespace Tests\Unit;

use Skopenow\Validation\EntryPoint;

/**
 *
 */
class EntryPointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test_multiple_inputs_validation
     * @param \Iterator $inputs
     * @dataProvider provider_test_multiple_inputs_validation
     */
    public function test_multiple_inputs_validation($expected, $inputs)
    {
        $validation = new EntryPoint;
        $validation->validate($inputs);
        // var_dump($validation->getResults());
        $this->assertEquals($expected, $validation->getResults());
    }

    /**
     * provider_test_multiple_inputs_validation
     * @return array
     */
    public function provider_test_multiple_inputs_validation()
    {
        return [
            [
                new \ArrayIterator([
                    'name' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "Peter",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 325,
                        ]),
                        new \ArrayIterator([
                            "input" => "Peter Mark",
                            "isValid" => true,
                            "normalized" => 'Peter Mark',
                            "error" => null,
                        ]),
                    ]),
                    'address' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "Oyster Bay , NY",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 335,
                        ]),
                        new \ArrayIterator([
                            "input" => "11 Oyster Bay , NY",
                            "isValid" => true,
                            "normalized" => '11 Oyster Bay , NY',
                            "error" => null,
                        ]),
                    ]),
                    'phone' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "2222222222",
                            "isValid" => true,
                            "normalized" => '2222222222',
                            "error" => null,
                        ]),
                        new \ArrayIterator([
                            "input" => "222222222",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 340,
                        ]),
                    ]),
                    'age' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "22",
                            "isValid" => true,
                            "normalized" => '22',
                            "error" => null,
                        ]),
                        new \ArrayIterator([
                            "input" => "222",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 355,
                        ]),
                    ]),
                    'birthdate' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "22",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 'Input must be valid date.',
                        ]),
                        new \ArrayIterator([
                            "input" => "2/22/2006",
                            "isValid" => true,
                            "normalized" => '2/22/2006',
                            "error" => null,
                        ]),
                    ]),
                    'email' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "email@qts.",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 'Input must be valid email address.',
                        ]),
                        new \ArrayIterator([
                            "input" => "email@qts.net",
                            "isValid" => true,
                            "normalized" => 'email@qts.net',
                            "error" => null,
                        ]),
                    ]),
                    'location' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "Oyster Bay, N",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 330,
                        ]),
                        new \ArrayIterator([
                            "input" => "Oyster Bay",
                            "isValid" => true,
                            "normalized" => 'Oyster Bay',
                            "error" => null,
                        ]),
                    ]),
                    'occupation' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "Web, Developer, QTS",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 380,
                        ]),
                        new \ArrayIterator([
                            "input" => "Web Developer, QTS",
                            "isValid" => true,
                            "normalized" => 'Web Developer, QTS',
                            "error" => null,
                        ]),
                    ]),
                    'url' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "www.qts.net",
                            "isValid" => true,
                            "normalized" => 'http://www.qts.net',
                            "error" => null,
                        ]),
                        new \ArrayIterator([
                            "input" => ".qts.",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 'Input must be valid url.',
                        ]),
                    ]),
                    'username' => new \ArrayIterator([
                        new \ArrayIterator([
                            "input" => "****",
                            "isValid" => false,
                            "normalized" => null,
                            "error" => 390,
                        ]),
                        new \ArrayIterator([
                            "input" => "username",
                            "isValid" => true,
                            "normalized" => 'username',
                            "error" => null,
                        ]),
                    ]),
                ]),
                new \ArrayIterator([
                    'name' => new \ArrayIterator(['Peter', 'Peter Mark']),
                    'address' => new \ArrayIterator([
                        'Oyster Bay , NY', '11 Oyster Bay , NY',
                    ]),
                    'phone' => new \ArrayIterator(['2222222222', '222222222']),
                    'age' => new \ArrayIterator(['22', '222']),
                    'birthdate' => new \ArrayIterator(['22', '2/22/2006']),
                    'email' => new \ArrayIterator(['email@qts.', 'email@qts.net']),
                    'location' => new \ArrayIterator([
                        'Oyster Bay, N', 'Oyster Bay',
                    ]),
                    'occupation' => new \ArrayIterator([
                        'Web, Developer, QTS', 'Web Developer, QTS',
                    ]),
                    'url' => new \ArrayIterator([
                        'www.qts.net', '.qts.']),
                    'username' => new \ArrayIterator(['****', 'username']),
                ]),
            ],

        ];
    }
}
