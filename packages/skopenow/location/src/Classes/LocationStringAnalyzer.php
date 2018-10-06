<?php

/**
 * LocationStringAnalyzer
 *
 * PHP version 7.0
 * 
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Location\Classes;

/**
 * LocationStringAnalyzer
 * 
 * @category Classes
 * @package  Location
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class LocationStringAnalyzer
{
    /**
     * Check f given string is for an address
     * 
     * @param string $testString [description]
     * 
     * @return boolean             [description]
     */
    public function isAddressString(string $testString)
    {
        $address = trim($testString);
        if (!$address) {
            return false;
        }

        $matchesCount = preg_match('#^[\d-]+(\w{2})?\s.+,\s*[\w|\s]+$#', $address);

        if (!$matchesCount) {
            return false;
        }

        return true;
    }

    /**
     * Check if a given string is for a city
     * 
     * @param string $testString [description]
     * 
     * @return boolean             [description]
     */
    public function isCityString(string $testString)
    {
        $l = trim($testString);
        if (strlen($l)<2) {
            return false;
        }

        //check if the location end with 2 character with out ,
        //Oyster Bay NY =Oyster Bay, NY

        if (!strpos($l, ',')) {
            $locationarray=explode(" ", $l);
            if (count($locationarray)>1) {
                $lastelement=end($locationarray);
                $newlocation='';
                if (strlen($lastelement)==2) {
                    foreach ($locationarray as $value) {
                        if ($value==$lastelement) {
                            $newlocation.=", ".$value;
                        } else {
                            $newlocation.=" ".$value;
                        }
                    }
                    $l=trim($newlocation);
                }
            }
        }

        $pattern = "/^[a-z][a-z\_\\s\\,\\.\\-\\/]*[a-z]$/i";
        $checkChar = preg_match($pattern, $l, $match);

        if (!$checkChar) {
            return false;
        }

        // To fix problem location conrines only numbers
        $_pattern = "#\\D#";
        $_checkChar = preg_match($_pattern, $l, $_match);

        //CVarDumper::dump($_checkChar,11,11); die;
        if (!$_checkChar) {
            return false;
        }

        $l = trim($l, ',');
        
        if (strlen($l)<2) {
            return false;
        }

        $l = preg_replace('#,\s*#i', ', ', $l);

        $CommaCount = substr_count($l, ",");
        if ($CommaCount < 3 && $CommaCount > 0) {// modified by Osama ..
            $l = str_ireplace([", United States",", us",", usa"], "", $l);

            $cityState = preg_replace("#\d|\-#", "", $l);
            $cit = explode(',', $cityState);
            if (isset($cit[1])) {
                $rState = trim(end($cit));
            }
            if ($_state = $rState) {
                if (strlen($_state)<2) {
                    return false;
                }
            }
        }
        return true;
    }
}
