<?php

/**
 * FormaterEntrypointTest Class to entry points
 *
 * @category Test Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

use Skopenow\Formatter\EntryPoint;

/**
 * FormaterEntrypointTest Class to entry points
 *
 * @category Test Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EntrypointTest extends TestCase
{
    protected $formatter;

    public function setup()
    {
        $this->formatter = loadService("formatter");
    }

    /**
     * [testNamesEnteryPoint description]
     *
     * @return [void] [description]
     */
    public function testNamesEnteryPoint()
    {
        $inputs = ["names"=>["   some one<br> Name     "]];
        $names = new \ArrayIterator($inputs);
        $formatInputs = $this->formatter->format($names);

        $formattedInputs = new \ArrayIterator;
        $newInput["original"] = "   some one<br> Name     ";
        $newInput["formatted"] = "Some One Name";
        $formattedInputs->append($newInput);
    
        $output["names"] = $formattedInputs;
        $expected = new \ArrayIterator($output);

        $this->assertEquals($expected, $formatInputs);
    }

    /**
     * [testEmailsEnteryPoint description]
     *
     * @return [type] [description]
     */
    public function testEmailsEnteryPoint()
    {
        $inputs = ["emails"=>["SOmeone@company.com","    someone@company.net"]];
        $names = new \ArrayIterator($inputs);
        $formatInputs = $this->formatter->format($names);

        $formattedInputs = new \ArrayIterator;
        $newInput["original"] = "SOmeone@company.com";
        $newInput["formatted"] = "someone@company.com";
        $newInput2["original"] = "    someone@company.net";
        $newInput2["formatted"] = "someone@company.net";
        $formattedInputs->append($newInput);
        $formattedInputs->append($newInput2);
    
        $output["emails"] = $formattedInputs;
        $expected = new \ArrayIterator($output);

        $this->assertEquals($expected, $formatInputs);
    }

    /**
     * [testWebsiteEnteryPoint description]
     *
     * @return [type] [description]
     */
    public function testWebsiteEnteryPoint()
    {
        $inputs = ["websites"=>["facebook.com"]];
        $names = new \ArrayIterator($inputs);
        $formatInputs = $this->formatter->format($names);

        $formattedInputs = new \ArrayIterator;
        $newInput["original"] = "facebook.com";
        $newInput["formatted"] = "http://facebook.com";
        $formattedInputs->append($newInput);
    
        $output["websites"] = $formattedInputs;
        $expected = new \ArrayIterator($output);

        $this->assertEquals($expected, $formatInputs);
    }

    /**
     * [testPonesEnteryPoint description]
     *
     * @return [type] [description]
     */
    public function testPonesEnteryPoint()
    {

        $inputs = ["phones"=>["TEL:00201099999999"]];
        $names = new \ArrayIterator($inputs);
        $formatInputs = $this->formatter->format($names);
        
        $formattedInputs = new \ArrayIterator;
        $newInput["original"] = "TEL:00201099999999";
        $newInput["formatted"] = "(002) 010-99999999";
        $formattedInputs->append($newInput);
    
        $output["phones"] = $formattedInputs;
        $expected = new \ArrayIterator($output);
        $this->assertEquals($expected, $formatInputs);
    }

    /**
     * [testAddressEnteryPoint description]
     *
     * @return [type] [description]
     */
    public function testAddressEnteryPoint()
    {
        $data[0]['full_address'] = "15 John kennedy lane, New York, NY 10001, USA";
        $data[0]['city'] = "New York, NY";
        $data[0]['zpc'] = "10001";
        $data[0]['country'] = "USA";

        $inputs = ["addresses"=>$data];
        $names = new \ArrayIterator($inputs);
        $formatInputs = $this->formatter->format($names);


        $formattedInputs = new \ArrayIterator;
        $newInput["original"] = $data[0];
        $newInput["formatted"] = "15 John kennedy ln, New York, NY 10001, USA";
        $formattedInputs->append($newInput);
    
        $output["addresses"] = $formattedInputs;
        $expected = new \ArrayIterator($output);
        $this->assertEquals($expected, $formatInputs);
    }
}
