<?php

/**
 * UniqueNameFacade
 *
 *
 * PHP version 7
 *
 * @package   UniqueNameFacade
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\UniqueName;

use Skopenow\NameInfo\UniqueName\Iterator\UniqueNameIterator;
use Skopenow\NameInfo\UniqueName\Iterator\UniqueNameIteratorInterface;
use Skopenow\NameInfo\UniqueName\Search\PiplUniqueNameSearch;
use Skopenow\NameInfo\UniqueName\Search\HowManyOfMeUniqueNameSearch;

/**
 * UniqueNameFacade class
 *
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class UniqueNameFacade
{

    /**
    * createUniqueNames
    *
    * 
    * @access public
    * @param array $names
    * @param bool $isRelative
    * @param string $apiKey
    * @return UniqueNameIteratorInterface
    */
    public function createUniqueNames($names, $isRelative, $apiKey) : \Iterator
    {
        $uniqueNameIterator = new UniqueNameIterator;
        $piplResultsCount = 0;
        $howManyResultsCount = 0;
        $personID = config('state.report_id');
        foreach ($names as $name) {
            $firstName = strtolower(trim($name['firstName']," \t\n\r\0\x0B,.()'\";:"));
            $middleName = strtolower(trim($name['middleName']," \t\n\r\0\x0B,.()'\";:"));
            $lastName = strtolower(trim($name['lastName']," \t\n\r\0\x0B,.()'\";:"));

            if (strlen($firstName) < 2 || strlen($lastName) < 2) continue;
            
            $piplSearch = new PiplUniqueNameSearch($firstName, $lastName, $apiKey);
            $piplResults = $piplSearch->search();

            $howManSearch = new HowManyOfMeUniqueNameSearch($firstName, $lastName);
            $howManyResults = $howManSearch->search();

            if($piplResults != false && $howManyResults != false)
            {
                $piplResultsCount = $piplResults['resultsCount'];
                $howManyResultsCount = $howManyResults['resultsCount'];
            }

            $outArray = [
                        "input" => ["firstName" => $firstName, "middleName" => $middleName, "lastName" => $lastName],
                        "unique" => 1
            ];
            if((!$howManyResultsCount && !$piplResultsCount) || ($howManyResultsCount > 25 || $piplResultsCount > 25) && !$isRelative) {
                $outArray["unique"] = 0;
            }
            $uniqueNameIterator->addUniqueName($outArray);
        }
        // Todo here:logger
        $loggerData = [
              "Message" => "Checking Unique Names \n",
              "ReportId" => $personID,
              "OriginalNames" => $names,
              "ModifiedNames" => iterator_to_array($uniqueNameIterator->getIterator())
        ];
        // Todo here: logger
        
        return $uniqueNameIterator->getIterator();
    }
}