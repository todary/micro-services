<?php
namespace Skopenow\Matching\Check;

use Skopenow\Matching\Interfaces\CheckInterface;
use Skopenow\Matching\Services\ReportService;
use Skopenow\Matching\Status;
use Skopenow\Matching\Analyzer\NameAnalyzer;
use Skopenow\Matching\Analyzer\LocationAnalyzer;
use Skopenow\Matching\Analyzer\WorkAnalyzer;
use Skopenow\Matching\Analyzer\SchoolAnalyzer;
use App\Libraries\DBCriteria;

class Check implements CheckInterface
{
    private $personID;
    private $profileInfo;
    private $matchWith;
    private $status;
    private $reportService;
    private $disableMiddleNameCriteria = false;
    protected $is_relative = false;
    protected $resultSource = null;
    const AGE_LOWER_OFFSET = 5;
    const AGE_UPPER_OFFSET = 5;

    public function __construct(ReportService $reportService)
    {
        $this->personID = config('state.report_id');
        $this->status = (new Status)->matchingData;
        $this->reportService = $reportService;
    }

    public function check()
    {
        \Log::info('Matching');
        if (empty($this->matchWith)) {
            $this->matchWith['name'] = [];
            $this->matchWith['location'] = [];
            $this->matchWith['work'] = [];
            $this->matchWith['school'] = [];
            $this->matchWith['email'] = [];
            $this->matchWith['phone'] = [];
            $this->matchWith['age'] = [];
            $this->matchWith['username'] = [];

        }
        if (!empty($this->profileInfo['name'])) {
            $this->matchNames($this->profileInfo['name'], $this->matchWith['name']??[]);
        } elseif (empty($this->profileInfo['name']) && !empty($this->profileInfo['username'])) {
            $this->matchUsernameWithName($this->profileInfo['username'], $this->matchWith['name']??[]);
        } else {
            $this->status['name']['found_name'] = false;
        }

        if (!empty($this->profileInfo['location'])) {
            $this->matchLocations($this->profileInfo['location'], $this->matchWith['location']??[]);
        } else {
            $this->status['location']['found_location'] = false;
        }

        if (!empty($this->profileInfo['work'])) {
            $this->matchWork($this->profileInfo['work'], $this->matchWith['work']??[]);
        }

        if (!empty($this->profileInfo['school'])) {
            $this->matchSchools($this->profileInfo['school'], $this->matchWith['school']??[]);
        }

        if (!empty($this->profileInfo['email'])) {
            $this->matchEmails($this->profileInfo['email'], $this->matchWith['email']??[]);
        }

        if (!empty($this->profileInfo['phone'])) {
            $this->matchPhones($this->profileInfo['phone'], $this->matchWith['phone']??[]);
        }

        if (!empty($this->profileInfo['age'])) {
            $this->matchAge($this->profileInfo['age'], $this->matchWith['age']??[]);
        }

        if (!empty($this->profileInfo['username'])) {
            $this->matchUsername($this->profileInfo['username'], $this->matchWith['username']??[]);
        }
        return $this->status;
    }

    private function matchNames(array $names1, array $names2 = [])
    {
        $names = [];
        foreach ($names1 as $name) {
            $names[] = $name;
            $nameParts = name_parts($name);
            if (strtolower($nameParts['middle_name']) == "De") {
                $names[] = $nameParts['first_name'] . ' ' . $nameParts['last_name'];
                $names[] = $nameParts['first_name'] . ' De' . $nameParts['last_name'];
                $names[] = $nameParts['first_name'] . ' De ' . $nameParts['last_name'];
                continue;
            }
            if (strpos($name, '-') === false) {
                // $names[] = $name;
                continue;
            }
            $splittedLastname = explode('-', $nameParts['last_name']);
            foreach ($splittedLastname as $lastName) {
                if (!empty($nameParts['middle_name'])) {
                    $names[] = "{$nameParts['first_name']} {$nameParts['middle_name']} {$lastName}";
                } else {
                    $names[] = "{$nameParts['first_name']} {$lastName}";
                }
            }
        }

        $names1 = $names;

        $analyzer = new NameAnalyzer($this->reportService);
        if ($this->disableMiddleNameCriteria) {
            $analyzer->setDisableMiddlenameCriteria(true);
        }
        $analyzer->setIsRelative($this->is_relative);
        if (empty($names2)) {
            $analyzer($this->reportService->getReport(), $names1);
            $this->status['name']['identities']['input_name'] = true;
        } else {
            $analyzer->runNameAnalyzer($names1, $names2);
        }
        $nameDetails = $analyzer->getBestNameDetails();
        \Log::info($analyzer->getLog());
        \Log::info('best name details match', $nameDetails??[]);
        if (!$analyzer->isMatch()) {
            $this->status['name']['identities']['input_name'] = false;
            return;
        }
        if (!empty($nameDetails)) {
            $this->setNameDetails($nameDetails);
        }
        if ($this->status['name']['identities']['input_name']) {
            $this->status['name']['identities']['input_name'] = $this->status['name']['status'];
        }
    }

