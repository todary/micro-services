<?php


/**
 * This is the NameSplitterExtractNamePartsCommand class test
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterExtractNamePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterRemoveExtraNamesCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCombinePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterPrepareNameInputCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterHonorificNickNamesCommand;
use Skopenow\NameInfo\NameSplitter\NameSplitter;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandInterface;

class NameSplitterCombinePartsCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $nameSplitter = new NameSplitter("Rob Douglas John-Smith");
        (new NameSplitterPrepareNameInputCommand())->execute($nameSplitter);
        (new NameSplitterHonorificNickNamesCommand())->execute($nameSplitter);
        (new NameSplitterExtractNamePartsCommand())->execute($nameSplitter);
        (new NameSplitterRemoveExtraNamesCommand())->execute($nameSplitter);
        (new NameSplitterCombinePartsCommand())->execute($nameSplitter);

        $expected = array(
            'input' => 'Rob Douglas John-Smith',
            'splitted' => array
        (
            0 => array
                (
                    'firstName' => "rob",
                    'middleName' => "douglas",
                    'lastName' => "john-smith"
                ),
                1 => array
                (
                    'firstName' => "rob",
                    'middleName' => "douglas",
                    'lastName' => "john"
                ),
                2 => array
                (
                    'firstName' => "rob",
                    'middleName' => "douglas",
                    'lastName' => "smith"
                )

        )
);
      $this->assertEquals($expected, $nameSplitter->getProcessedName());
    }
}