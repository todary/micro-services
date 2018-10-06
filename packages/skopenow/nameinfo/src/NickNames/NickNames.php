<?php

/**
 * Handles nicknames operations
 *
 * PHP version 7
 *
 * @package   NickNames
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NickNames;

use Skopenow\NameInfo\NickNames\NickNamesInterface;
use Skopenow\NameInfo\NickNames\Iterator\NickNamesIteratorInterface;

/**
 * NickNames Class
 *
 *
 * @package   NameInfo
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class NickNames implements NickNamesInterface
{

    /**
    * $names
    *
    *
    * @var ArrayIterator
    */
    private $names;

    /**
    * $iterator
    * 
    *
    * @var NickNamesIterator
    */
    private $nickNamesiterator;

    /**
    * Constructor
    *
    *
    * @access public
    * @param Iterator $names
    */
    public function __construct(\Iterator $names, NickNamesIteratorInterface $nickNamesiterator)
    {
        $this->names = $names;
        $this->nickNamesiterator = $nickNamesiterator;
    }

    /**
    * search
    *
    * search nicknames
    * 
    * @access public
    * @return void 
    */
    public function search()
    {
        $key = json_encode($this->names);
        $cachedData = \Cache::get($key);
        if ($cachedData) {
            $response = $cachedData;
        } else {
            try {
                $response = $this->fetchData($this->names);
                \Cache::put($key, $response, 60*24*30*2);
            } catch (\Exception $ex) {
                $response = [];
            }
        }
        if($response) {
            foreach ($response as $nameArray) {
                 $this->nickNamesiterator->addNickName($nameArray);
            } 
        }
    }

    /**
    * getNickNames
    *
    * get nicknames
    * 
    * @access public
    * @return iterator 
    */
    public function getNickNames() : \Iterator
    {
        return $this->nickNamesiterator->getIterator();
    }

    /**
    * getNames
    *
    * 
    * @access public
    * @return ArrayIterator $names
    */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * fetchData
     * 
     * 
     * @access public
     * @param \Iterator $names
     * @return array
     */
    public function fetchData(\Iterator $names)
    {
        $namesArray = iterator_to_array($names);
        $outputArr = [];
        if($namesArray) {
            foreach($namesArray as $name) {
        $name = strtolower(trim($name));
         $dbOpj = app()->DynamoDB->query(array(
          'TableName' => 'nickNames',
          'ExpressionAttributeNames' => array('#n' => 'nickName'),
          'KeyConditionExpression' => '#n = :name',
          'ExpressionAttributeValues' =>  array (
              ':name' => array('S' => "{$name}")
          )
         ));
         
         $nickNamesArray = [];     
         if(is_object($dbOpj)) {
             $foundNames = $dbOpj->get("Items");
             if($foundNames) {
                 foreach($foundNames as $foundName) {
                     if(is_array($foundName) and array_key_exists('nickNames', $foundName) and count($foundName['nickNames']))
                     {
                         $nickNamesArray[] = array_merge(current($foundName['nickNames']),$nickNamesArray);
                     }
                 }
            }
         }
        $outputArr[] = [
                                 "input" => $name,
                                 "nickNames" => (!empty($nickNamesArray)?array_unique(current($nickNamesArray)):[])
                         ];
            }
        }
        return $outputArr; 
    }
}