    private function setNameDetails(array $nameDetails = [])
    {
        if (in_array('fn', $nameDetails['nameDetails']['score'])) {
            $this->status['name']['identities']['fn'] = true;
        }
        if (in_array('mn', $nameDetails['nameDetails']['score'])) {
            $this->status['name']['identities']['mn'] = true;
        }
        if (in_array('ln', $nameDetails['nameDetails']['score'])) {
            $this->status['name']['identities']['ln'] = true;
        }

        if ($this->status['name']['identities']['fn'] ||
            $this->status['name']['identities']['mn'] ||
            $this->status['name']['identities']['ln']
        ) {
            $this->status['name']['status'] = true;
        }
        if (in_array('fzn', $nameDetails['nameDetails']['score'])) {
            $this->status['name']['identities']['fzn'] = true;
        }
        if (in_array('unq_name', $nameDetails['nameDetails']['score'])) {
            $this->status['name']['identities']['unq_name'] = true;
        }
        $this->status['name']['matchWith'] = $nameDetails['nameDetails']['matchWith'];
    }

    private function matchLocations(array $locations1, array $locations2 = [])
    {

        $analyzer = new LocationAnalyzer($this->reportService);
        if (empty($locations2)) {
            $analyzer($this->reportService->getReport(), $locations1);
            $this->status['location']['identities']['input_loc'] = true;
        } else {
            $analyzer->runLocationAnalyzer($locations1, $locations2);
        }
        $locationDetails = $analyzer->getBestLocations();

        \Log::info($analyzer->getLog());
        \Log::info('best location details match', $locationDetails??[]);

        if (!$analyzer->isMatch()) {
            $this->status['location']['identities']['input_loc'] = false;
            return;
        }
        if (!empty($locationDetails)) {
            $this->setLocationDetails($locationDetails);
        }
        if ($this->status['location']['identities']['input_loc']) {
            $this->status['location']['identities']['input_loc'] = $this->status['location']['status'];
        }
    }
    private function setLocationDetails(array $locationDetails = [])
    {
        if (! is_array($locationDetails['locationDetails']['matchScore'])) {
            return;
        }
        if (in_array('st', $locationDetails['locationDetails']['matchScore'])) {
            $this->status['location']['identities']['st'] = true;
        }
        if (is_array($locationDetails['locationDetails']['matchScore']) && in_array('pct', $locationDetails['locationDetails']['matchScore'])) {
            $this->status['location']['identities']['pct'] = true;
        }
        if (array_key_exists('matchTypeName', $locationDetails['locationDetails'])) {
            if ($locationDetails['locationDetails']['matchTypeName'] == 'SmallCityWithSmallCity') {
                $this->status['location']['identities']['exct-sm'] = true;
            } elseif ($locationDetails['locationDetails']['matchTypeName'] == 'BigCityWithBigCity') {
                $this->status['location']['identities']['exct-bg'] = true;
            }
        }
        $this->status['location']['status'] = true;
        if (array_key_exists('dist', $locationDetails['locationDetails'])) {
            $this->status['location']['distance'] = $locationDetails['locationDetails']['dist'];
        }
        $this->status['location']['matchWith'] = $locationDetails['locations'][1];
    }

