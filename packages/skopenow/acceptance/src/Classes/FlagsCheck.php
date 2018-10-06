<?php

/**
 * FlagsCheck
 *
 * PHP version 7.0
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */

namespace Skopenow\Acceptance\Classes;

/**
 * FlagsCheck
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class FlagsCheck
{
    /**
     * [$scoringFlags description]
     *
     * @var array
     */
    protected $scoringFlags;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        require __DIR__.'/../Config/acceptance.php';
        $this->scoringFlags = loadData("scoringFlags");
    }

    public function isLocationFound($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["loc_not_found"]["value"];
        $value = $this->scoringFlags["loc_not_found"]["value"];
        if ($scoreValue == $value) {
            return false;
        }
        return true;
    }

    public function isNameFound($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["name_not_found"]["value"];
        $value = $this->scoringFlags["name_not_found"]["value"];
        if ($scoreValue == $value) {
            return false;
        }
        return true;
    }

    /**
     * [isNotMatchDataPoint description]
     *
     * @param boolean $isDataPoint [description]
     *
     * @return boolean              [description]
     */
    public function isNotMatchDataPoint($isDataPoint, $flags)
    {
        if (!$isDataPoint) {
            return false;
        }

        //check match Company
        $scoreValue = $flags&$this->scoringFlags["cm"]["value"];
        if ($scoreValue == $this->scoringFlags["cm"]["value"]) {
            return false;
        }

        //check match school
        $scoreValue = $flags&$this->scoringFlags["sc"]["value"];
        if ($scoreValue == $this->scoringFlags["sc"]["value"]) {
            return false;
        }
        return true;
    }

    /**
     * [isMatchName description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchName($flags)
    {
        $name = $this->scoringFlags["fn"]["value"]
        |$this->scoringFlags["ln"]["value"];
        $scoreValue = $flags&$name;

        if ($scoreValue == $name) {
            return true;
        }
        return false;
    }

    /**
     * [isUniqueName description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isUniqueName($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["unq_name"]["value"];
        $value = $this->scoringFlags["unq_name"]["value"];
        if ($scoreValue == $value) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchLocation description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchLocation($flags)
    {
        $pct = $flags&$this->scoringFlags["pct"]["value"];
        $st = $flags&$this->scoringFlags["st"]["value"];

        if ($this->isSmallCity($flags)) {
            return true;

        } elseif ($this->isBigCity($flags)) {
            return true;

        } elseif ($pct == $this->scoringFlags["pct"]["value"]) { //PartialCity
            return true;

        } elseif ($st == $this->scoringFlags["st"]["value"]) { //State
            return true;
        }

        return false;
    }

    /**
     * [isSmallCity description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isSmallCity($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["exct-sm"]["value"];
        if ($scoreValue == $this->scoringFlags["exct-sm"]["value"]) {    //small city
            return true;
        }
        return false;
    }
    
    /**
     * [isBigCity description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isBigCity($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["exct-bg"]["value"];
        if ($scoreValue == $this->scoringFlags["exct-bg"]["value"]) {    //big city
            return true;
        }
        return false;
    }

    /**
     * [isDistanceInRang description]
     *
     * @param integer $flags    [description]
     * @param float   $distance [description]
     *
     * @return boolean           [description]
     */
    public function isDistanceInRang($flags, $distance)
    {
        if (is_null($distance)) {
            return false;
        }

        if ($distance < config("acceptance.acceptDistance")) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchPhone description]
     *
     * @param intger $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchPhone($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["ph"]["value"];
        if ($scoreValue == $this->scoringFlags["ph"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchEmail description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchEmail($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["em"]["value"];
        if ($scoreValue == $this->scoringFlags["em"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchDataPoint description]
     *
     * @param boolean $isDataPoint [description]
     *
     * @return boolean              [description]
     */
    public function isMatchDataPoint($isDataPoint, $flags)
    {
        if (is_null($isDataPoint)) {
            return false;
        }

        //check match Company
        $scoreValue = $flags&$this->scoringFlags["cm"]["value"];
        if ($scoreValue == $this->scoringFlags["cm"]["value"]) {
            return true;
        }

        //check match school
        $scoreValue = $flags&$this->scoringFlags["sc"]["value"];
        if ($scoreValue == $this->scoringFlags["sc"]["value"]) {
            return true;
        }

        return false;
    }

    /**
     * [isMatchMiddleName description]
     *
     * @param integer $flags [description]
     *
     * @return boolean              [description]
     */
    public function isMatchMiddleName($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["mn"]["value"];
        if ($scoreValue == $this->scoringFlags["mn"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isOnlyOne has friend]
     *
     * @param integer $flags [description]
     *
     * @return boolean              [description]
     */
    public function relative($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["rltv"]["value"];
        if ($scoreValue == $this->scoringFlags["rltv"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isOnlyOne description]
     *
     * @param integer $flags [description]
     *
     * @return boolean              [description]
     */
    public function isOnlyOne($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["onlyOne"]["value"];
        if ($scoreValue == $this->scoringFlags["onlyOne"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isInputPhone description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isInputPhone($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["input_ph"]["value"];
        if ($scoreValue == $this->scoringFlags["input_ph"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isInputEmail description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isInputEmail($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["input_em"]["value"];
        if ($scoreValue == $this->scoringFlags["input_em"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isInputName description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isInputName($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["input_name"]["value"];
        if ($scoreValue == $this->scoringFlags["input_name"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isRelative this account is relative]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isRelative($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["is_relative"]["value"];
        if ($scoreValue == $this->scoringFlags["is_relative"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isVerifiedUsername description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isVerifiedUsername($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["verified_un"]["value"];
        if ($scoreValue == $this->scoringFlags["verified_un"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchAge description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchAge($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["age"]["value"];
        if ($scoreValue == $this->scoringFlags["age"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchState description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchState($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["st"]["value"];
        if ($scoreValue == $this->scoringFlags["st"]["value"]) {
            return true;
        }
        return false;
    }

    /**
     * [isMatchPartialCity description]
     *
     * @param integer $flags [description]
     *
     * @return boolean        [description]
     */
    public function isMatchPartialCity($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["pct"]["value"];
        if ($scoreValue == $this->scoringFlags["pct"]["value"]) {
            return true;
        }
        return false;
    }

    public function isRelativeMain($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["rltvWithMain"]["value"];
        if ($scoreValue == $this->scoringFlags["rltvWithMain"]["value"]) {
            return true;
        }
        return false;
    }

    public function isMatchInputUserName($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["input_un"]["value"];
        if ($scoreValue == $this->scoringFlags["input_un"]["value"]) {
            return true;
        }
        return false;
    }

    public function isMatchUserName($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["un"]["value"];
        if ($scoreValue == $this->scoringFlags["un"]["value"]) {
            return true;
        }
        return false;
    }

    public function isPeopleUsername($flags)
    {
        $scoreValue = $flags&$this->scoringFlags["people_un"]["value"];
        if ($scoreValue == $this->scoringFlags["people_un"]["value"]) {
            return true;
        }
        return false;
    }
}
