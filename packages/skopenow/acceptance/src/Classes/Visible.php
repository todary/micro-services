<?php

/**
 * Visible
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

use App\Constants\RejectedReasons;

/**
 * Visible
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class Visible
{
    /**
     * [$flagsCheck description]
     *
     * @var [type]
     */
    protected $flagsCheck;

    protected $reportObj;

    protected $isdataPoint = null;
    protected $nameFound = false;
    protected $locationFound = false;
    protected $usernameFound = false;

    /**
     * [__construct description]
     *
     * @param Object $flagsCheck [description]
     */
    public function __construct($flagsCheck, $reportObj)
    {
        $this->flagsCheck = $flagsCheck;
        $this->reportObj = $reportObj;
    }

    /**
     * [checkVisible description]
     *
     * @param boolean $flags         [description]
     * @param boolean $this->nameFound     [description]
     * @param boolean $this->locationFound [description]
     * @param boolean $thIs->IsdataPoint   [description]
     * @param boolean $this->usernameFound [description]
     *
     * @return boolean               [description]
     */
    public function checkVisible(
        $resultData,
        $flags
    ) {
        $visible["visible"] = false;
        $visible["reason"] = 0;

        if (!$resultData->getIsProfile()) {
            $visible["visible"] = true;
            $visible["reason"] = 0;
            return $visible;
        }
        

        if ($this->flagsCheck->isNameFound($flags)) {
            $this->nameFound = true;
        }
        
        if ($this->flagsCheck->isLocationFound($flags)) {
            $this->locationFound = true;
        }
        
        if ($resultData->getEducations()->count()
            || $resultData->getExperiences()->count()
        ) {
            $report_id = config("state.report_id");
            $types = ["work", "school"];

            $this->isdataPoint = false;
            if ($this->reportObj->isVerifiedDataPoints($report_id, $types)) {
                $this->isdataPoint = true;
            }
        }

        if (!is_null($resultData->getUsername())) {
            $this->usernameFound = true;
        }


        switch (true) {
            /*case $this->flagsCheck->isOnlyOne($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;*/

            case $this->nameFound && !$this->locationFound:
                $visible = $this->checkNotFoundLocationCases($flags);
                break;

            case $this->nameFound:
                $visible = $this->checkFoundNameCases($flags);
                break;

            case !$this->nameFound:
                $visible = $this->checkNotFoundNameCases($flags);
                break;
            
            /*default:
                $visible = false;
                break;*/
        }

        return $visible;
    }

    /**
     * [checkFoundNameCases description]
     *
     * @param Integer $flags       [description]
     * @param boolean $thIs->IsdataPoint [description]
     *
     * @return boolean             [description]
     */
    protected function checkFoundNameCases($flags)
    {
        switch (true) {
            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && $this->flagsCheck->isOnlyOne($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && $this->flagsCheck->isRelativeMain($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && !$this->flagsCheck->isOnlyOne($flags):
                $visible["visible"] = false;
                $visible["reason"] = RejectedReasons::MATCHNAME_RELATIVE_NOT_ONLY;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->usernameFound
                && $this->flagsCheck->isMatchUserName($flags)
                && ($this->flagsCheck->isMatchLocation($flags) || !$this->locationFound):

                if ($this->flagsCheck->isPeopleUsername($flags)) {
                    $visible["visible"] = true;
                    $visible["reason"] = 0;
                    break;
                } else {
                    if ($this->flagsCheck->isUniqueName($flags)) {
                        $visible["visible"] = true;
                        $visible["reason"] = 0;
                        break;
                    } else {
                        $visible["visible"] = false;
                        $visible["reason"] = RejectedReasons::DOES_NOT_MATCH_UNIQUENAME;
                        $visible["reason"] |= RejectedReasons::DOES_NOT_MATCH_PEOPLEUENAME ;
                        break;
                    }
                }
                
            case !$this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isInputPhone($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case !$this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isInputEmail($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isVerifiedUsername($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isUniqueName($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case !$this->flagsCheck->isUniqueName($flags) //commenName
                && $this->flagsCheck->isSmallCity($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case !$this->flagsCheck->isUniqueName($flags) //commenName
                && $this->flagsCheck->isBigCity($flags):
                $visible["visible"] = false;
                $visible["reason"] = RejectedReasons::DOES_NOT_MATCH_UNIQUENAME;
                $visible["reason"] |= RejectedReasons::DOES_NOT_MATCH_BIGCITY;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isMatchPhone($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isMatchEmail($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isMatchDataPoint($this->isdataPoint, $flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            default:
                $visible["visible"] = false;
                $visible["reason"] = 0;
                break;
        }

        return $visible;
    }

    /**
     * [checkNotFoundNameCases description]
     *
     * @param integer $flags         [description]
     * @param boolean $this->usernameFound [description]
     *
     * @return boolean               [description]
     */
    protected function checkNotFoundNameCases($flags)
    {
        $visible["reason"] = RejectedReasons::DOES_NOT_FOUND_NAME;
        switch (true) {
            case $this->usernameFound
              && $this->flagsCheck->isMatchUserName($flags):
                $visible["visible"] = false;
                $visible["reason"] = RejectedReasons::DOES_NOT_FOUND_NAME;
                break;

            case $this->flagsCheck->isVerifiedUsername($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->usernameFound
              && $this->flagsCheck->isPeopleUsername($flags)
              && ($this->flagsCheck->isMatchLocation($flags)
                || !$this->locationFound):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;
            
            case $this->flagsCheck->isUniqueName($flags):
                $visible["visible"] = false;
                $visible["reason"] |= RejectedReasons::IS_UNIQUENAME;
                break;

            default:
                $visible["visible"] = false;
                $visible["reason"] |= RejectedReasons::DOES_NOT_MATCH_VERIFIEDUSERNAME;
                break;
        }

        return $visible;
    }

    /**
     * [checkNotFoundLocationCases description]
     *
     * @param boolean $flags [description]
     *
     * @return [type]        [description]
     */
    protected function checkNotFoundLocationCases($flags)
    {
        switch (true) {
            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && $this->flagsCheck->isOnlyOne($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && $this->flagsCheck->isRelativeMain($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
                && $this->usernameFound
                && $this->flagsCheck->isMatchUserName($flags)
                && ($this->flagsCheck->isMatchLocation($flags) || !$this->locationFound):

                if ($this->flagsCheck->isPeopleUsername($flags)) {
                    $visible["visible"] = true;
                    $visible["reason"] = 0;
                    break;
                } else {
                    if ($this->flagsCheck->isUniqueName($flags)) {
                        $visible["visible"] = true;
                        $visible["reason"] = 0;
                        break;
                    } else {
                        $visible["visible"] = false;
                        $visible["reason"] = RejectedReasons::DOES_NOT_MATCH_UNIQUENAME;
                        $visible["reason"] |= RejectedReasons::DOES_NOT_MATCH_PEOPLEUENAME ;
                        break;
                    }
                }

            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->relative($flags)
              && !$this->flagsCheck->isOnlyOne($flags):
                $visible["visible"] = false;
                $visible["reason"] = RejectedReasons::MATCHNAME_RELATIVE_NOT_ONLY;
                break;

            case $this->usernameFound
              && $this->flagsCheck->isMatchUserName($flags)
              && ($this->flagsCheck->isMatchName($flags)
                || !$this->nameFound):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            case $this->flagsCheck->isMatchName($flags)
              && $this->flagsCheck->isMatchAge($flags):
                $visible["visible"] = true;
                $visible["reason"] = 0;
                break;

            default:
                $visible["visible"] = false;
                $visible["reason"] = 0;
                break;
        }

        return $visible;
    }
}