    private function matchWork(array $work1, array $work2 = [])
    {
        $analyzer = new WorkAnalyzer($this->reportService);
        if (empty($work2)) {
            $this->status['work']['identities']['input_cm'] = true;
        }
        $workDetails = [];
        $analyzer->setAdditionalWorkExperience($work2);
        $analyzer->setWorkDetails($workDetails);
        foreach ($work1 as $work) {
            $analyzer->setWork($work);
        }
        if ($analyzer->isMatch()) {
            $workDetails = $analyzer->getWorkDetails();
            $this->setWorkDetails($workDetails);
        }
        if ($this->status['work']['identities']['input_cm']) {
            $this->status['work']['identities']['input_cm'] = $this->status['work']['status'];
        }
    }

    private function setWorkDetails(array $workDetails)
    {
        $this->status['work']['status'] = true;
        if (in_array('cm', $workDetails)) {
            $this->status['work']['identities']['cm'] = true;
        }

        if (array_key_exists('matchWith', $workDetails)) {
            $this->status['work']['matchWith'] = $workDetails['matchWith'];
        }
    }

    private function matchSchools(array $schools1, array $schools2 = [])
    {
        $analyzer = new SchoolAnalyzer($this->reportService);
        if (empty($schools2)) {
            $this->status['school']['identities']['input_sc'] = true;
        }
        $schoolDetails = [];
        $analyzer->setAdditionalSchools($schools2);
        $analyzer->setSchoolDetails($schoolDetails);
        foreach ($schools1 as $school) {
            $analyzer->setSchool($school);
        }
        if ($analyzer->isMatch()) {
            $this->status['school']['status'] = true;
            $schoolDetails = $analyzer->getSchoolDetails();
            $this->setSchoolDetails($schoolDetails);
        }
        if ($this->status['school']['identities']['input_sc']) {
            $this->status['school']['identities']['input_sc'] = $this->status['school']['status'];
        }
    }

    private function setSchoolDetails($schoolDetails)
    {
        if (in_array('sc', $schoolDetails)) {
            $this->status['school']['identities']['sc'] = true;
        }

        if (array_key_exists('matchWith', $schoolDetails)) {
            $this->status['school']['matchWith'] = $schoolDetails['matchWith'];
        }
    }

    private function matchAge(array $ages1, array $ages2 = [])
    {
        if ($ages2 == []) {
            $report = $this->reportService->getReport();
            $ages2 = $report['ages'];
        }
        foreach ($ages1 as $age1) {
            foreach ($ages2 as $age2) {
                $upperAge = $age2 + self::AGE_UPPER_OFFSET;
                $lowerAge = $age2 - self::AGE_LOWER_OFFSET;
                if ($age1 <= $upperAge && $age1 >= $lowerAge) {
                    $this->status['age']['status'] = true;
                    $this->status['age']['identities']['age'] = true;
                    break 2;
                }
            }
        }
        $this->status['age']['matchWith'] = $age2;
    }

    public function matchEmails(array $emails1, array $emails2 = [])
    {
        $input_emails = [];
        $emails_dataPoints = [];
        if (empty($emails2)) {
            $report = $this->reportService->getReport();
            $input_emails = $report['emails'];
            // $this->status['email']['identities']['input_em'] = true;

            $dataPointService = loadService('datapoint')->datasource();
            $DBCriteria = new DBCriteria();
            $DBCriteria->compare('report_id', config('state.report_id'));
            $DBCriteria->compare('type', 'emails');
            $emails_dataPoints = $dataPointService->loadData($DBCriteria);
        }

        foreach ($emails1 as $email1) {
            foreach ($emails2 as $email2) {
                $email1 = $this->prepareString($email1);
                $email2 = $this->prepareString($email2);
                if ($email1 === $email2) {
                    $this->status['email']['status'] = true;
                    $this->status['email']['matchWith'] = $email2;
                    $this->status['email']['identities']['em'] = true;
                    // break the outer loop
                    break 2;
                }
            }
        }

        foreach ($emails1 as $email1) {
            foreach ($input_emails as $email2) {
                $email1 = $this->prepareString($email1);
                $email2 = $this->prepareString($email2);
                if ($email1 === $email2) {
                    $this->status['email']['status'] = true;
                    $this->status['email']['matchWith'] = $email2;
                    $this->status['email']['identities']['em'] = true;
                    $this->status['email']['identities']['input_em'] = true;
                    // break the outer loop
                    break 2;
                }
            }
        }

        foreach ($emails1 as $email1) {
            foreach ($emails_dataPoints as $email2) {
                $email1 = $this->prepareString($email1);
                $email2 = $this->prepareString($email2['main_value']);
                if ($email1 === $email2) {
                    $this->status['email']['status'] = true;
                    $this->status['email']['matchWith'] = $email2;
                    $this->status['email']['identities']['em'] = true;
                    // break the outer loop
                    break 2;
                }
            }
        }

        if ($this->status['email']['identities']['input_em']) {
            $this->status['email']['identities']['input_em'] = $this->status['email']['status'];
        }
    }

