<?php

/**
 * Acceptance
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
 * Acceptance
 *
 * @category Class
 * @package  Acceptance
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class Acceptance
{
    /**
     * [$flagsCheck description]
     *
     * @var [type]
     */
    protected $flagsCheck;

    /**
     * [$bannedSource description]
     *
     * @var BannedInterface
     */
    protected $bannedSource;

    protected $reportObj;

    public $isdataPoint = null;
    protected $nameFound = false;
    protected $locationFound = false;
    protected $usernameFound = false;

    /**
     * [__construct description]
     *
     * @param [type] $flagsCheck [description]
     */
    public function __construct($flagsCheck, $banned, $reportObj)
    {
        $this->flagsCheck = $flagsCheck;
        $this->bannedSource = $banned;
        $this->reportObj = $reportObj;
    }

    /**
     * [checkAccptance description]
     *
     * @param  ResultData $resultData [description]
     * @param  int $flags      [description]
     *
     * @return array             [description]
     */
    public function checkAccptance($resultData, $flags)
    {
        $acceptance["acceptance"] = false;
        $acceptance["reason"] = 0;

        if (empty($resultData->url)) {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] = RejectedReasons::EMPTY_URL;
            return $acceptance;
        }

        if ($resultData->getIsManual() == 1 || $resultData->getIsInput() == 1) {
            $acceptance["acceptance"] = true;
            return $acceptance;
        }
        if ($this->isBanned($resultData->url)) {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] = RejectedReasons::BANNED_DOMAIN;
            return $acceptance;
        }

        if ($resultData->getIsProfile()) {
            return $this->isProfileAcceptance($resultData, $flags);
        }

        return $this->isAllowedUrl($resultData);
    }

    /**
     * [isAllowedUrl description]
     *
     * @param  Result Data  $resultData [description]
     *
     * @return array             [description]
     */
    protected function isAllowedUrl($resultData)
    {
        $acceptance["acceptance"] = true;
        $acceptance["reason"] = 0;

        // if (stripos($resultData->source, "(add_url)") == false) {
        //     if ($this->isBanned($resultData->url)) {
        //         $acceptance["acceptance"] = false;
        //         $acceptance["reason"] = RejectedReasons::BANNED_DOMAIN;
        //     }
        // }

        $pattern = "/:\\/\\/([^.]+).wikipedia.org/";
        if (preg_match($pattern, $resultData->url, $match)) {
            $lan=$match[1];       // ignore if not english
            if ($lan!='en') {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] = RejectedReasons::WIKIPEDIA_NOT_EN;
            }
        }
        return $acceptance;
    }

    public function isBanned($url)
    {
        $panned = $this->bannedSource->getBannedDomains();
        foreach ($panned as $pn) {
            if (stripos($url, $pn->domain) !== false) {
                return true;
                break;
            }
        }

        $userBannedSources = $this->bannedSource->getUserBanned();
        foreach ($userBannedSources as $source) {
            if (stripos($url, $source->url) !== false) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * [isProfileAcceptance description]
     *
     * @param  ResultData  $resultData [description]
     * @param  Integer  $flags      [description]
     *
     * @return boolean             [description]
     */
    protected function isProfileAcceptance($resultData, $flags)
    {
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
            case $this->flagsCheck->isOnlyOne($flags):
                $acceptance = $this->checkOnlyOneCase($flags);
                break;

            case $this->flagsCheck->isNotMatchDataPoint($this->isdataPoint, $flags):
                $acceptance["acceptance"] = false;
                $acceptance["reason"] = RejectedReasons::DOES_NOT_MATCH_DATAPOINT;
                break;

            case $this->nameFound && !$this->locationFound:
                $acceptance = $this->checkNotFoundLocationCases($flags);
                break;

            case $this->nameFound:
                $acceptance = $this->checkFoundNameCases($flags);
                break;

            case !$this->nameFound:
                $acceptance = $this->checkNotFoundNameCases($flags);
                break;
            
            /*default:
                $acceptance["acceptance"] = false;
                break;*/
        }

        return $acceptance;
    }

    /**
     * [checkOnlyOneCase description]
     *
     * @param int $flags [description]
     *
     * @return array        [description]
     */
    public function checkOnlyOneCase($flags)
    {
        $acceptance["acceptance"] = false;
        $acceptance["reason"] = RejectedReasons::IS_ONLY_ONE;

        if ($this->flagsCheck->isMatchInputUserName($flags)) {
            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;
        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTUSERNAME;
        }

        if ($this->flagsCheck->isInputPhone($flags)) {
            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;
        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTPHONE;
        }

        if ($this->flagsCheck->isInputEmail($flags)) {
            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;
        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTEMAIL;
        }

        if ($this->flagsCheck->isInputName($flags)) {
            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;
        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTNAME;
        }

        return $acceptance;
    }

    /**
     * [checkFoundNameCases description]
     *
     * @param integer $flags       [description]
     * @param boolean $this->isdataPoint [description]
     *
     * @return boolean             [description]
     */
    protected function checkFoundNameCases($flags)
    {
        $acceptance["acceptance"] = false;
        $acceptance["reason"] = 0;

        if (!$this->flagsCheck->isMatchLocation($flags)) {
            $acceptance["reason"] = RejectedReasons::DOES_NOT_MATCH_LOCATION;

            if ($this->flagsCheck->isMatchName($flags)
                && $this->flagsCheck->isMatchMiddleName($flags)
            ) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;
            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_NAME;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_MIDDELNAME;
                return $acceptance;
            }
        }

        if (!$this->flagsCheck->isMatchName($flags)) {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] = RejectedReasons::DOES_NOT_MATCH_NAME;

            if ($this->flagsCheck->isInputPhone($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTPHONE;
            }

            if ($this->flagsCheck->isInputEmail($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTEMAIL;
            }

        } elseif ($this->flagsCheck->isMatchName($flags)) {

            if (!$this->flagsCheck->relative($flags)) {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_RELATIVE;
                
            } else {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;
            }

            if ($this->flagsCheck->isMatchLocation($flags)
                && $this->usernameFound
                && $this->flagsCheck->isMatchUserName($flags)
            ) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;
                
            } else {
                $acceptance["acceptance"] = false;
                if (!$this->flagsCheck->isMatchLocation($flags)) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_LOCATION;
                }
                if (!$this->flagsCheck->isMatchUserName($flags)) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_USERNAME;
                }
            }

            if ($this->flagsCheck->isVerifiedUsername($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_VERIFIEDUSERNAME;
            }

            if ($this->flagsCheck->isMatchInputUserName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTUSERNAME;
            }

            if ($this->flagsCheck->isMatchPhone($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_PHONE;
            }

            if ($this->flagsCheck->isMatchEmail($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_EMAIL;
            }

            if ($this->flagsCheck->isMatchDataPoint($this->isdataPoint, $flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_DATAPOINT;
            }

            if ($this->flagsCheck->isMatchMiddleName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_MIDDELNAME;
            }

            if ($this->flagsCheck->isUniqueName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } elseif (!$this->flagsCheck->isUniqueName($flags)) {
                $acceptance["reason"] = RejectedReasons::DOES_NOT_MATCH_UNIQUENAME;

                if ($this->flagsCheck->isSmallCity($flags)) {
                    $acceptance["acceptance"] = true;
                    $acceptance["reason"] = 0;
                    return $acceptance;

                } else {
                    $acceptance["acceptance"] = false;
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_SMALLCITY;
                }

                if ($this->flagsCheck->isBigCity($flags)) {
                    $acceptance["acceptance"] = true;
                    $acceptance["reason"] = 0;
                    return $acceptance;

                } else {
                    $acceptance["acceptance"] = false;
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_BIGCITY;
                }

                if ($this->flagsCheck->isMatchPartialCity($flags)) {
                    $acceptance["acceptance"] = true;
                    $acceptance["reason"] = 0;
                    return $acceptance;

                } else {
                    $acceptance["acceptance"] = false;
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_PARTIALCITY;
                }

                if ($this->flagsCheck->isMatchState($flags)) {
                    $acceptance["acceptance"] = true;
                    $acceptance["reason"] = 0;
                    return $acceptance;

                } else {
                    $acceptance["acceptance"] = false;
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_STATE;
                }
            }

        }
        
        return $acceptance;
    }

    /**
     * [checkNotFoundNameCases description]
     *
     * @param integer $flags         [description]
     *
     * @return boolean
     */
    protected function checkNotFoundNameCases($flags)
    {
        $acceptance["acceptance"] = false;
        $acceptance["reason"] = RejectedReasons::DOES_NOT_FOUND_NAME;

        if ($this->flagsCheck->isMatchInputUserName($flags)) {
            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;

        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTUSERNAME;
        }

        if ($this->usernameFound) {
            if ($this->flagsCheck->isVerifiedUsername($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_VERIFIEDUSERNAME;
            }

            if ($this->flagsCheck->isMatchUserName($flags)
                && ($this->flagsCheck->isMatchLocation($flags)
                    || !$this->locationFound)
                ) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                if (!$this->locationFound) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_FOUND_LOCATION;
                } elseif (!$this->flagsCheck->isMatchLocation($flags)) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_LOCATION;
                }
                if ($this->flagsCheck->isMatchUserName($flags)) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_USERNAME;
                }
            }
        } else {
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_FOUND_USERNAME;

            if ($this->flagsCheck->isUniqueName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_UNIQUENAME;
            }
        }
        
        return $acceptance;
    }

    /**
     * [checkNotFoundLocationCases description]
     *
     * @param integer $flags [description]
     *
     * @return array        [description]
     */
    protected function checkNotFoundLocationCases($flags)
    {
        $acceptance["acceptance"] = false;
        $acceptance["reason"] = RejectedReasons::DOES_NOT_FOUND_LOCATION;

        if ($this->flagsCheck->isMatchName($flags)) {
            if (!$this->flagsCheck->relative($flags)) {
                //not match relative
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_RELATIVE;
                
            } else {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;
            }

            if ($this->usernameFound && $this->flagsCheck->isMatchUserName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                if ($this->usernameFound) {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_USERNAME;
                } else {
                    $acceptance["reason"] |= RejectedReasons::DOES_NOT_FOUND_USERNAME;
                }
            }

            if ($this->flagsCheck->isMatchInputUserName($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_INPUTUSERNAME;
            }

            if ($this->flagsCheck->isVerifiedUsername($flags)) {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;

            } else {
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_VERIFIEDUSERNAME;
            }

            if (!$this->flagsCheck->isMatchAge($flags)) {
                //not match age
                $acceptance["acceptance"] = false;
                $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_AGE;
                
            } else {
                $acceptance["acceptance"] = true;
                $acceptance["reason"] = 0;
                return $acceptance;
            }

            $acceptance["acceptance"] = true;
            $acceptance["reason"] = 0;
            return $acceptance;

        } else {
            //not found location
            $acceptance["acceptance"] = false;
            $acceptance["reason"] |= RejectedReasons::DOES_NOT_MATCH_NAME;
            
        }

        return $acceptance;
    }
}
