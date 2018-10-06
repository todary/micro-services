<?php

/**
 * EntryPoint
 *
 * PHP version 7
 *
 * @package   EntryPoint
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo;

use Skopenow\NameInfo\NameSplitter\Parser\NameSplitterParser;
use Skopenow\NameInfo\NickNames\NickNames;
use Skopenow\NameInfo\UniqueName\UniqueName;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;
use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIterator;
use Skopenow\NameInfo\NickNames\Iterator\NickNamesIterator;
use Skopenow\NameInfo\UniqueName\Search\PiplUniqueNameSearch;
use Skopenow\NameInfo\UniqueName\Search\HowManyOfMeUniqueNameSearch;
use Skopenow\NameInfo\NameSplitter\NameSplitter;

/**
 * EntryPoint Class
 *
 * EntryPoint class for nameinfo service
 *
 * @package   EntryPoint
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   Release: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class EntryPoint
{
    
    /**
    * NameSplit
    *
    * @access public
    * @param Iterator $args
    * @return Iterator
    */
    public function nameSplit(\Iterator $args)
    {
        $iterator = new NameSplitterIterator();
        $commandList = new NameSplitterCommandList();
        $parser = new NameSplitterParser($args, $commandList, $iterator);
        return $parser->process();
    }
    
    /**
     * honorificNicknames
     * 
     * 
     * @param string $name
     * @param bool $removeDots
     * @return string
     */
    public function honorificNicknames(string $name, bool $removeDots = false)
    {
        $nameSplitter = new NameSplitter($name);
        $nameSplitter->prepareNameInput();
        $nameSplitter->honorificNicknames($removeDots);
        return $nameSplitter->getProcessedName();
    }

    /**
    * NickNames
    *
    * @access public
    * @param Iterator $args
    * @return Iterator
    */
    public function nickNames(\Iterator $args)
    {
        $nickNamesIterator = new NickNamesIterator();
        $nickNames = new NickNames($args, $nickNamesIterator);
        $nickNames->search();
        return $nickNames->getNickNames();
    }

     /**
    * UniqueName
    *
    * @access public
    * @param Iterator $args
    * @param bool $isRelative
    * @param $apiKey
    * @return Iterator
    */
    public function uniqueName(\Iterator $args, bool $isRelative = false, string $apiKey = "CONTACT-gmcr1h343kx5nk01ncew52aw")
    {
        $nickNames = new UniqueName($args, $isRelative, $apiKey);
        return $nickNames->checkUniqueName();
    }
    
    public function SearchPiplWithFirstAndLastNames(string $firstName, string $lastName, string $apiKey = "CONTACT-gmcr1h343kx5nk01ncew52aw")
    {
        $piplUniqueNameSearch = new PiplUniqueNameSearch($firstName, $lastName, $apiKey);
        return $piplUniqueNameSearch->search();
    }
    
    public function SearchHowManyOfMe(string $firstName, string $lastName)
    {
        $HowManyOfMeUniqueNameSearch = new HowManyOfMeUniqueNameSearch($firstName, $lastName);
        return $HowManyOfMeUniqueNameSearch->search();
    }
}