    private function matchPhones(array $phones1, array $phones2 = [])
    {
        $input_phones = [];
        $phones_datapoints = [];
        if (empty($phones2)) {
            $report = $this->reportService->getReport();
            $input_phones = $report['phones'];
            // $this->status['phone']['identities']['input_ph'] = true;

            $dataPointService = loadService('datapoint')->datasource();
            $DBCriteria = new DBCriteria();
            $DBCriteria->compare('report_id', config('state.report_id'));
            $DBCriteria->compare('type', 'phones');
            $phones_datapoints = $dataPointService->loadData($DBCriteria);
        }

        foreach ($phones1 as $phone1) {
            foreach ($phones2 as $phone2) {

                if ($this->matchPhone($phone1, $phone2)) {
                    $this->status['phone']['status'] = true;
                    $this->status['phone']['matchWith'] = $phone2;
                    $this->status['phone']['identities']['ph'] = true;
                    $this->status['phone']['identities']['input_ph'] = true;
                    // Break Outer Loop
                    break 2;
                }
            }
        }

        foreach ($phones1 as $phone1) {
            foreach ($input_phones as $phone2) {
                if ($this->matchPhone($phone1, $phone2)) {
                    $this->status['phone']['status'] = true;
                    $this->status['phone']['matchWith'] = $phone2;
                    $this->status['phone']['identities']['ph'] = true;
                    // Break Outer Loop
                    break 2;
                }
            }
        }

        foreach ($phones1 as $phone1) {
            foreach ($phones_datapoints as $phone2) {
                $phone2 = $phone2['main_value'];
                if ($this->matchPhone($phone1, $phone2)) {
                    $this->status['phone']['status'] = true;
                    $this->status['phone']['matchWith'] = $phone2;
                    $this->status['phone']['identities']['ph'] = true;
                    // Break Outer Loop
                    break 2;
                }
            }
        }

        if ($this->status['phone']['identities']['input_ph']) {
            $this->status['phone']['identities']['input_ph'] = $this->status['phone']['status'];
        }
    }

    private function matchPhone(string $phone1, string $phone2)
    {
        if (strpos($phone1, '00') !== false && strpos($phone1, '00') == 0) {
            $phone1 = substr($phone1, 2);
        }
        if (strpos($phone1, '+') !== false && strpos($phone1, '+') == 0) {
            $phone1 = substr($phone1, 1);
        }
        if (strpos($phone2, '00') !== false && strpos($phone2, '00') == 0) {
            $phone2 = substr($phone2, 2);
        }
        if (strpos($phone2, '(') !== false) {
            $phone2 = str_ireplace('(', '', $phone2);
        }
        if (strpos($phone2, ')') !== false) {
            $phone2 = str_ireplace(')', '', $phone2);
        }
        if (strpos($phone2, ' ') !== false) {
            $phone2 = str_ireplace(' ', '', $phone2);
        }
        if (strpos($phone2, '-') !== false) {
            $phone2 = str_ireplace('-', '', $phone2);
        }
        if (strpos($phone2, '+') !== false && strpos($phone2, '+') == 0) {
            $phone2 = substr($phone2, 1);
        }
        $phone1 = $this->prepareString($phone1);
        $phone2 = $this->prepareString($phone2);
        if ($phone1 === $phone2) {
            return true;
        }

        return false;
    }

