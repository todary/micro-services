<?php
namespace Tests\Unit;

use Skopenow\Validation\Classes\NamepartsValidation;

/**
 * Test Nameparts Validation Class
 */
class NamepartsValidationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test_nameparts_validation_functionalities
     * @param type $expected
     * @param type|null $names
     * @dataProvider provider_test_nameparts_validation_functionalities
     */
    public function test_nameparts_validation_functionalities($expected, $names = null)
    {
        $validation = new NamepartsValidation($names);

        $this->assertEquals(
            $expected,
            $validation->validate()
        );
    }

    /**
     * provider_test_nameparts_validation_functionalities
     * @return array
     */
    public function provider_test_nameparts_validation_functionalities()
    {
        return [
            [new \ArrayIterator((array) false), new \ArrayIterator],
            [
                new \ArrayIterator([
                    new \ArrayIterator([
                        "input" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "elbadry-ahmed",
                        ],
                        "isValid" => true,
                        "normalized" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "elbadry-ahmed",
                        ],
                        "error" => null,
                    ]),
                    new \ArrayIterator([
                        "input" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "elbadry",
                        ],
                        "isValid" => true,
                        "normalized" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "elbadry",
                        ],
                        "error" => null,
                    ]),
                    new \ArrayIterator([
                        "input" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "ahmed",
                        ],
                        "isValid" => true,
                        "normalized" => [
                            "firstName" => "wael",
                            "middleName" => "salah",
                            "lastName" => "ahmed",
                        ],
                        "error" => null,
                    ]),
                ]),
                new \ArrayIterator([
                    [
                        "input" => "Wael Salah Elbadry-Ahmed",
                        "splitted" => [
                            [
                                "firstName" => "wael",
                                "middleName" => "salah",
                                "lastName" => "elbadry-ahmed",
                            ],
                            [
                                "firstName" => "wael",
                                "middleName" => "salah",
                                "lastName" => "elbadry",
                            ],
                            [
                                "firstName" => "wael",
                                "middleName" => "salah",
                                "lastName" => "ahmed",
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }
}
