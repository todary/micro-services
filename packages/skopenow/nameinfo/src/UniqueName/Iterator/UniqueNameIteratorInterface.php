<?php

/**
 * UniqueNameSearchIterator Interface
 *
 * PHP version 7
 *
 * @package   UniqueName
 * @author    Wael Salah <wael.fci@gmail.com>
 * @copyright 2017-2018 Wael Salah
 * @access    public
 * @license   http://licence.net BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://licence.net
 */

namespace Skopenow\NameInfo\UniqueName\Iterator;

/**
 * UniqueNameSearchIteratorInterface
 *
 *
 * @package   UniqueName
 * @author    Wael Salah <wael.fci@gmail.com>
 * @copyright 2017-2018 Wael Salah
 * @access    public
 * @license   http://licence.net BSD Licence
 * @version   Release: 1.0.0
 * @link      http://licence.net
 */

interface UniqueNameIteratorInterface extends \IteratorAggregate
{
    
    /**
    * addUniqueName
    *
    *
    * @access public
    * @param string $uniqueName
    * @return void
    */
    public function addUniqueName(array $uniqueName);
}