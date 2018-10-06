<?php

/**
 * Handles splitting name string into first, last, and middle names
 *
 * PHP version 7
 *
 * @category  Naming_String
 * @package   NameSplitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter;

use Skopenow\NameInfo\NameSplitter\NameSplitterInterface;

/**
 * NameSplitter Class
 *
 * @category  Name_Splitters
 * @package   NameInfo
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   Release: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class NameSplitter implements NameSplitterInterface
{

    /**
     * $name
     *
     * @var string
     */
    private $_name;

    /**
     * $processedName
     *
     * @var mixed
     */
    private $_processedName;

    /**
     * $wordsIgnored
     *
     * @var array
     */
    private $_wordsIgnored = array('ii','iii','jr','sr','iv');

    /**
     * Constructor
     *
     * @access public
     * @param  string $name
     *
     */
    public function __construct(string $name)
    {
        $this->_name = $name;
    }

    /**
     * prepareNameInput
     *
     * Prepare name input by removeing junk characters
     *
     * @access public
     * @return void
     */
    public function prepareNameInput()
    {
        $name = preg_replace("/[^@\s]*@[^@\s]*\.[^@\s]*/", "", $this->_name); // Remove email from name
        $name = preg_replace("/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i", "", $name);
        $name = preg_replace("/\s+/", " ", $name);

        $this->_processedName = trim($name);
    }

    /**
     * honorificNicknames
     *
     * Toggle dots after honorific nicknames from the first name.
     * ex: Dr. Rob => Dr Rob or Dr Rob => Dr. Rob
     *
     * @access public
     * @param  boolean $removeDots remove dots
     *
     * @return void
     */
    public function honorificNicknames(bool $removeDots = false)
    {
        $honorificNicknames = ['dr'];
        if (!$removeDots) {
            array_walk(
                $honorificNicknames, function (&$item, $key) {
                    $item = "#^(" . $item . ")([^\S]|$)#i";
                }
            );
            $this->_processedName = trim(preg_replace($honorificNicknames, "$1. ", $this->_processedName));
            return;
        }
        // remove dot's from honorific nicknames
        array_walk(
            $honorificNicknames, function (&$item, $key) {
                $item = "#^(" . $item . ")\s*(\.)#i";
            }
        );

        $this->_processedName = trim(preg_replace($honorificNicknames, "$1", $this->_processedName));
        return;
    }

    /**
     * extractNameParts
     *
     * @access public
     * @return void
     */
    public function extractNameParts()
    {
        preg_match_all("/([^\\s]+)/i", $this->_processedName, $arrayOfName);
        $arr = array_unique($arrayOfName, SORT_REGULAR);
        $this->_processedName = array_pop($arr);
    }

    /**
     * removeExtraNames
     *
     * remove extra name like (jr, sr,ii)
     *
     * @access public
     * @return void
     */
    public function removeExtraNames()
    {
        if(isset($this->_processedName[2])) {
            if($this->suffixLastName($this->_processedName[2])) {
                unset($this->_processedName[2]);
                $this->_processedName = array_values($this->_processedName); // rearnge index
            }
        }
    }

    /**
     * combineParts
     *
     * @access public
     * @return void
     */
    public function combineParts()
    {
        $names = [];
        $countOfName = count($this->_processedName);

        $firstName = "";
        $middleName = "";
        $lastName = "";

        for ($i=0; $i < $countOfName; $i++) {
            if($i == 0) {
                $firstName = trim(strtolower($this->_processedName[0]));
            } elseif($i == 1 and array_key_exists(2, $this->_processedName)) {
                $middleName = trim(strtolower($this->_processedName[1]));
            } else {
                $lastName .= $this->_processedName[$i].' ';
            }
        }
        $lastName = trim(strtolower($lastName));

        $splittedLastNames = $this->splitDashedLastName($lastName);

        foreach($splittedLastNames as $splittedLastName){
            $fullName = $firstName . ' ' . $middleName;
            $fullName = trim($fullName) . ' ' . $splittedLastName;
            $name = [
                "firstName" => $firstName,
                "middleName" => $middleName,
                "lastName" => $splittedLastName,
                "fullName" => $fullName
            ];

            $names[] = $name;
        }

        $this->_processedName = [
                "input" => $this->_name,
                "splitted" => $names,
            ];
    }

    /**
     * splitDashedLastName
     *
     *
     * @access public
     * @param string $lastname
     *
     * @return array
     */
    public function splitDashedLastName(string $lastname): array
    {
        $splitLastnames[] = $lastname; //$spldLastnames[0]='cuevas-martinez';

        if (strpos($lastname, '-') !== false) {
            $lastname = explode('-', $lastname); //$lastname=['cuevas','martinez']

            //['cuevas-martinez', 'cuevas', 'martinez']
            $splitLastnames = array_merge($splitLastnames, $lastname);
        }
        return $splitLastnames;
    }

    /**
     * getProcessedName
     *
     * @access public
     * @return array
     */
    public function getProcessedName()
    {
        return $this->_processedName;
    }

    /**
     * suffixLastName
     *
     * @access public
     * @param string $lastName
     *
     * @return bool
     */
    public function suffixLastName(string $lastName) : bool
    {
        // Removed (i) as per client request on task #11126
        $filter_ = trim(strtolower(rtrim($lastName, '.')));
        if(in_array($filter_, $this->_wordsIgnored)) {
            return true;
        }
        return false;
    }
}
