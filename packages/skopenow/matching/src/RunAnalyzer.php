<?php
namespace Skopenow\Matching;

use Skopenow\Matching\Analyzer\{
    NameAnalyzer,
    LocationAnalyzer,
    SchoolAnalyzer,
    WorkAnalyzer
};
use Skopenow\Matching\Services\ReportService;

class RunAnalyzer
{
    private $report;

    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }

    public function runNameAnalyzer(
        $person,
        $combination = null,
        $names,
        &$nameDetailes,
        $params = []
    )
    {
        $status = false;
        $is_relative = false;
        $disableMiddlenameCriteria = false;
        if (isset($params['is_relative'])) {
            $is_relative = $params['is_relative'];
        }
        if (isset($params['disableMiddlenameCriteria'])) {
            $disableMiddlenameCriteria = $params['disableMiddlenameCriteria'];
        }
        $nameMatch = new NameAnalyzer($this->report);
        $nameMatch->setIsRelative($is_relative);
        $nameMatch->setDisableMiddlenameCriteria($disableMiddlenameCriteria);
        $nameMatch($person, $names);

        if ($nameMatch->isMatch()) {
            $status = true;
            $bestMatch = $nameMatch->getBestNameDetails();
            if (!empty($bestMatch['nameDetails']['score'])) {
                $nameDetailes = $bestMatch['nameDetails']['score'];
                if (
                    !empty($bestMatch['nameDetails']['similarity']) &&
                    $bestMatch['nameDetails']['similarity'] == "exactName"
                ) {
                    $nameDetailes[] = "exactName";
                } else {
                    $nameDetailes[] = "fuzzyName";
                }
                if (!empty($bestMatch['nameDetails']['matchWith'])) {
                    $nameDetailes['matchWith'] = $bestMatch['nameDetails']['matchWith'] ;
                }
                if (!empty($bestMatch['nameDetails']['realName'])) {
                    $nameDetailes["input_name"] = true;
                }
                else {
                    $nameDetailes['input_name'] = false;
                }
            }
        }
        // TODO
        // searchApis::logData($person['id'],$nameMatch->getLog(),$combination);
        return $status;
    }

    public function runLocationAnalyzer(
        $person,
        $combination = null,
        $locations,
        &$locationDetail
    )
    {
        // using location analyzer for location matching .
        $status = 0;
        $LocationAnalyzer = new LocationAnalyzer($this->report);
        $LocationAnalyzer($person, $locations);
        if ($LocationAnalyzer->isMatch()) {
            $status = 1;
            $matchingDetailes = $LocationAnalyzer->getBestLocations();
            if (!empty($matchingDetailes)) {
                $locationDetail = $matchingDetailes;
                // $locationDetail = array_merge($locationDetail, $locationDetail['matchScore']);
            }
        }
        // TODO
        // searchApis::logData($person['id'],$LocationAnalyzer->getLog(),$combination);
        return $status;
    }

    public function runSchoolAnalyzer(
        $person,
        $combination = null,
        $schools,
        &$schoolDetailes,
        $additionalSchools = []
    )
    {
        $status = true;
        $log = "";
        if (
            !empty($schools) &&
            (!empty($person->school) || !empty($additionalSchools))
        ) {
            $status = false;
            $analyzer = new SchoolAnalyzer($this->report, $combination);
            foreach ($schools as $school) {
                $analyzer->setSchool($school);
                $analyzer->setAdditionalSchools($additionalSchool);
                $analyzer->setSchoolDetailes($schoolDetailes);
                $schoolMatch = $analyzer->isMatch();
                if ($schoolMatch) {
                    $status = true;
                    break;
                }
            }
        }

        // TODO
        // searchApis::logData($person['id'],$log,$combination);

        return $status;
    }

    public function runWorkAnalyzer(
        $person,
        $work_Exps,
        &$workDetailes,
        $additionalWorkExp = []
    )
    {
        $status = true;
        if(
            !empty($work_Exps) &&
            (!empty($person->company) || !empty($additionalWorkExp))
        ) {
            $status = false;
            $analyzer = new WorkAnalyzer($this->report);
            foreach ($work_Exps as $work_Exp) {
                $analyzer->setWork($work_Exp);
                $analyzer->setWorkDetails($workDetailes);
                $analyzer->setAdditionalWorkExperience($additionalWorkExp);
                $schoolMatch = $analyzer->isMatch();
                if ($schoolMatch) {
                    $status = true;
                    break;
                }
            }
        }
        return $status;
    }
}
