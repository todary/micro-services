<?php

/**
 * This is the UniqueNameTest class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\UniqueName\UniqueName;

class UniqueNameTest extends \TestCase
{
    public function testCheckUniqueName()
    {
        $names = new \ArrayIterator([
                                        
                                            [
                                                "firstName" => "Robert",
                                                "middleName" => "",
                                                "lastName" => "Douglas"
                                            ]
    ]);
        $uniqueName = new UniqueName($names, false, "CONTACT-gmcr1h343kx5nk01ncew52aw");

        $this->assertEquals(0, $uniqueName->checkUniqueName()->offsetGet(0)['unique']);
    }
    
    public function testCheckUniqueNameIsUnique()
    {
        $names = new \ArrayIterator([
                                        
                                            [
                                                "firstName" => "Rob",
                                                "middleName" => "",
                                                "lastName" => "Douglas"
                                            ]
    ]);
        $uniqueName = new UniqueName($names, false, "CONTACT-gmcr1h343kx5nk01ncew52aw");

        $this->assertEquals(0, $uniqueName->checkUniqueName()->offsetGet(0)['unique']);
    }
    
    public function testCheckUniqueNameNonUnique()
    {
        $names = new \ArrayIterator([
                                        
                                            [
                                                "firstName" => "Christopher",
                                                "middleName" => "",
                                                "lastName" => "Wishnie"
                                            ]
    ]);
        $uniqueName = new UniqueName($names, false, "CONTACT-gmcr1h343kx5nk01ncew52aw");

        $this->assertEquals(1, $uniqueName->checkUniqueName()->offsetGet(0)['unique']);
    }
    
}