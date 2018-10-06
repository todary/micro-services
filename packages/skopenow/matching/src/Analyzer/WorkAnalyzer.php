<?php
namespace Skopenow\Matching\Analyzer;

use \Illuminate\Support\Facades\DB;
use Skopenow\Matching\Match\WorkMatch;
use Skopenow\Matching\Services\ReportService;

class WorkAnalyzer
{
    private $work;
    private $person;
    private $workDetailes = [];
    private $additionalWorkExp = [];

    public function __construct(ReportService $report)
    {
        $this->person = $report->getReport();
        $this->match = new WorkMatch;
    }

    public function setWork(string $work)
    {
        $this->work = $work;
    }

    public function setWorkDetails(array &$workDetailes)
    {
        $this->workDetailes = $workDetailes;
    }

    public function setAdditionalWorkExperience(array $additionalWorkExp)
    {
        $this->additionalWorkExp = $additionalWorkExp;
    }

    public function isMatch() : bool
    {
        $status = false;
        if (!$this->work || !is_string($this->work)) {
            return $status;
        }
        $companies = [];
        if (empty($this->additionalWorkExp)) {
            $companies = $this->extractPersonCompanies($this->person, $emailsCompanies);
        }
        if (!empty($this->additionalWorkExp)) {
            $companies = array_merge($companies, $this->additionalWorkExp);
        }
        $statusMatch = false;
        if (!empty($companies)) {
            foreach ($companies as $key => $cm) {
                $params = array("remove_three_chars_words" => true);
                $this->match->setParameters($params);
                $this->match->setWork1($cm);
                $this->match->setWork2($this->work);
                $this->match->extractCompany(true);

                if ($this->match->match($cm, $this->work)) {
                    $statusMatch= true;
                    break;
                } elseif ($this->match->match()) {
                    $statusMatch = true;
                    break;
                } elseif ($this->match->match()) {
                    $statusMatch= true;
                    break;
                } elseif (stripos($this->work, $cm)) {
                    $statusMatch = true;
                    break;
                }
            }
            if ($statusMatch) {
                $status = true;
                $this->workDetailes[] = 'cm';
                $this->workDetailes['matchWith'] = $this->work;
            }
        }
        return $status;
    }

    public function getWorkDetails()
    {
        return $this->workDetailes;
    }

    /**
    * [extractPersonCompanies description]
    * @param  [type]        $person [the person object]
    * @return array         [the companies array entered in the searchFields or null in empty]
    */
    public function extractPersonCompanies($person, &$emailCompanies)
    {
        $companies = $person['companies'];
        $emails = $person['emails'];
        if (!empty($emails)) {
            $emailCompanies = $this->extractCompaniesFromEmails($emails);
            $companies = array_merge($companies, array_values($emailCompanies));
        }

        $companies = array_filter(array_unique($companies));
        return $companies;
    }

    private function extractCompaniesFromEmails($emails)
    {
        $emailsBlackList = DB::table("email_blacklist")->select('domain')->get();
        $emailsBlackList = $emailsBlackList->toArray();

        $companies = [];
        foreach ($emails as $key => $email) {
            $combs_fields[1] = ['em' =>  $email];
            $domain = "";
            preg_match('/([^@]+)@(.*)\.([^.]*)$/', $email, $explodeEmail);
            if (!empty($explodeEmail[1]) && !empty($explodeEmail[2])) {
                $domain = $explodeEmail[2].".".$explodeEmail[3];
            } else {
                preg_match("/([^@]*)@([^.]*)(\..+)/", $email, $explodeEmail);
                if (!empty($explodeEmail[1]) && !empty($explodeEmail[2])) {
                    $domain = $explodeEmail[2].$explodeEmail[3];
                }
            }
            if (!empty($domain)) {
                // escape this email when domain is in black list
                if (in_array($domain, $emailsBlackList)) {
                    continue;
                }
                $nameDetails = $this->getNameFromUsername($explodeEmail[1]);
                $companies[$email] = $explodeEmail[2];
            }
        }
        return $companies;
    }

    private function getNameFromUsername($username)
    {
        $name = [];
        $delimiters = ['.','-','_'];
        $patterns = '#\d+#i';
        $username = preg_replace($patterns, '', $username);

        $ready = str_replace($delimiters, $delimiters[0], $username);
        $expName = explode($delimiters[0], $ready);
        $name['fn'] = $expName[0];
        $name['ln'] = (count($expName) > 2 ? $expName[2] : (isset($expName[1]) ? $expName[1] : null));
        return $name;
    }
}