    private function matchUsername(array $usernames1, array $usernames2 = [])
    {
        $usernames_inputs = [];
        $usernames_datapoints = [];
        if ($usernames2 === []) {
            $report = $this->reportService->getReport();
            $usernames_inputs = $report['usernames'];

            $dataPointService = loadService('datapoint')->datasource();
            $DBCriteria = new DBCriteria();
            $DBCriteria->compare('report_id', config('state.report_id'));
            $DBCriteria->compare('type', 'added_usernames');
            $usernames_datapoints = $dataPointService->loadData($DBCriteria);
        }

        foreach ($usernames1 as $username1) {
            $username1 = $this->prepareString($username1);

            foreach ($usernames2 as $username2) {
                $username2 = $this->prepareString($username2);

                if ($username1 === $username2) {
                    $this->status['username']['status'] = true;
                    $this->status['username']['matchWith'] = $username2;
                    $this->status['username']['identities']['un'] = true;
                }
            }

            foreach ($usernames_inputs as $username2) {
                $username2 = $this->prepareString($username2);

                if ($username1 === $username2) {
                    $this->status['username']['status'] = true;
                    $this->status['username']['matchWith'] = $username2;
                    $this->status['username']['identities']['un'] = true;
                    $this->status['username']['identities']['input_un'] = true;
                }
            }

            foreach ($usernames_datapoints as $username_datapoint) {
                $username2 = $this->prepareString($username_datapoint['main_value']);
                if (!empty($username_datapoint['data']['source']) && !is_null($this->resultSource) && $username_datapoint['data']['source'] != $this->resultSource) {
                    if ($username1 === $username2) {
                        $this->status['username']['status'] = true;
                        $this->status['username']['matchWith'] = $username2;

                        $this->status['username']['identities']['un'] = true;
                        if (!empty($username_datapoint['is_verified'])) {
                            $this->status['username']['identities']['verified_un'] = true;
                        }
                        if(!empty($username_datapoint['sources']) && in_array('peopleData', $username_datapoint['sources'])) {
                            $this->status['username']['identities']['people_un'] = true;
                        }
                    }
                }
            }
        }
        // dd($usernames_datapoints, $this->status['username']);
    }

    public function setProfileInfo(array $profileInfo)
    {
        $this->profileInfo = $profileInfo;
    }

    public function setMatchWith(array $matchWith)
    {
        $this->matchWith = $matchWith;
    }

    public function setIsRelative(bool $status)
    {
        $this->is_relative = $status;
    }

    public function setResultSource($source)
    {
        $this->resultSource = $source;
    }

    private function prepareString(string $str)
    {
        return trim(strtolower($str));
    }
    public function disableMiddleNameCriteria(bool $status)
    {
        $this->disableMiddleNameCriteria = $status;
    }

    protected function matchUsernameWithName(array $usernames, array $names = [])
    {
        $isInput = false;
        if (empty($names)) {
            $report = loadService('reports')->getReport();
            $isInput = true;
            $names = $report['names'];
        }
        foreach ($usernames as $username) {
            $username = str_replace(['.', '_', ' '], '', $username);
            $username = strtolower($username);
            foreach ($names as $name) {
                $name_parts = name_parts($name);
                if (!empty($name_parts['middle_name'])) {
                    $uns = [
                        strtolower($name_parts['first_name'] . $name_parts['middle_name'] . $name_parts['last_name']),
                        strtolower($name_parts['first_name'] . substr($name_parts['middle_name'], 0, 1) . $name_parts['last_name']),
                    ];
                    if (in_array($username, $uns)) {
                        $this->status['name']['status'] = true;
                        $this->status['name']['identities']['fn'] = true;
                        $this->status['name']['identities']['mn'] = true;
                        $this->status['name']['identities']['ln'] = true;
                        $this->status['name']['matchWith'] = $name_parts['full_name'];
                        break 2;
                    }
                }
                $uns = [strtolower($name_parts['first_name'] . $name_parts['last_name'])];
                if (in_array($username, $uns)) {
                    $this->status['name']['status'] = true;
                    $this->status['name']['identities']['fn'] = true;
                    $this->status['name']['identities']['ln'] = true;
                    $this->status['name']['matchWith'] = $name_parts['full_name'];
                    break 2;
                }
            }
        }
        $unique = isUniqueFullName([$name]);
        if ($this->status['name']['status'] && $unique[0]) {
            $this->status['name']['identities']['unq_name'] = true;
        }

        if ($isInput && $this->status['name']['status']) {
            $this->status['name']['identities']['input_name'] = true;
        }
    }
}
