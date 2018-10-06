<?php

/**
 * NickNamesIterator
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

namespace Skopenow\NameInfo\NickNames\Iterator;

use Skopenow\NameInfo\NickNames\Iterator\NickNamesIteratorInterface;

/**
 * NickNamesIterator Class
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

class NickNamesIterator implements NickNamesIteratorInterface
{

    /**
    * $nickNamesList
    *
    *
    * @var array
    */
    protected $nickNamesList = [];

    /**
    * addNickName
    *
    *
    * @access public
    * @param array $nickName
    * @return void
    */
    public function addNickName(array $nickName)
    {
        if(!in_array($nickName, $this->nickNamesList))
            array_push($this->nickNamesList, $nickName);
    }

    /**
    * getIterator
    *
    *
    * @access public
    * @return ArrayIterator
    */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->nickNamesList);
    }
}