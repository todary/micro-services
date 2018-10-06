<?php

/**
 * NameSplitterTest
 * 
 * @package Test
 * @access public
 * @copyright 2017-2018 Queen tech
 * @author Queen tech <info@queentechsolutions.net>
 * @version 1.0.0
 */

namespace Skopenow\NameInfoTest;

use Skopenow\NameInfo\NameSplitter\NameSplitter;

class NameSplitterTest extends \PHPUnit\Framework\TestCase
{

  public function testExtractNameParts()
  {
      $nameSplitter = new NameSplitter("Rob Douglas sr");
      $nameSplitter->prepareNameInput();
      $nameSplitter->honorificNicknames();
      $nameSplitter->extractNameParts();

      $this->assertEquals(['Rob', 'Douglas', 'sr'], $nameSplitter->getProcessedName());

  }

  public function testRemoveExtraNames()
  {
      $nameSplitter = new NameSplitter("Rob Douglas jr");
      $nameSplitter->prepareNameInput();
      $nameSplitter->honorificNicknames();
      $nameSplitter->extractNameParts();
      $nameSplitter->removeExtraNames();

      $this->assertEquals(['Rob', 'Douglas'], $nameSplitter->getProcessedName());

  }

  public function testCombineParts()
  {
      $nameSplitter = new NameSplitter("Rob Douglas jr");
      $nameSplitter->prepareNameInput();
      $nameSplitter->honorificNicknames();
      $nameSplitter->extractNameParts();
      $nameSplitter->removeExtraNames();
      $nameSplitter->combineParts();
      $expected = array(
          'input' => 'Rob Douglas jr',
          'splitted' => array
          (
              0 => array
              (
                  'firstName' => "rob",
                  'middleName' => "",
                  'lastName' => "douglas"
              )

          )
      );
      $this->assertEquals($expected, $nameSplitter->getProcessedName());

  }

  public function testGetProcessedName()
  {
      $nameSplitter = new NameSplitter("wael salah elbadry wael.fci@gmail.com");
      $nameSplitter->prepareNameInput();

      $this->assertEquals("wael salah elbadry", $nameSplitter->getProcessedName());
  }
}