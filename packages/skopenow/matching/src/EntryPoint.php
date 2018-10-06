<?php

namespace Skopenow\Matching;

use Skopenow\Matching\Check\F6sCheck;
use Skopenow\Matching\Check\AngelCheck;
use Skopenow\Matching\Check\FacebookCheck;
use Skopenow\Matching\Check\FlickrCheck;
use Skopenow\Matching\Check\FoursquareCheck;
use Skopenow\Matching\Check\GoogleplusCheck;
use Skopenow\Matching\Check\InstagramCheck;
use Skopenow\Matching\Check\LinkedinCheck;
use Skopenow\Matching\Check\MyspaceCheck;
use Skopenow\Matching\Check\PicasaCheck;
use Skopenow\Matching\Check\PinterestCheck;
use Skopenow\Matching\Check\SlideshareCheck;
use Skopenow\Matching\Check\SoundcloudCheck;
use Skopenow\Matching\Check\TwitterCheck;
use Skopenow\Matching\Check\YoutubeCheck;
use Skopenow\Matching\Check\URLCheck;
use Skopenow\Matching\Check\Check;

use Skopenow\Matching\Services\ReportService;
use Skopenow\Matching\Analyzer\LocationAnalyzer;
use Skopenow\Matching\Match\NameMatch;
use Skopenow\Matching\Match\LcationMatch;
use Skopenow\Matching\Match\SchoolMatch;
use Skopenow\Matching\Match\WorkMatch;

/**
 * Class EntryPoint
 * The Entry point for the Matching Package
 *
 * @package Skopenow\Matching
 */
class EntryPoint
{

    protected $resultSource = null;

    public function __construct()
    {
        $this->reportService = new ReportService;
        $this->report = $this->reportService->getReport();
    }

    /**
     * Match ProfileInfo with existing optional data
     * if matchWith is empty then match with Report data
     * @param  array  $profileInfo array of profile info data to match with
     * @param  array  $matchWith   array of profile info data to match with (optional)
     * @return array               Status array (check Status class)
     */
    public function check(
        array $profileInfo,
        array $matchWith = [],
        bool $is_relative = false,
        bool $disableMiddleNameCriteria = false
    ) : array
    {
        $check = new Check($this->reportService);
        $check->disableMiddleNameCriteria($disableMiddleNameCriteria);
        $check->setProfileInfo($profileInfo);
        $check->setMatchWith($matchWith);
        $check->setResultSource($this->resultSource);
        $check->setIsRelative($is_relative);
        return $check->check();
    }

    public function setResultSource($source)
    {
        $this->resultSource = $source;
    }


