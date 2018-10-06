<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\{Status, RunAnalyzer};
use Skopenow\Matching\Match\NameMatch;
use Skopenow\Matching\Analyzer\{LocationAnalyzer, SchoolAnalyzer, WorkAnalyzer};


class LinkedinCheck implements CheckInterface
{
    private $info;
    private $entry;
    private $data;
    private $url;
    private $person;
    private $combination;
    private $htmlContent = [];
    private $extraData = [];
    private $checkLocationByforce = false;
    private $resultsCount = 0;

    public function __construct(
        string $url,
        array $info,
        $combination,
        ReportService $report
    )
    {
        $this->entry = loadService('urlInfo');
        $status = new Status;
        $this->data = $status->matchingData;
        $this->combination = $combination;
        $this->person = $report->getReport();
        $this->url = $url;
        $this->info = $info;
        $this->report = $report;
    }

    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
    }

    public function setCheckLocationByforce($checkLocationByforce)
    {
        $this->checkLocationByforce = $checkLocationByforce;
    }

    public function setResultsCount($resultsCount)
    {
        $this->resultsCount = $resultsCount;
    }

    public function setHtmlContent($html)
    {
        $this->htmlContent = $html;
    }

    public function check()
    {
        $ResultsCount = $this->resultsCount;
        $profile_url = $this->url;
        $person = $this->person;
        $combination = $this->combination;
        $extraData = $this->extraData;
        $this->data['name']['status'] = true;
        $this->data['location']['status'] = true;
        $this->data['work']['status'] = true;
        $this->data['school']['status'] = true;
        $this->data['username']['status'] = true;

        $analyzer = new RunAnalyzer($this->report);
        $combination['urlProfile'] = $profile_url;
        $status = [];
        $check = [];
        $status["name"] = 1;
        $status["location"] = 1;
        $status["found_name"] = 0;
        $status["found_location"] = 0;
        $status["status"] = 200;
        $status["unique_name"] = (isset($combination['unique_name']) && !empty($combination['unique_name'])) ? $combination['unique_name'] : '';
        if (
            isset($combination['htmlContent']) &&
            is_array($combination['htmlContent'])
        ) {
            $this->htmlContent = $combination['htmlContent'];
            unset($combination['htmlContent']);
        }
        $profile_url = $this->entry->prepareContent($profile_url, $person['id'], $combination['id']);
        $status['type'] = 'list';
        if (
            stripos($profile_url, 'linkedin.com/in/') !== false ||
            stripos($profile_url,'linkedin.com/profile/view?id=')
        ) {
            $status['type'] = 'profile';
        }

        if ($status['type'] == 'list') {
            $check ['name'] = "it was list";
            $check ['location'] = "it was list";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app ()->reportLog->resultCheck ( $checkArray, $person, $combination );

            unset($checkArray);

            return $this->data;
        }

        $combination_level = $combination['combination_level'];
        $nameMatch = new NameMatch;
        $checkUniqueComb = $nameMatch->checkUniqueNameComb($combination_level);

        $url = $this->entry->normalizeURL($profile_url);

        $comb = $combination;

        $uname = $this->entry->getUsername($url);

        if (
            $uname &&
            isset($person['usernames']) &&
            stripos(",{$person['usernames']},", ",{$uname}," ) !== false
        ) {
            $this->data['username']['identities']['un'] = true;
            $this->data['username']['identities']['input_un'] = true;
            $this->data['username']['matchWith'] = $uname;

            // TODO
            // SearchApis::logData ( $person ['id'], "This profile $url comes from a manual/reverse username search, passed.", $comb );

            $req_url = (isset($extraData['capture_url']) && !empty($extraData['capture_url'])) ? $extraData['capture_url'] : $url;
            $profileLinkedin = $this->info;

            $status['nameDetails'] = [];

            if ($profileLinkedin  == false) {
                $status['name'] = $status['location'] = 0;
                $status['status'] = 404;
                return $this->data;
            }
            $status['name'] = $analyzer->runNameAnalyzer(
                $person,
                $combination,
                [$profileLinkedin['name']],
                $status['nameDetails']
            );
            $this->data['name']['matchWith'] = $status['nameDetails']['matchWith']??'';
            $this->data['name']['identities']['input_name'] = $status['nameDetails']['input_name']??false;

            if (in_array('fn', $status['nameDetails'])) {
                $this->data['name']['identities']['fn'] = true;
            }

            if (in_array('mn', $status['nameDetails'])) {
                $this->data['name']['identities']['mn'] = true;
            }
            if (in_array('IN', $status['nameDetails'])) {
                $this->data['name']['identities']['input_name'] = true;
            }

            if (in_array('ln', $status['nameDetails'])) {
                $this->data['name']['identities']['ln'] = true;
            }

            if (in_array('unq_name', $status['nameDetails'])) {
                $this->data['name']['identities']['unq_name'] = true;
            }
            if (in_array('fzn', $status['nameDetails'])) {
                $this->data['name']['identities']['fzn'] = true;
            }

            $status['locationDetails'] = [];
            $status['location'] = $analyzer->runLocationAnalyzer(
                $person,
                $combination,
                [$profileLinkedin['location']],
                $status['locationDetails']
            );

            $input = $status['locationDetails']['locations'][0]??'';
            $status['locationDetails'] = $status['locationDetails']['locationDetails']??[];
            if (in_array('st', $status['locationDetails'])) {
                $this->data['location']['identities']['st'] = true;
            }
            if (in_array('pct', $status['locationDetails'])) {
                $this->data['location']['identities']['pct'] = true;
            }

            if (in_array('matchTypeName', $status['locationDetails'])) {
                if ($status['locationDetails']['matchTypeName'] == 'SmallCityWithSmallCity') {
                    $this->data['location']['identities']['exct-sm'] = true;
                } elseif ($status['locationDetails']['matchTypeName'] == 'BigCityWithBigCity') {
                    $this->data['location']['identities']['exct-bg'] = true;
                }
            }
            $this->data['location']['matchWith'] = $input;

            $status['linkedinData'] = $profileLinkedin;

            $check['name'] = "manual/reverse username search (passed)";
            $check['location'] = "manual/reverse username search (passed)";
            $checkArray = [];
            $checkArray['result_url'] = $url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app ()->reportLog->resultCheck ( $checkArray, $person, $comb );
            unset($checkArray);

            return $this->data;
        }

        // # Check if name and location is empty,set log and return $status .
        if (empty($person['first_name']) && empty($person['city'])) {
            $check['name'] = "empty first_name && city";
            $check['location'] = "empty first_name && city";
            $checkArray = [];
            $checkArray['result_url'] = $profile_url;
            $checkArray['check'] = $check;
            // TODO
            // Yii::app ()->reportLog->resultCheck ( $checkArray, $person, $combination );
            unset($checkArray);
            return $this->data;
        }

        $req_url = (isset($extraData['capture_url']) && !empty($extraData['capture_url'])) ? $extraData['capture_url'] : $url;
        $profileLinkedin = $this->entry->getProfileInfo($req_url, 'linkedin', $this->htmlContent);

        if ($profileLinkedin  == false) {
            $status['name'] = $status['location'] = 0;
            $status ["status"] = 404;
            return $this->data;
        }
        // check and compare person[age] with linkedIn result age
        if (!empty($person['age'])
          && !empty($profileLinkedin['education']['age'])
          && !$this->checkLinkedInByAge($person, $profileLinkedin, $linkedinAge)
      ) {
            // TODO
            // SearchApis::logData($person['id'],"Hide Profile {$profile_url}, cause does not match with linkedin age(".$linkedinAge.")\n"  );
            $status['age'] = false;
            $status['person_age'] = $person['age'];
        } elseif (!empty($person['age'])) {
            $status['age'] = true;
            $this->data['age']['status'] = true;
            $this->data['age']['identities']['age'] = true;
            $this->data['age']['matchWith'] = $profileLinkedin['education']['age'];
            $status['person_age'] = $person['age'];
        }

        if (!empty($profileLinkedin['profile_url'])) {
            $status['profile_url'] = $profileLinkedin['profile_url'];
        }
        $status['linkedinData'] = $profileLinkedin;

        $combination['urlProfile'] = !empty($profileLinkedin['linkProfile']) ? $profileLinkedin['linkProfile'] : $profile_url;
        // TODO
        // SearchApis::logData ( $person ['id'], "Checking Linkedin profile {$combination['urlProfile']}", $combination );

        if ($profileLinkedin['name']) {
            $status ["found_name"] = 1;
            // check if the name has comma split something otherwise name task(#10378)
            if (preg_match('/,(?!(\)|.*\)))/s', $profileLinkedin['name'])) {
                preg_match('/([^,]+)/', $profileLinkedin['name'], $trimname);
                if (isset($trimname[1])) {
                    // TODO
                    // SearchApis::logData ( $person ['id'], "Trim linkedin profile {$profile_url} name {$profileLinkedin['name']} into {$trimname[1]} ", $combination );
                    $profileLinkedin['name'] = $trimname[1];
                }
            }
            $status['profile_name'] = $profileLinkedin['name'];
        } elseif (isset($extraData['profile_name'])) {
            $profileLinkedin['name'] = $extraData['profile_name'];
            // check if the name has comma split something otherwise name task(#10378)
            preg_match('/([^,]+)/', $profileLinkedin['name'], $trimname) ;
            if (isset($trimname[1])) {
                $profileLinkedin['name'] = $trimname[1];
            }
            $status['profile_name'] = $profileLinkedin['name'];
        }

        if ($profileLinkedin['image']) {
            $status['image'] = $profileLinkedin['image'];
        } elseif (isset($extraData['profile_image'])) {
            $profileLinkedin['image'] =$extraData['profile_image'];
            $status['image'] = $profileLinkedin['image'];
        }

        if ($profileLinkedin['linkProfile']) {
            $status['linkProfile'] = $profileLinkedin['linkProfile'];
        }

        //$url = !empty($status['linkProfile'])?$status['linkProfile']: $url;

        // name check
        if (empty($profileLinkedin['name']) || $profileLinkedin['name'] == false) {
            $status["name"] = 1;
            $check['name'] = "Not Found";
        }
        $nameAnalyzerStatus = $analyzer->runNameAnalyzer(
            $person,
            $combination,
            [$profileLinkedin['name']],
            $status['nameDetails']
        );

        if (!$nameAnalyzerStatus && !empty($profileLinkedin['otherNames'])) {
            $nameAnalyzerStatus = $analyzer->runNameAnalyzer(
                $person,
                $combination,
                $profileLinkedin['otherNames'],
                $status['nameDetails']);
        }
        if ($nameAnalyzerStatus) {
            $status['name'] = $nameAnalyzerStatus;
        } elseif (
            isset($combination['unique_name']) &&
            $combination['unique_name'] &&
            $ResultsCount == 1
        ) {
            $this->matchFnAndFirstL($profileLinkedin, $person, $status, $check);
            $status['onlyOne'] = true;
            $status ["name"] = $nameAnalyzerStatus ;
        } else {
            $status ["name"] = 0;
        }
        // Get location
        if (empty($profileLinkedin['location']) || $profileLinkedin['location'] == false) {
            $status["location"] = 1;
            $check['location'] = "Not Found";
        }

        /*
        * i'm added case (location = other) as per mark requested
        * to handle this profile http://linkedin.com/in/alice-lloyd-george-b6686124
        * @by hafez
        */
        $city2 = ($profileLinkedin['location'] && strtolower($profileLinkedin['location']) !== 'other' ) ? $profileLinkedin['location'] : '';

        if (
            isset($extraData['location']) &&
            empty($city2) &&
            !empty($extraData['location'])
        ) {
            $city2 = $extraData['location'];
        }

        $state2 = $city2;
        $locationAnalyzer = new LocationAnalyzer;
        $locations = $locationAnalyzer->loadAllLocations($person);
        if ($state2) {
            $status["found_location"] = 1;
        }

        /**
         * make check location just in third combination, combination not unique, any profile coming from google source,
         * and by forcing this Method match location.
         **/
        if (
            $ResultsCount > 1 &&
            $combination['big_city'] &&
            $combination_level == 1
        ) {
            $checkLocationByforce = true;
        }
        if (
            count($locations) > 0 &&
            (
                (
                    $combination_level == 2 &&
                    $status["unique_name"] !== '1' &&
                    $ResultsCount !== 1
                ) ||
                $combination_level == 3 ||
                $combination['main_source'] == 'google' ||
                $checkLocationByforce
            )
        ) {
            $this->linkedinLocationAnalyzer($status, $person, $comb, $city2);
            if ($this->checkLocationByforce) {
                $log = "    Checking location profile {$combination['urlProfile']}: \n";
                $log .="        Check Location by force.\n";
                // TODO
                // SearchApis::logData($person['id'], $log, $combination);
            }
        } elseif ($checkUniqueComb) {
            $this->linkedinLocationAnalyzer($status, $person, $comb, $city2);
        } else {
            $this->linkedinLocationAnalyzer($status, $person, $comb, $city2);
            $log = "    Checking location profile {$combination['urlProfile']}: \n";
            if ($checkUniqueComb) {
                $log .= "       Escape match location for this Profile ( {$combination['urlProfile']} ) cause it came from Unique Name.\n";
                // TODo
                // SearchApis::logData($person['id'],$log , $combination);
                $check['location'] = "Escape match location, cause this profile came from Uniqu Name.";
            } elseif( $combination_level != 3) {
                $log .="        Escape match location from combination level ( {$combination_level} ) .\n";
                // TODO
                // SearchApis::logData($person['id'], $log, $combination);
                $check['location'] = "Escape match location from combination level ( {$combination_level} ) .";
            } else {
                $log .="        Empty Person Location .\n";
                // TODO
                // SearchApis::logData($person['id'], $log, $combination);
                $check['location']="Empty Person Location";
            }
        }
        /*
         * The below log to know if the profile result combination level == 2 + resurlCount == 1
         * will escape from match location task #11320
         */
        if ($combination_level == 2 && $ResultsCount == 1) {
            $status["location"] = 1;
            $status['onlyOne'] = true;
            $status["locationDetails"] = ['exct', 'st'];
            $log = "    Checking location profile {$combination['urlProfile']}: \n";
            // TODO
            // SearchApis::logData($person['id'], "Match location by force where resultCount = 1 and from combination level ( {$combination_level} ) .\n", $combination);
        }
        $this->matchLinkedINCompaniesAndSchools($person, $combination, $profileLinkedin, $status);

        $checkArray = [];
        $checkArray['result_url'] = $profile_url;
        $checkArray['check'] = $check;
        // TODO
        // Yii::app ()->reportLog->resultCheck ( $checkArray, $person, $combination );
        unset($checkArray);
        $status['linkedinData'] = $profileLinkedin;
        return $this->data;;
    }

    private function checkLinkedInByAge($person, $profileLinkedin, &$linkedinAge = false)
    {
        $linkedinAge = (!empty($profileLinkedin['education']['age'])) ? $profileLinkedin['education']['age'] : false;
        if ($linkedinAge) {
            $ageFrom = $person['age'] - 5;
            $ageTo = $person['age'] + 5;
            if (
                $profileLinkedin['education']['age'] >= $ageFrom &&
                $profileLinkedin['education']['age'] <= $ageTo
            ) {
                return true;
            }
            return false;
        }
        return false;
    }

        /**
     * This function created to match linkedin profiles name
     * where $combination['unique_name'] = 1 && $ResultsCount = 1 and profile name like [Stephanie V.]
     * so here i match firstName with first letter in lastName
     * if matched $status [name] will be = 1;
     * Task #11219
     * @param $profileLinkedin
     * @param $person
     * @param $status
     * @param $check
     * @by Hafez
     */
    private function matchFnAndFirstL($profileLinkedin, $person, &$status, $check)
    {
        $nameMatch = new NameMatch;
        $fullName = explode(" ", $profileLinkedin['name']);
        //first letter from extracted last name
        $extractSNameL = str_split(strtolower($fullName[1]));
        //first letter from person last_name
        $personSNameL = str_split(strtolower($person['last_name']));
        $nameMatch->setFirstName1($fullName[0]);
        $nameMatch->setMiddleName1('');
        $nameMatch->setLastName1('fixed');
        $nameMatch->setFirstName2($person['first_name']);
        $nameMatch->setMiddleName2('');
        $nameMatch->setLastName2('fixed');
        $matchName = $nameMatch->match();
        if ($matchName[0] && $personSNameL[0] == $extractSNameL[0]) {
            $status["name"] = 1;
            // this to avoid invisible that created by Osama
            $status["nameDetails"] = ['fn', 'ln'];
            $check['name'] = "Matched";
        }
    }


    /**
     * call location analyzer to match linkedIn location
     * @param $status
     * @param $person
     * @param $comb
     * @param $linkedinProfileLocation
     * @note this function create before create locationAnalyzer in helper
     * @by Hafez
     */
    private function linkedinLocationAnalyzer(&$status, $person, $comb, $linkedinProfileLocation)
    {
        ## using location analyzer for location matching .

        $LocationAnalyzer = new \Skopenow\Matching\Analyzer\LocationAnalyzer();
        $LocationAnalyzer($person, $linkedinProfileLocation);
        $status['location'] = 0;
        if ($LocationAnalyzer->isMatch()) {
            $status["location"] = 1;
            $locationDetails = $LocationAnalyzer->getBestLocations();
            if (!empty($locationDetails['locationDetails'])) {
                $status["locationDetails"] = $locationDetails['locationDetails'] ;
                $status['locationDetails'] = array_merge($status['locationDetails'],$status['locationDetails']['matchScore']);
            }
        }
        // End of location matching .
        // TODO
        // SearchApis::logData ( $person ['id'], $LocationAnalyzer->getLog() ,$comb);
    }

    /*
    * match linkedin dataPoint[companies, schools]
    */
    private function matchLinkedINCompaniesAndSchools(
        $person,
        $combination,
        $profileLinkedin,
        &$status
    )
    {
        $status['work'] = '';
        $status['work_status'] = 0;
        $workAnalyzer = new WorkAnalyzer($person);
        $personCompanies = $workAnalyzer->extractPersonCompanies($person, $emailsCompanies);
        if (
            !empty($personCompanies) &&
            is_array($profileLinkedin['positions']) &&
            !empty(array_column($profileLinkedin['positions'], 'company')) &&
            $status['name']
        ) {
            $status['work_matched'] = true;
            $profileCompanies = array_column($profileLinkedin['positions'], 'company');
            // TODO
            // searchApis::logData($person['id'],"Found companies in linkedin profile".$combination['urlProfile'],$combination);
            // unset($profileCompanies[0]);
            foreach ($profileCompanies as $found_company) {
                $logData = "Found company [$found_company] is ";
                $workDetails = [];
                $workAnalyzer->setWork($found_company);
                $workAnalyzer->setWorkDetails($workDetails);
                $companyMatch = $workAnalyzer->isMatch();
                if (!empty($companyMatch['status']) && $companyMatch['status']) {
                    $status['work'] = $found_company;
                    $status['work_status'] = 1;
                    $status['workDetails'] = $workDetails;
                    if (in_array('cm', $status['workDetails'])) {
                        $this->data['work']['identities']['cm'] = true;
                    }
                    $this->data['work']['matchWith'] = $found_company;
                    // $combs_usernames=searchApis::add_username($found_company, $person, $combination);
                    // CVarDumper::dump($combs_usernames,11,11);
                    $logData .= "Matched company [$found_company] in :\n".print_r($personCompanies,true);
                    // TODO
                    // searchApis::logData($person['id'],$logData,$combination);
                    break;
                }
                $logData .= "Not matched";
                // TODO
                // searchApis::logData($person['id'],$logData,$combination);
                unset($companyMatch);
            }
        }

        $status['school'] = '';
        $status['school_status'] = 0;
        $schoolAnalyzer = new SchoolAnalyzer($person, $combination);
        $schools = $schoolAnalyzer->extractPersonSchools($person);
        if (
            !empty($schools) &&
            !empty($profileLinkedin['education']['education']) &&
            !empty(array_column($profileLinkedin['education']['education'], "school"))
        ) {
            $profileSchools = array_column($profileLinkedin['education']['education'],'school');
            $status['school_matched'] = true;
            foreach ($profileSchools as $found_school) {
                $schooLDetailes = [];
                $schoolAnalyzer->setSchool($found_school);
                $schoolAnalyzer->setSchoolDetails($schooLDetailes);
                $isSchoolMatch = $schoolAnalyzer->isMatch();
                if (!empty($isSchoolMatch['status'])) {
                    $status['school'] = $found_school;
                    $status['school_status'] = 1;
                    $status['schoolDetails'] = $schooLDetailes;
                    if (in_array('sc', $status['schoolDetails'])) {
                        $this->data['school']['identities']['sc'] = true;
                    }
                    $this->data['school']['matchWith'] = $found_school;
                    break;
                }
            }
        }
    }
}
