<?php

namespace Skopenow\Matching\Analyzer;

use Skopenow\Matching\Interfaces\AnalyzerInterface;
use Skopenow\Matching\Services\ReportService;
use Skopenow\Matching\Match\SchoolMatch;
use App\EduList;

class SchoolAnalyzer implements AnalyzerInterface
{
    private $log = '';
    private $school;
    private $schooLDetailes = [];
    private $additionalSchools = [];

    public function __construct(ReportService $report)
    {
        $this->match = new SchoolMatch;
        $this->person = $report->getReport();
    }

    public function setSchool($school)
    {
        $this->school = $school;
    }

    public function setSchoolDetails(array &$schooLDetailes)
    {
        $this->schooLDetailes = $schooLDetailes;
    }

    public function setAdditionalSchools(array $additionalSchools)
    {
        $this->additionalSchools = $additionalSchools;
    }
    public function isMatch() : bool
    {
        $this->log .= "[School Analyzer] Begin isSchoolMatch ...............\n";
        $status = false;

        $this->log .= "[School Analyzer] Extract Persons Schools ........ \n";
        $schools = [];
        if (empty($this->additionalSchools)) {
            $schools = $this->extractPersonSchools($this->person);
        }
        $this->log .= "[School Analyzer] add additional Schools ........ \n";
        if (!empty($this->additionalSchools)) {
            $schools = array_merge($schools, $this->additionalSchools);
        }
        $this->log .= "[School Analyzer] schools :  \n" . print_r($schools, true) ."\n";

        $statusMatch = false;
        if (!empty($schools)) {
            foreach ($schools as $key => $sc) {
                $this->match->setSchool1($sc);
                $this->match->setSchool2($this->school);
                if ($this->match->match()) {
                    $this->log .= "[School Analyzer] Matched School $sc -- $this->school \n";
                    $statusMatch = true;
                    break;
                } elseif (stripos($this->school, $sc) !== false) {
                    $this->log .= "[School Analyzer] Matched School $sc -- $this->school \n";
                    $statusMatch = true;
                    break;
                }
            }
            if ($statusMatch) {
                $this->log .= "[School Analyzer] Final *** There is  Matched School \n";
                $status = true;
                $this->schooLDetailes[] = 'sc';
                $this->schooLDetailes['matchWith'] = $this->school;
            } else {
                $this->log .= "[School Analyzer] Final *** No Matched School \n";
            }
        }
        return $status;
    }

    public function getSchoolDetails()
    {
        return $this->schooLDetailes;
    }
    /**
    * [extractPersonSchools description]
    * @param  [type]        $person [the person object]
    * @return array         [the Schools array entered in the searchFields or null in empty]
    *
    */
    public function extractPersonSchools($person)
    {
        $this->log .= "[School Analyzer] Persons Schools : " . print_r($person['schools'], true) ."\n";
        $schools = $person['schools'];
        $schools = array_filter(array_unique($schools));
        $emails = $person['emails'];
        if (!empty($emails)) {
            $this->log .= "[School Analyzer] Extracted Schools From Emails ......... \n";
            $emailCompanies = $this->extractSchoolsFromEmails($emails);
            $this->log .= "[School Analyzer] Emails Schools : " . print_r($emailCompanies, true) ."\n";
            $schools = array_merge($schools, array_values($emailCompanies));
        }
        $schools = array_filter(array_unique($schools));
        return $schools;
    }

    private function extractSchoolsFromEmails($emails)
    {
        $schools = [];
        foreach ($emails as $email) {
            $mail = explode("@", $email);
            if (isset($mail[1])) {
                $mail = $mail[1];
            } else {
                return $schools;
            }
            $school = EduList::where("mail", $mail)->first();
            if (isset($school[0])) {
                $schools[] = $school[0]['school'];
            }
        }
        return $schools;
    }
}