    /**
     * check F6s Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkF6s(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'f6s');
        }
        $reportID = config('state.report_id');
        $check = new F6sCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check Angel Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkAngel(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'angel');
        }
        $reportID = config('state.report_id');
        $check = new AngelCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check Flickr Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkFlickr(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'flickr');
        }
        $reportID = config('state.report_id');
        $check = new FlickrCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check foursquare Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkFoursquare(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'foursquare');
        }
        $reportID = config('state.report_id');
        $check = new FoursquareCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check googleplus Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkGoogleplus(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'googleplus');
        }
        $reportID = config('state.report_id');
        $check = new GoogleplusCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check instagram Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkInstagram(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'instagram');
        }
        $reportID = config('state.report_id');
        $check = new InstagramCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check Instagram Profile Info with input and combination data
     * @param  string  $url         profile URL
     * @param  Report  $report      Person data
     * @param  array   $combination  combination data
     * @param  array   $extraData            extra data to pass
     * @param  boolean $checkLocationByforce
     * @param  array   $htmlContent     html content of profile
     * @param  integer $resultsCount
     * @return array               array of data (see Status Class)
     */
    public function checkLinkedin(
        string $url,
        array $profileInfo,
        $combination,
        $extraData = [],
        $checkLocationByforce = false,
        $htmlContent = [],
        $resultsCount = 0
    ) : array {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'linkedin');
        }
        $check = new LinkedinCheck($url, $profileInfo, $combination, $this->report);
        $check->setExtraData($extraData);
        $check->setCheckLocationByforce($checkLocationByforce);
        $check->setResultsCount($resultsCount);
        $check->setHtmlContent($htmlContent);
        return $check->check();
    }

    /**
     * check Instagram Profile Info with input and combination data
     * @param  string  $url         profile URL
     * @param  Report  $report      Person data
     * @param  array   $combination  combination data
     * @param  boolean $isRelative
     * @param  string  $location                  other location
     * @param  boolean $disable_location_check
     * @param  boolean $NameExact
     * @param  array   $htmlContent               html content of profile
     * @param  boolean $disableMiddlenameCriteria
     * @return array                            array of data (see Status Class)
     */
    public function checkFacebook(
        string $url,
        array $profileInfo,
        array $combination,
        $isRelative = true,
        $location = '',
        $disable_location_check = false,
        $NameExact = false,
        array $htmlContent = [],
        $disableMiddlenameCriteria = false
    ) : array {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'facebook', $htmlContent);
        }
        $check = new FacebookCheck($url, $profileInfo, $combination, $this->report);
        $this->setIsRelative($isRelative);
        $this->setAdditionalLocation($location);
        $this->disableLocationCheck($disable_location_check);
        $this->setNameExact($NameExact);
        $this->disableMiddlenameCriteria($disableMiddlenameCriteria);
        $check->setHtmlContent($htmlContent);
        return $check->check();
    }

    /**
     * check myspace Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkMyspace(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'myspace');
        }
        $reportID = config('state.report_id');
        $check = new MyspaceCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check Picasa Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkPicasa(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'picasa');
        }
        $reportID = config('state.report_id');
        $check = new PicasaCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check pinterest Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkPinterest(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'pinterest');
        }
        $reportID = config('state.report_id');
        $check = new PinterestCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check slideshare Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkSlideshare(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'slideshare');
        }
        $reportID = config('state.report_id');
        $check = new SlideshareCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check soundcloud Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkSoundcloud(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'soundcloud');
        }
        $reportID = config('state.report_id');
        $check = new SoundcloudCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check twitter Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkTwitter(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'twitter');
        }
        $reportID = config('state.report_id');
        $check = new TwitterCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check youtube Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @return array               array of data (see Status Class)
     */
    public function checkYoutube(string $url, array $profileInfo, $combination) : array
    {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, 'twitter');
        }
        $reportID = config('state.report_id');
        $check = new YoutubeCheck($url, $profileInfo, $combination, $this->report);
        return $check->check();
    }

    /**
     * check URL Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @param  string $source      source name
     * @param  array  $htmlContent  htmlContent of the profile
     * @return array               array of data (see Status Class)
     */
    public function checkURL(
        string $url,
        array $profileInfo,
        $combination,
        string $source,
        array $htmlContent
    ) : array {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $profileInfo = $entry->getProfileInfo($url, $source, $htmlContent);
        }
        $reportID = config('state.report_id');
        $check = new URLCheck($url, $profileInfo, $combination, $this->report);
        $check->setSource($source);
        return $check->check();
    }

    /**
     * check URL Profile Info with input and combination data
     * @param  string $url         profile URL
     * @param  array $profileInfo  array of profile info
     * @param  array $combination  combination data
     * @param  string $source      source name
     * @param  array  $htmlContent  htmlContent of the profile
     * @return array               array of data (see Status Class)
     */
    public function checkRelatedProfiles(
        string $url,
        array $profileInfo,
        $combination,
        array $links,
        array $htmlContent
    ) : array {
        if (empty($profileInfo)) {
            $entry = loadService('urlInfo');
            $source = $entry->determineSource($url);
            $profileInfo = $entry->getProfileInfo($url, $source[0], $htmlContent);
        }
        $reportID = config('state.report_id');
        $check = new RelatedProfilesCheck($url, $profileInfo, $combination, $this->report);
        $check->setLinks($links);
        return $check->check();
    }

    /**
     * Match Name
     * Return true if the name match with minimum percentage
     * @param  string      $fn1            Firstname
     * @param  string      $md1            middlename
     * @param  string      $ln1            lastname
     * @param  string      $fn2            firstname
     * @param  string      $md2            middlename
     * @param  string      $ln2            lastname
     * @param  int|integer $minimumPercent Minimum Percent tp match
     * @return bool                        match?
     */
    public function matchName(
        string $fn1,
        string $md1,
        string $ln1,
        string $fn2,
        string $md2,
        string $ln2,
        int $minimumPercent = 90
    ) : bool {
        $match = new NameMatch();
        $match->setFirstName1($fn1);
        $match->setFirstName2($fn2);
        $match->setMiddleName1($md1);
        $match->setMiddleName2($md2);
        $match->setLastName1($ln1);
        $match->setLastName2($ln2);
        $match->setMinimumPercent($minimumPercent);
        return $match->match();
    }

    /**
     * Match Location
     * return true if Location Match
     * @param  string       $city1            City1
     * @param  string       $state1           State1
     * @param  string       $city2            City2
     * @param  string       $state2           State2
     * @param  array        &$locationDetails LocationDetails by refence
     * @param  bool|boolean $preventAliasing  Prevent Aliasing
     * @param  bool|boolean $cityOrstate      match City and/or State
     * @param  bool|boolean $onlyState        match Only State
     * @return bool                           true if matched
     */
    public function matchLocation(
        string $city1,
        string $state1,
        string $city2,
        string $state2,
        array &$locationDetails = [],
        bool $preventAliasing = false,
        bool $cityOrstate = false,
        bool $onlyState = false
    ) : bool {
        $match = new LocationMatch($this->report, $combination);
        $match->setCity1($city1);
        $match->setCity2($city2);
        $match->setState1($state1);
        $match->setState2($state2);
        $match->setLocationDetails($locationDetails);
        $match->preventAliasing($preventAliasing);
        $match->setCityOrState($cityOrstate);
        $match->setOnlyState($onlyState);
        return $match->match();
    }

    /**
     * Match School
     * return true if schools matched
     * @param  array/string $school1
     * @param  array/string $school2
     * @return bool            true if matche
     */
    public function matchSchool($schools1, $schools2) : bool
    {
        if (!is_array($schools1)) {
            $schools1 = [$schools1];
        }
        if (!is_array($schools2)) {
            $schools2 = [$schools2];
        }
        $match = new SchoolMatch;
        foreach ($schools1 as $school1) {
            foreach ($schools2 as $school2) {
                $match->setSchool1($school1);
                $match->setSchool2($school2);
                if ($match->match() === true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * [matchWork description]
     * @param  array/string $workExps1      [description]
     * @param  array/string $workExps2      [description]
     * @param  bool   $extractCompany [description]
     * @param  array  $params         [description]
     * @return bool                 [description]
     */
    public function matchWork(
        $workExps1,
        $workExps2,
        bool $extractCompany = false,
        array $params = []
    ) : bool {
        if (!is_array($workExps1)) {
            $workExps1 = [$workExps1];
        }
        if (!is_array($workExps2)) {
            $workExps2 = [$workExps2];
        }
        $match = new WorkMatch;
        $match->setParams($params);
        $match->setExtractCompany($extractCompany);
        foreach ($workExps1 as $work1) {
            foreach ($workExps2 as $work2) {
                $match->setWork1($work1);
                $match->setWork2($work2);
                if ($match->match() === true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if Location belongs to bigcity and return true
     * @param  string  $location [description]
     * @param  [type]  $city     [description]
     * @param  [type]  $state    [description]
     * @return boolean
     */
    public function isBigCity(string $location, $city = null, $state = null) : bool
    {
        $analyzer = new LocationAnalyzer($this->reportService);
        return $analyzer->isBigCity($location, $city, $state);
    }
}
