<?php

/**
 * Handles unique name operations
 *
 * Note: Lines that begin with [Begin To do] and [End To do] these requires
 * Addition of the new services that do this task i.e. 
 * (Yii::app()->cache->get($cacheKey."_pipl")) you need to set this according 
 * to your service
 *
 * PHP version 7
 *
 * @package   UniqueName
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\UniqueName;

use Skopenow\NameInfo\UniqueName\UniqueNameInterface;
use Skopenow\NameInfo\UniqueName\UniqueNameFacade;

/**
 * UniqueName class
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

class UniqueName implements UniqueNameInterface
{

    /**
    * $names
    *
    *   
    * @var array
    */
    private $names = [];

    /**
    * $isRelative
    *
    *   
    * @var bool
    */
    private $isRelative;

    /**
    * $apiKey
    *
    *   
    * @var string
    */
    private $apiKey;
    
    /**
    * Constructor
    *
    * 
    * @access public
    * @param array $names
    * @param bool $isRelative
    * @param string $apiKey
    * @return void
    */
    public function __construct(\Iterator $names, bool $isRelative = false, string $apiKey = '')
    {
        $this->names = $names;
        $this->isRelative = $isRelative;
        $this->apiKey = $apiKey;
    }

    /**
    * checkUniqueName
    *
    * 
    * @access public
    * @return \Iterator
    */
    public function checkUniqueName() : \Iterator
    {
        return (new UniqueNameFacade())->createUniqueNames($this->names, $this->isRelative, $this->apiKey);
    }
}