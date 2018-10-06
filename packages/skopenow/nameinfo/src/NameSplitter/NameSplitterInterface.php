<?php

/**
 * Handles splitting names operation
 *
 * PHP version 7
 *
 * @package   NameSplitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter;

/**
 * NameSplitterInterface Interface
 *
 *
 * @package   NameSplitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

interface NameSplitterInterface
{

    /**
    * Constructor
    *
    *
    * @access public
    * @param string $name
    */
    public function __construct(string $name);

    /**
    * prepareNameInput
    *
    * Prepare name input by removeing junk characters
    *
    * @access public
    * @return void
    */
    public function prepareNameInput();

    /**
    * honorificNicknames
    *
    * Toggle dots after honorific nicknames from the first name.
    * ex: Dr. Rob => Dr Rob or Dr Rob => Dr. Rob
    *
    * @access public
    * @param boolean $removeDots remove dots
    * @return void
    */
    public function honorificNicknames(bool $removeDots = false);

    /**
    * extractParts
    *
    *
    * @access public
    * @return void
    */
    public function extractNameParts();

    /**
    * removeExtraNames
    *
    *
    * @access public
    * @return void
    */
    public function removeExtraNames();

    /**
    * combineParts
    *
    *
    * @access public
    * @return void
    */
    public function combineParts();
    
    /**
     * splitDashedLastName
     *
     *
     * @access public
     * @param string $lastname
     *
     * @return array
     */
    public function splitDashedLastName(string $lastname): array;
        
    /**
     * suffixLastName
     *
     * @access public
     * @param string $lastName
     *
     * @return bool
     */
    public function suffixLastName(string $lastName) : bool;
}