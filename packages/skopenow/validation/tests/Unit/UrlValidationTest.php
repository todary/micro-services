<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\UrlValidation;

class UrlValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_if_url_is_valid
     * @param string $expected
     * @param string $url
     * @dataProvider provider_test_if_url_is_valid
     */
    public function test_if_url_is_valid($expected, $url)
    {
        $validation = new UrlValidation($url);
        $this->assertEquals($expected, $validation->validate());
    }

    /**
     * provider_test_if_url_is_valid
     * @return array
     */
    public function provider_test_if_url_is_valid()
    {
        return [
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'qts.net',
                        'isValid' => true,
                        'normalized' => 'http://qts.net',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['qts.net']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'http://qts.net',
                        'isValid' => true,
                        'normalized' => 'http://qts.net',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['http://qts.net']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'www.qts.net',
                        'isValid' => true,
                        'normalized' => 'http://www.qts.net',
                        'error' => null,
                    ]),
                ]),
                new \ArrayIterator(['www.qts.net']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'url@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid url.',
                    ]),
                ]),
                new \ArrayIterator(['url@']),
            ],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        'input' => 'http://qts.net',
                        'isValid' => true,
                        'normalized' => 'http://qts.net',
                        'error' => null,
                    ]),
                    new \ArrayIterator([
                        'input' => 'url@',
                        'isValid' => false,
                        'normalized' => null,
                        'error' => 'Input must be valid url.',
                    ]),
                ]),
                new \ArrayIterator(['http://qts.net', 'url@']),
            ],
        ];
    }
}
