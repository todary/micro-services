<?php

namespace Skopenow\Matching\Analyzer;

use Skopenow\Matching\Interfaces\AnalyzerInterface;
use Skopenow\Matching\Services\ReportService;

/**
*  class Name Analyzer
*  @author Mohammed Attya
*/
class NameAnalyzer implements AnalyzerInterface
{
    /**
     * [$person_names all person names in search and prograss data]
     * @var array of objects [ { first_name , midle_name , last_name } ]
     */
    private $person_names = [];

    /**
     * [$names names will matched with person names]
     * @var array
     */
    private $names = [];

    /**
     * [$fullNmaeDetails description]
     * @var array
     */
    private $fullNameDetails = [];

    /**
     * [$person description]
     * @var null
     */
    private $person = null;

    private $person_relative = [];

    /**
     * [$matchingRules description]
     * @var [type]
     */
    private $matchingRules =
    [
        "firstMiddleLast_Criteria" => true,
        "fuzzyName_Criteria" => true,
        "firstLast_lastFirst_Criteria"  => true,
        'F_MiddleLast_Criteria' => true,
        'firstMiddle_L_Criteria' => true,
        'first_M_Last_Criteria' => true,
        'firstMidde_firstLast_Criteria' => true,
    ];

    /**
     * [$matched_names description]
     * @var array
     */
    private $matched_names = ["exact" => [], "fuzzy" => []];

    /**
     * [$middle_names names have middle name]
     * @var array
     */
    private $middle_names = [];

    /**
     * [$is_relative description]
     * @var boolean
     */
    private $is_relative = false ;

    /**
     * [$log description]
     * @var string
     */
    private $log = "\n";

    /**
     * [$nick_names description]
     * @var array
     */
    private $nick_names = [];

    /**
     * [$person_middle_names description]
     * @var array
     */
    private $person_middle_names = [];

    /**
     * [$empty_names description]
     * @var array
     */
    private $empty_names = false;

    /**
     * [$otherNames all person other names from prograss data]
     * @var array
     */
    private $otherNames = [];

    /**
     * [$matched_middle_names names that matched middle names]
     * @var array
     */
    private $matched_middle_names = [];

    /**
     * [$matched_priorty structure of matched priorty]
     * @var [type]
     */
    private $matched_priorty = [
        "middle" => [
            "realName" => ["exact" => [] , "fuzzy" => []],
            "otherName" => ["exact" => [] , "fuzzy" => []],
        ],
        "firstLast" => [
            "realName" => ["exact" => [], "fuzzy" => []] ,
            "otherName" => ["exact" => [], "fuzzy" => []] ,
        ],
    ];

    /**
     * [$middleNamesMatchedCriteria if want to apply not matched middle names keep it true ,otherwise make it false ]
     * @var boolean
     */
    private $disableMiddlenameCriteria = false ;

    /**
     * [$oneWordExist description]
     * @var boolean
     */
    private $oneWordExist = false;

    /**
     * [$oneWordMatch description]
     * @var boolean
     */
    private $oneWordMatch = false;

    private $report;

    public function __construct(ReportService $report)
    {
        $this->nameMatch = new \Skopenow\Matching\Match\NameMatch;
        $this->report = $report;
    }

    /**
     * [__invoke description]
     * @param  \Persons $person [description]
     * @param  [type]   $names  [description]
     * @return [type]           [description]
     */
    public function __invoke($person, $names)
    {
        $this->person = $person;
        $this->stringToArray($names);

        if (empty($names)) {
            $this->empty_names = true;
            return;
        }
        // TODO
        $person_names = $this->getAllPersonNames($person);

        if ($this->is_relative) {
            $person_names = $this->getRelativeNames();
        }

        $this->otherNames = $this->getOtherNames();
        // $person_names = array_merge($person_names, $this->otherNames);
        $this->person_names  = $this->preparingNames($person_names, true);
        $this->names  = $this->preparingNames($names);
        if (empty($this->person_names) || empty($this->names)) {
            $this->empty_names = true;
            return;
        }
        $this->process_matching_names();

        // TODO
        /**
        if (\SearchApis::$testing) {
            echo '<pre>'.$this->log.'</pre>';
        }
        */
    }

    /**
     * [runNameAnalyzer description]
     * @param  array  $names1 [description]
     * @param  array  $names2 [description]
     * @return [type]         [description]
     */
    public function runNameAnalyzer($names1, $names2)
    {
        if (!is_array($names1)) {
            $this->stringToArray($names1);
        }
        if (!is_array($names2)) {
            $this->stringToArray($names2);
        }

        if(empty($names1) || empty($names2) )
        {
            $this->empty_names = true;
            return;
        }

        $this->person_names = $this->preparingNames($names1, true);
        $this->names = $this->preparingNames($names2);
        if (empty($this->person_names) || empty($this->names)) {
            $this->empty_names = true;
            return;
        }

        $this->process_matching_names();

        // TODO
        /**
        if (\SearchApis::$testing) {
            echo '<pre>'.$this->log.'</pre>';
        }
        */
    }

    /**
     * [getBestNameDetails description]
     * @return [type] [description]
     */
    public function getBestNameDetails()
    {
        $this->log .= "[NameAnalyzer] best name details will detrimine best name from matched priorty array  \n" . print_r($this->matched_priorty,true) ."\n";

        //////////////////////// Middle Name /////////////////////////////
        // check matched priorty  have middle name , real and exact
        if (!empty($this->matched_priorty['middle']["realName"]["exact"])) {
            extract(array_pop($this->matched_priorty['middle']["realName"]["exact"]));
            return $this->preparingResult($name1, $name2);
        }

        if (!empty($this->matched_priorty['middle']["realName"]["fuzzy"])) {
            extract(array_pop($this->matched_priorty['middle']["realName"]["fuzzy"]));
            return $this->preparingResult($name1, $name2);
        }

        // check matched priorty  have middle name , other and exact
        if (!empty($this->matched_priorty['middle']["otherName"]["exact"])) {
            extract(array_pop($this->matched_priorty['middle']["otherName"]["exact"]));
            return $this->preparingResult($name1, $name2);
        }

        if (!empty($this->matched_priorty['middle']["otherName"]["fuzzy"])) {
            extract(array_pop($this->matched_priorty['middle']["otherName"]["fuzzy"]));
            return $this->preparingResult($name1, $name2);
        }

        //////////////////////// First Last Name ////////////////////////////////
        // check matched priorty  have first last name , other and exact
        if (!empty($this->matched_priorty['firstLast']["realName"]["exact"])) {
            extract(array_pop($this->matched_priorty['firstLast']["realName"]["exact"]));
            return $this->preparingResult($name1, $name2);
        }

        if (!empty($this->matched_priorty['firstLast']["realName"]["fuzzy"])) {
            extract(array_pop($this->matched_priorty['firstLast']["realName"]["fuzzy"]));
            return $this->preparingResult($name1, $name2);
        }

        // check matched priorty  have first last name , other and exact
        if (!empty($this->matched_priorty['firstLast']["otherName"]["exact"])) {
            extract(array_pop($this->matched_priorty['firstLast']["otherName"]["exact"]));
            return $this->preparingResult($name1, $name2);
        }

        if (!empty($this->matched_priorty['firstLast']["otherName"]["fuzzy"])) {
            extract(array_pop($this->matched_priorty['firstLast']["otherName"]["fuzzy"]));
            return $this->preparingResult($name1, $name2);
        }

        return null;
    }

    public function all_words_match()
    {
        $personNames = [];
        $checkedNames = [];

        foreach ($this->person_names as $person_name) {
            $personNames[] = $person_name->first_name;
            $personNames[] = $person_name->middle_name;
            $personNames[] = $person_name->last_name;
        }

        foreach ($this->names as $name) {
            $checkedNames[] = $name->first_name;
            $checkedNames[] = $name->middle_name;
            $checkedNames[] = $name->last_name;
        }

        $personNames = array_unique($personNames);
        $checkedNames = array_unique($checkedNames);

        $result = array_intersect($checkedNames, $personNames);
        return count($result) == count($checkedNames);
    }

    /**
     * [isMatch description]
     * @return boolean [description]
     */
    public function isMatch() : bool
    {
        if ($this->disableMiddlenameCriteria) {
            $this->log .= "\n[NameAnalyzer] ***Note*** Ignored Rejected Middle Name Criteria \n";
        }

        if (
            $this->oneWordExist &&
            (empty($this->person_names) || empty($this->names))
        ) {
            if ($this->oneWordMatch) {
                $this->log .= "\n[NameAnalyzer] ***FINAL*** Match True (Pass One Word That Match Name) \n";
                return true;
            }
            $this->log .= "\n[NameAnalyzer] ***FINAL*** Match False (Pass One Word un Match Name) \n";
            return false;
        }

        if ($this->empty_names) {
            $this->log .= "\n[NameAnalyzer] ***FINAL*** Match Stop (Pass Empty data to the Analyzer Assuming Match) \n";
            return true;
        }

        if (
            !$this->middleNames_Criteria() &&
            !$this->disableMiddlenameCriteria &&
            !$this->all_words_match()
        ) {
            $this->log .= "\n[NameAnalyzer] ***FINAL*** There's un matched middle name(s) \n";
            return false;
        }

        if ($this->oneWordExist && $this->oneWordMatch) {
            $this->log .= "\n[NameAnalyzer] ***FINAL*** Match True (Pass One Word That Match Name) \n";
            return true;
        } elseif (
            empty($this->matched_names["exact"]) &&
            empty($this->matched_names["fuzzy"])
        ) {
            $this->log .= "\n[NameAnalyzer] ***FINAL*** There's un matched name(s)\n";
            return false;
        }

        $this->log .= "\n[NameAnalyzer] ***FINAL*** There's matched name(s)\n";
        return true;
    }

    public function getLog()
    {
        return $this->log;
    }

    /**
     * [getAllPersonLocations get all person names]
     * @param  \Persons $person [description]
     * @return [type]           [description]
     */
    private function getAllPersonNames($person)
    {
        // TODO
        // $temp = \SearchApis::load_progress($person->id, false);
        $temp = $this->report->getAllPersonNames($person['id']);
        $searched_names = $temp;
        $searched_names = $this->filterNamesFromResults($searched_names);
        $searched_names = @array_column($searched_names,"main_value");
        if (empty($searched_names)){
            $searched_names = $person['names'];//array_filter(explode(",", $person['searched_names']));
        }

        if (!empty($searched_names)) {
            $searched_names = array_unique($searched_names);
        }
        $names_temp = $person['names']; //explode(",",  $person['searched_names']);
        if (!empty($names_temp)) {
            $searched_names = array_unique(
                array_map("StrToLower", array_merge($searched_names,$names_temp)));
        }
        return $searched_names;
    }

    /**
     * [preparingNames description]
     * @param  [type] $p_names [description]
     * @return [type]          [description]
     */
    private function preparingNames($p_names, $is_person = false)
    {
        $names_temp = [];
        foreach ($p_names as $name) {
            $this->log .= "[NameAnalyzer] preparingNames " . print_r($name,true) . "\n";
            if (!is_string($name)) {
                $this->log .= " ***not string*** " . print_r($name,true) . "\n";
                continue;
            }
            $name = trim($name);

            if (stripos($name, " ") === false) {
                $this->log .= " ***no enough name parts*** " . print_r($name,true) . "\n";
                $this->oneWordExist = true;
                if ($this->matchingOneWord($name)) {
                    $this->oneWordMatch = true;
                }
                continue;
            }

            // remove pranthes ()
            $name = preg_replace("#\\(.*?\\)#", "$3", $name);

            $name = $this->filterName($name);

            $name = preg_replace("#^(dr|mr|ms|senior|junior|sr|jr)(\s|\.)+#i", "$3", $name);


            $splitName = $this->splitName($name);
            $names = new \StdClass;
            $names->first_name = '';
            $names->middle_name = '';
            $names->last_name = '';
            $names->full_name = '';

            if (isset($splitName['firstName'])) {
                $names->first_name = strtolower($splitName['firstName']);
            }
            if (isset($splitName['middleName'])) {
                $names->middle_name = strtolower($splitName['middleName']);
            }
            if (isset($splitName['lastName'])) {
                $names->last_name = strtolower($splitName['lastName']);
            }

            $names->full_name = ucwords(strtolower($name));

            $this->filter_dashName($names);

            $this->setFullNameDetails($names,$is_person);
            $names_temp[$names->full_name] = $names;
        }
        return $names_temp;
    }

    /**
     * [stringToArray if the names is string put it into array]
     * @return [type] [description]
     */
    public function stringToArray(&$names)
    {
        if (!is_array($names)) {
            // validate $names
            $names = [];
            if(is_string($names))
            {
                $names = [$names];
            }
        }
        $names = array_filter($names);
    }

    /**
     * [getOtherNames get all other names for person]
     * @return array of others names
     */
    private function getOtherNames()
    {

        // $criteria = new \Search\Helpers\Bridges\BridgeCriteria();
        // $criteria->compare("is_deleted", 0);
        // $criteria->compare("type", 'names');
        // $dp_bridge = new \Search\Helpers\Bridges\DataPointBridge($this->person['id']);
        // $allNames = $dp_bridge->getAll($criteria);
        $allNames = $this->report->getOtherNames($this->person['id']);
        $allNames = @array_column($allNames, 'main_value');
        return $allNames;

        // GET NAMES FROM OTHER SERVICE
        /*$names = [];
        $inputNames = strtolower($this->person['searched_names']);
        $inputNames = explode(",",$inputNames);
        foreach ($allNames as $namesArray) {
            if (!empty($namesArray['data_json'])) {
                $namedData = json_decode($namesArray['data_json'],1);
                if (!empty($namedData["other_name"])) {
                    if (
                        $namedData["other_name"] == 1 &&
                        !in_array(strtolower($namedData['name']), $inputNames)
                    ) {
                        $names[] = ucwords(strtolower($namedData['name']));
                    }
                }
            }
        }
        return array_filter($names);*/
    }

    private function setFullNameDetails($name, $is_person = false)
    {
        $this->fullNameDetails[$name->full_name] =
        [
            'first_name' => $name->first_name ,
            'middle_name' => $name->middle_name ,
            'last_name' => $name->last_name ,
            'full_name' => $name->full_name ,
            'is_person' => $is_person ,
            'have_middle' => !empty($name->middle_name) ,
            'matchWith' => [] ,
            "is_other_name" => in_array($name->full_name, $this->otherNames) ,
        ];

        if($is_person) {
            $this->fullNameDetails[$name->full_name]['nickName'] = $this->nickNames($name->first_name);
        }
    }

    /**
     * [preparingResult prepare array with data for 2 names]
     * @param  string $name1 [description]
     * @param  string $name2 [description]
     * @return [array]
     * [
     *      'names' => [$name1 , $name2]
     *      '$name1' =>
     *              [
     *                  'first_name' => string
     *                  'middle_name' => string
     *                  'last_name' => string
     *                  'full_name' => string
     *              ]
     *      '$name2' =>
     *              [
     *                  'first_name' => string
     *                  'middle_name' => string
     *                  'last_name' => string
     *                  'full_name' => string
     *              ]
     *      'nameDetails' =>
     *              [
     *                  'score' => [fn,ln,mn,fzn]
     *                  'similarity' => [exactName ,fuzzyName]
     *                  'matchWith' => $name1
     *              ]
     * ]
     */
    private function preparingResult($name1, $name2 = '')
    {
        $result = [];
        $name1 = ucwords(strtolower($name1));
        if (isset($this->fullNameDetails[$name1])) {
            $name1_obj = $this->fullNameDetails[$name1];
            $result[$name1] = [
                'first_name' => $name1_obj['first_name'],
                'middle_name' => $name1_obj['middle_name'],
                'last_name' => $name1_obj['last_name'],
                'full_name' => $name1_obj['full_name'],
            ];
        }

        if (!empty($name2)) {
            $name2 = ucwords(strtolower($name2));
            if (isset($this->fullNameDetails[$name2])) {
                $name2_obj = $this->fullNameDetails[$name2];
                $result[$name2] = [
                    'first_name' => $name2_obj['first_name'] ,
                    'middle_name' => $name2_obj['middle_name'] ,
                    'last_name' => $name2_obj['last_name'] ,
                    'full_name' => $name2_obj['full_name'] ,
                ];
                $names = [];
                $names = [
                    $name2_obj['full_name'] => [
                        'firstName' => $name2_obj['first_name'],
                        'middleName' => $name2_obj['middle_name'],
                        'lastName' => $name2_obj['last_name'],
                    ]
                ];
                $names = new \ArrayIterator($names);
                $nameInfo = loadService("NameInfo");
                $unique = $nameInfo->uniqueName($names);
            }

            if (!empty($this->fullNameDetails[$name1]['matchWith'])) {
                if (isset($this->fullNameDetails[$name1]['matchWith'][$name2])) {
                    $nameDetails = $this->fullNameDetails[$name1]['matchWith'][$name2]['nameDetails'];
                    $score = [];

                    if (in_array("fn", $nameDetails)) {
                        $score[] = "fn";
                    }

                    if (in_array("IN", $nameDetails)) {
                        $score[] = "IN";
                    }

                    if (in_array("fzn", $nameDetails)) {
                        $score[] = "fzn";
                    }

                    if (in_array("mn", $nameDetails)) {
                        $score[] = "mn";
                    }

                    if (in_array("ln", $nameDetails)) {
                        $score[] = "ln";
                    }

                    if (!empty($unique[0]['unique'])) {
                        $score[] = "unq_name";
                    }


                    if (!empty($this->fullNameDetails[$name1]['is_other_name'])) {
                        $result['nameDetails']['other_name'] = 1 ;
                    }

                    $result['nameDetails']['score'] = $score;
                    $result['nameDetails']['similarity'] = $nameDetails['score'] ;
                    $result['nameDetails']['matchWith'] = $name1;
                    $result['names'] = [$name1, $name2];
                }
            }
        }
        return $result;
    }

    /**
     * [filter_dashName description]
     * @param  [type] &$obj_name [description]
     * @return [type]            [description]
     */
    private function filter_dashName(&$obj_name)
    {
        $obj_name->first_name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $obj_name->first_name);

        $obj_name->middle_name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $obj_name->middle_name);

        $obj_name->last_name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $obj_name->last_name);

        $obj_name->first_name = preg_replace("#[^\w\s]#u","",$obj_name->first_name);
        $obj_name->middle_name = preg_replace("#[^\w\s]#u","",$obj_name->middle_name);
        $obj_name->last_name = preg_replace("#[^\w\s\-]#u","",$obj_name->last_name);
    }

    /**
     * [NickNames description]
     * @param [type] $first_name [description]
     */
    private function nickNames($first_name)
    {
        $result = null;

        if (
            isset($this->nick_names[$first_name]) &&
            !empty($this->nick_names[$first_name])
        ) {
            return true;
        }

        if (empty($result)) {
            $result = $this->getNickNamesFromDB($first_name);
        }

        if (empty($result)) {
            return false;
        }

        $this->nick_names[$first_name] = array_map('strtolower', $result);
        return true;
    }

    /**
     * [getRelativeNames description]
     * @return [type] [description]
     */
    private function getRelativeNames()
    {
        // TODO
        /*$prog = \SearchApis::load_progress($this->person['id'],false,"relatives");
        if ($prog['relatives_data'])
        {
            $rels = \CJSON::decode($prog['relatives_data'], 1);

            if (count($rels)) {
                foreach ($rels as $rl) {

                    if (!isset($rl['name'])) continue;

                    $rel_name = explode(" ", strtolower($rl['name']), 3);

                    if (count($rel_name) >= 2)
                    {
                        $full_name = ucwords(strtolower($rl['name']));
                        $names = new \StdClass;
                        $names->first_name = $rel_name[0];
                        $names->middle_name = isset($rel_name[2]) ? $rel_name[1] : "";
                        $names->last_name = isset($rel_name[2]) ? $rel_name[2] : $rel_name[1];
                        $names->full_name = $full_name;
                        $this->setFullNameDetails($names);
                        $this->person_relative[$full_name] = $names;
                    }
                }
            }
        }*/


        $ReportService = loadService('reports');
        $relatives = $ReportService->getReportRelatives();
        $this->person_relative = [];
        foreach ($relatives as $key => $relative) {
            if (!empty($relative['data']['name'])) {
                $this->person_relative[] = $relative['data']['name'];
            }
        }

        return $this->person_relative;
    }

    /**
     * [middleName_Criteria return false if all middle names rejected ]
     * @return [type] [description]
     */
    private function middleNames_Criteria()
    {

        if (
            !empty($this->person_middle_names) &&
            !in_array(true,$this->person_middle_names)
        ) {
            return false;
        }

        return true;
    }

    /**
     * [process_matching_names description]
     * @return [type] [description]
     */
    private function process_matching_names()
    {
        $this->log .= "[NameAnalyzer] Begin Name Analyzer Match \n";
        foreach ($this->person_names as $person_name) {
            foreach ($this->names as $name) {
                $match = false;
                $percent = 0 ;
                $matchMiddleName = false;
                $nameDetails = [];

                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} \n";

                $Criteria = array_filter($this->matchingRules);

                $result = ['middle'=> [],'firstLast' => []];
                foreach ($Criteria as $fun => $value) {
                    $temp = $this->$fun($person_name, $name);
                    if ($temp['match']) {
                        if ($temp['matchMiddleName']) {
                            $result['middle'][] = $temp;
                        } else {
                            $result['firstLast'][] = $temp;
                        }
                    }
                }

                if (!empty($result['middle'])) {
                    extract($result['middle'][0]);
                } elseif (!empty($result['firstLast'])) {
                    extract($result['firstLast'][0]);
                }

                if (!empty($otherName)) {
                    $this->fullNameDetails[$person_name->full_name]['is_other_name'] = 1 ;
                }

                if (!$match) {
                    $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} wth SearchApis :: matchName ..... \n";
                    $this->nameMatch->setFirstName1($person_name->first_name);
                    $this->nameMatch->setMiddleName1($person_name->middle_name);
                    $this->nameMatch->setLastName1($person_name->last_name);
                    $this->nameMatch->setFirstName2($name->first_name);
                    $this->nameMatch->setMiddleName2($name->middle_name);
                    $this->nameMatch->setLastName2($name->last_name);
                    list($match,$percent,$outBecause,$nameStatus) = $this->nameMatch->match();
                }

                //name details
                if ($match) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} is matched with  {$name->full_name} \n";

                    if ($percent == 100) {
                        $nameDetails[] = 'fn';
                        if (!$this->is_relative) {
                            $nameDetails[] = 'IN' ;
                        }
                    } else {
                        $nameDetails[] = 'fzn';
                    }

                    if ($matchMiddleName) {
                        $nameDetails[] = 'mn';
                    }


                    $nameDetails[] = 'ln';

                    $matchWith = $name->full_name;

                    $nameDetails['matchWith'] = $matchWith;

                    $nameDetails['percent'] = $percent;

                    if (isset($nameStatus)) {
                        if ($nameStatus == 1) {
                            $nameDetails['score'] = 'exactName';
                            $this->matched_names["exact"][] =
                                ['name1' => $person_name->full_name , 'name2' => $name->full_name ];

                            if ($matchMiddleName) {
                                //middle name
                                if (
                                    !empty($this->fullNameDetails[$person_name->full_name]['is_other_name']) &&
                                    $this->fullNameDetails[$person_name->full_name]['is_other_name']
                                ) {
                                    // other name
                                    $this->matched_priorty["middle"]["otherName"]["exact"][] =
                                        ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                                } else {
                                    // real name
                                    $this->matched_priorty["middle"]["realName"]["exact"][] =
                                        ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                                }
                            } if (
                                !empty($this->fullNameDetails[$person_name->full_name]['is_other_name']) &&
                                $this->fullNameDetails[$person_name->full_name]['is_other_name']
                            ) {
                                // other name
                                $this->matched_priorty["firstLast"]["otherName"]["exact"][] =
                                    ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                            } else {
                                // real name
                                $this->matched_priorty["firstLast"]["realName"]["exact"][] =
                                    ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                            }
                        } elseif ($nameStatus == 2) {
                            $nameDetails['score'] = 'fuzzyName';
                            $this->matched_names["fuzzy"][] =
                                ['name1' => $person_name->full_name , 'name2' => $name->full_name ];

                            if ($matchMiddleName) {
                                //middle name
                                if (
                                    !empty($this->fullNameDetails[$person_name->full_name]['is_other_name']) &&
                                    $this->fullNameDetails[$person_name->full_name]['is_other_name']
                                ) {
                                    // other name
                                    $this->matched_priorty["middle"]["otherName"]["fuzzy"][] =
                                        ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                                } else {
                                    // real name
                                    $this->matched_priorty["middle"]["realName"]["fuzzy"][] =
                                        ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                                }
                            } if (
                                !empty($this->fullNameDetails[$person_name->full_name]['is_other_name']) &&
                                $this->fullNameDetails[$person_name->full_name]['is_other_name']
                            ) {
                                // other name
                                $this->matched_priorty["firstLast"]["otherName"]["fuzzy"][] =
                                    ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                            } else {
                                // real name
                                $this->matched_priorty["firstLast"]["realName"]["fuzzy"][] =
                                    ['name1' => $person_name->full_name , 'name2' => $name->full_name ];
                            }
                        }
                    }

                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} nameDetails ".print_r($nameDetails,true)." \n";
                } else {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} there is no Matching........... \n";
                }

                if (!empty($nameDetails)) {
                    $this->fullNameDetails[$person_name->full_name]['matchWith'][$name->full_name]["nameDetails"] = $nameDetails ;
                }
            }
        }
        $this->log .= "[NameAnalyzer] End Name Analyzer Match \n";
    }

    /**
     * [isNickFirstName check if first name of person nick names is qual to first name of the other name]
     * @param  [object]  $person_name  person name
     * @param  [object]  $name        other name
     * @return boolean
     */
    private function isFuzzyFirstNameMatch($name, $person_name)
    {
        //send dummy last and middle name to check only on first name
        list($match, $percent, $outBecause, $nameStatus) = $this->nameMatch->match($person_name->first_name,'','ab',$name->first_name, '', 'ab');

        if (
            $percent > 75 ||
            (
                isset($this->fullNameDetails[$person_name->full_name]) &&
                !empty($this->fullNameDetails[$person_name->full_name]['nickName']) &&
                $this->fullNameDetails[$person_name->full_name]['nickName'] &&
                in_array(strtolower($name->first_name) , $this->nick_names[$person_name->first_name])
            )
        ) {
            return true;
        }
        return false;
    }

    /**
     * [isSameName compare two names if they are equal after trim and lowercase return true else false]
     * @param  [string]  $firstName
     * @param  [string]  $secondName
     * @return boolean
     */
    private function isSameName($firstName, $secondName)
    {
        return strtolower(trim($firstName)) === strtolower(trim($secondName));
    }

    /**
     * [firstMiddleLast_Criteria check first middle last name
     *  if there is no middle will check first last]
     * @param  [obj] $person_name [description]
     * @param  [obj] $name        [description]
     * @return [array]              [description]
     */
    private function firstMiddleLast_Criteria($person_name,$name)
    {
        $this->log .= "[NameAnalyzer] Begin Check First Middle Last || First Last Name Criteria \n";

        $match = false;
        $percent = 0 ;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $fuzzyFirstNameMatch = $this->isFuzzyFirstNameMatch($name, $person_name);
        $sameFirstName = $this->isSameName($person_name->first_name, $name->first_name);
        if ($fuzzyFirstNameMatch || $sameFirstName) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have same first name \n";
            //check middle name
            if (
                !empty(trim($person_name->middle_name)) &&
                !empty(trim($name->middle_name))
            ) {
                $this->person_middle_names[$person_name->full_name] = false;
                if (strtolower(trim($person_name->middle_name)) === strtolower(trim($name->middle_name))) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same middle name \n";

                    $match = true;
                    $this->person_middle_names[$person_name->full_name] = true ;
                    $matchMiddleName = true;
                    $percent = 100;
                }
            }

            //check last name
            if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))) {
                $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same last name  \n";
                $match = true ;
                $percent = 100;
                $nameStatus = 1;
                if ($fuzzyFirstNameMatch && !$sameFirstName) {
                    $percent = 70;
                    $nameStatus = 2 ;
                }
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} faild have different last name  \n";
                $match = false ;
                $percent = 0;
            }
        }
        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
        ];
    }


    /**
     * [fuzzyName_Criteria fuzzy name]
     * @param  [type] $person_name [description]
     * @param  [type] $name        [description]
     * @return [type]              [description]
     */
    private function fuzzyName_Criteria($person_name, $name)
    {
        $this->log .= "[NameAnalyzer] Begin Check Fuzzy Name Criteria \n";

        $match = false;
        $percent = 0 ;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name}  have different first name .... \n";
        if (
            isset($this->fullNameDetails[$person_name->full_name]) &&
            !empty($this->fullNameDetails[$person_name->full_name]['nickName']) &&
            $this->fullNameDetails[$person_name->full_name]['nickName'] &&
            in_array( strtolower($name->first_name) , $this->nick_names[$person_name->first_name])
        ) {
            $this->log .= "[NameAnalyzer] {$name->first_name} is Nickname   \n";

            if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))) {
                $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have same last name with Nickname \n";

                $match = true;
                $percent = 70;
                $nameStatus = 2 ;
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} faild have different last name with Nickname \n";
            }
        }
        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
        ];
    }


    /**
     * [firstLast_lastFirst match first last with last first ]
     * @param  [type] $person_name [description]
     * @param  [type] $name        [description]
     * @return [type]              [description]
     */
    private function firstLast_lastFirst_Criteria($person_name,$name)
    {
        $this->log .= "[NameAnalyzer] Begin Check First Last :: Last First Criteria \n";

        $match = false;
        $percent = 0 ;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->first_name)) ) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have last = first name \n";

            //check last name
            if (strtolower(trim($person_name->first_name)) === strtolower(trim($name->last_name)) ) {
                $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} first = last name  \n";
                $match = true ;
                $percent = 100;
                $nameStatus = 1 ;
                $otherName = true;
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} first != last name  \n";
                $match = false;
                $percent = 0;
            }
        }
        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
        ];
    }

    /**
     * [F_MiddleLast_Criteria F. Middle Last or F. Last ]
     * @param [type] $person_name [description]
     * @param [type] $name        [description]
     */
    private function F_MiddleLast_Criteria($person_name,$name)
    {
        $this->log .= "[NameAnalyzer] Begin Check F. Middle Last Criteria \n";

        if(strtolower(trim($person_name->last_name)) != strtolower(trim($name->last_name)))
            return;

        $match = false;
        $percent = 0 ;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $first_char = substr(strtolower(trim($person_name->first_name)), 0, 1);
        $first_name = strtolower(trim($name->first_name));

        if ($first_char  === $first_name || $first_char."."  === $first_name) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} first character =  first name \n";
            //check middle name
            if (
                !empty(trim($person_name->middle_name)) &&
                !empty(trim($name->middle_name)) &&
                strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))
            ) {
                $this->person_middle_names[$person_name->full_name] = false ;
                if (strtolower(trim($person_name->middle_name)) === strtolower(trim($name->middle_name))) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same middle name \n";

                    $match = true;
                    $this->person_middle_names[$person_name->full_name] = true ;
                    $matchMiddleName = true;
                    $percent = 100;
                }
            }

            //check last name
            if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))) {
                $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same last name  \n";
                $match = true ;
                $percent = 100;
                $nameStatus = 1 ;
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} faild have different last name  \n";
                $match = false ;
                $percent = 0;
            }
        }

        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
        ];
    }

    /**
     * [first_M_Last_Criteria First M. Last]
     * @param [type] $person_name [description]
     * @param [type] $name        [description]
     */
    private function first_M_Last_Criteria($person_name, $name)
    {
        $this->log .= "[NameAnalyzer] Begin Check First M. Last Criteria \n";

        $match = false;
        $percent = 0 ;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $fuzzyFirstNameMatch = $this->isFuzzyFirstNameMatch($name, $person_name);
        $sameFirstName = $this->isSameName($person_name->first_name, $name->first_name);
        if ($fuzzyFirstNameMatch || $sameFirstName) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have same first name \n";
            //check middle name
            if (!empty(trim($person_name->middle_name)) && !empty(trim($name->middle_name))
                //removed for case Rebecca Anne Bull => Rebecca Bull Reed
                /*&& strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))*/
            ) {
                $Middle_char = substr( strtolower(trim($person_name->middle_name)), 0, 1);
                $middle_name = strtolower(trim($name->middle_name ));

                $Middle_char_2 = substr( strtolower(trim($name->middle_name)) , 0,1);
                $middle_name_2 = strtolower(trim($person_name->middle_name));
                if (
                    $Middle_char  === $middle_name ||
                    $Middle_char."."  === $middle_name ||
                    $Middle_char_2 === $middle_name_2 ||
                    $Middle_char_2."."  === $middle_name_2
                ) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} middle charcter =  middle name \n";

                    $match = true ;
                    $this->person_middle_names[$person_name->full_name] = true ;
                    $matchMiddleName = true;
                    $percent = 100;
                }
            }

            //check last name
            if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->last_name))) {
                $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same last name  \n";
                $match = true;
                $percent = 100;
                $nameStatus = 1 ;
                if ($fuzzyFirstNameMatch && !$sameFirstName) {
                    $percent = 70;
                    $nameStatus = 2 ;
                }
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} faild have different last name  \n";
                $match = false ;
                $percent = 0;
            }
        }

        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
       ];
    }

    /**
     * [firstMiddle_L check first middle L. || First L. ]
     * @param  [obj] $person_name [description]
     * @param  [obj] $name        [description]
     * @return [array]              [description]
     */
    private function firstMiddle_L_Criteria($person_name,$name)
    {
        $this->log .= "[NameAnalyzer] Begin Check First Middle L. || First L. Criteria \n";

        $match = false;
        $percent = 0;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $fuzzyFirstNameMatch = $this->isFuzzyFirstNameMatch($name, $person_name);
        $sameFirstName = $this->isSameName($person_name->first_name, $name->first_name);
        if ($fuzzyFirstNameMatch || $sameFirstName) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have same first name \n";
            //check middle name
            if (
                !empty(trim($person_name->middle_name)) &&
                !empty(trim($name->middle_name))
            ) {
                $this->person_middle_names[$person_name->full_name] = false;
                if (strtolower(trim($person_name->middle_name)) === strtolower(trim($name->middle_name))) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have same middle name \n";
                    $match = true ;
                    $this->person_middle_names[$person_name->full_name] = true;
                    $matchMiddleName = true;
                    $percent = 100;
                }
            }

            //check last name
            $last_char = substr( strtolower(trim($person_name->last_name)) , 0,1);
            $last_name = strtolower(trim($name->last_name ));
            if ($last_char  === $last_name || $last_char."."  === $last_name) {
                $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} last character = last name  \n";
                $match = true;
                $percent = 100;
                $nameStatus = 1;
                if ($fuzzyFirstNameMatch && !$sameFirstName) {
                    $percent = 70;
                    $nameStatus = 2;
                }
            } else {
                $this->log .= "[NameAnalyzer] Matching {$person_name->full_name} - {$name->full_name} faild have different last name  \n";
                $match = false ;
                $percent = 0;
            }
        }

        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
       ];
    }

    /**
     * [firstMiddle_Criteria check first last with first middle
     *  if there is no middle will check first last]
     * @param  [obj] $person_name [description]
     * @param  [obj] $name        [description]
     * @return [array]              [description]
     */
    private function firstMidde_firstLast_Criteria($person_name,$name)
    {
        $this->log .= "[NameAnalyzer] Begin Check First Middle || First Last Criteria \n";

        $match = false;
        $percent = 0;
        $matchMiddleName = false;
        $nameStatus = false;
        $otherName = false;

        $fuzzyFirstNameMatch = $this->isFuzzyFirstNameMatch($name, $person_name);
        $sameFirstName = $this->isSameName($person_name->first_name, $name->first_name);
        if ($fuzzyFirstNameMatch || $sameFirstName) {
            $this->log .= "[NameAnalyzer]  {$person_name->full_name} - {$name->full_name} have same first name \n";
            // check middle name
            if (!empty(trim($name->middle_name))) {
                if (strtolower(trim($person_name->last_name)) === strtolower(trim($name->middle_name))) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have last name =  middle name \n";

                    $match = true ;
                    $percent = 100;
                        $nameStatus = 1 ;
                    if ($fuzzyFirstNameMatch && !$sameFirstName) {
                        $percent = 70;
                        $nameStatus = 2 ;
                    }
                }
            } elseif(!empty(trim($person_name->middle_name))) {
                if (strtolower(trim($person_name->middle_name)) === strtolower(trim($name->last_name))) {
                    $this->log .= "[NameAnalyzer] {$person_name->full_name} - {$name->full_name} have middle name =  last name \n";

                    $match = true ;
                    $percent = 100;
                    $nameStatus = 1;
                    if ($fuzzyFirstNameMatch && !$sameFirstName) {
                        $percent = 70;
                        $nameStatus = 2 ;
                    }
                }
            }
        }

        return [
            'match'             => $match,
            'percent'           => $percent,
            'otherName'         => $otherName,
            'nameStatus'        => $nameStatus,
            'matchMiddleName'   => $matchMiddleName,
       ];
    }

    /**
     * @return string
     */
    private function clean($string)
    {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z-]/', '', $string); // Removes special chars.

       return preg_replace('/-+/', '', $string); // Replaces multiple hyphens with single one.
    }

    private function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    /**
     * [matchingOneWord description]
     * @return [type] [description]
     */
    private function matchingOneWord($name)
    {
        $this->log .= "[NameAnalyzer] Begin Match One Word Name : $name  \n";
        $name = strtolower( $this->clean($name));
        foreach ($this->person_names as $person_name) {
            $middle_char = substr(strtolower(trim($person_name->middle_name)), 0, 1);
            $firstLast = $person_name->first_name . $person_name->last_name;
            $firstMiddleLast = $person_name->first_name . $person_name->middle_name .$person_name->last_name;
            $first_M_Last = $person_name->first_name .$middle_char . $person_name->last_name;
            $firstMiddle = $person_name->first_name . $person_name->middle_name;

            if (!empty($person_name->middle_name)) {
                $this->log .= "[NameAnalyzer] Begin Check Person Middle Name ($person_name->middle_name) with one word (if there middle name / char in username)  \n";
                $result = str_replace(strtolower($person_name->first_name),"[first]",$name);
                if (stripos($result,"[first]") !==  false) {
                    $this->log .= "[NameAnalyzer] The Person First Name ($person_name->first_name) in the one word \n";
                    $result = str_replace(strtolower($person_name->last_name),"[/last]",$result);
                    if (stripos($result,"[/last]") !== false) {
                        $this->log .= "[NameAnalyzer] The Person Last Name ($person_name->last_name) in the one word \n";
                        $result_temp = $result;
                        $result = $this->get_string_between($result,"[first]","[/last]");
                        $is_empty = str_replace(["[first]","[/last]",$result],"",$result_temp);
                        if (empty($is_empty) && !empty($result)) {
                            if (
                                strtolower($middle_char) == $result ||
                                strtolower($person_name->middle_name) == $result
                            ) {
                                $this->log .= "[NameAnalyzer] The Person Middle Name or Middle Char ($person_name->middle_name or $middle_char ) in the one word \n";
                                $this->log .= "[NameAnalyzer] Person Middle Name Check Success \n  End Match One Word Name : $name \n";
                                return true;
                            } else {
                                $this->log .= "[NameAnalyzer] The Person Middle Name or Middle Char ($person_name->middle_name or $middle_char ) not in the one word \n";
                                $this->log .= "[NameAnalyzer] Person Middle Name Check Faild \n  End Match One Word Name : $name \n";
                                $this->person_middle_names[$person_name->full_name] = false ;
                            }
                        }
                    }
                }
            }

            $this->log .= "[NameAnalyzer]  End Match One Word Name : $name \n";

            if (
                $firstLast == $name ||
                $firstMiddleLast == $name ||
                $firstMiddle == $name ||
                $person_name->first_name == $name ||
                $person_name->middle_name == $name ||
                $person_name->last_name == $name ||
                $first_M_Last == $name
            ){
                $this->log .= "[NameAnalyzer] One Word Name Check successed \n";
                return true;
            }
        }
        return false;
    }


    public function setIsRelative($status)
    {
        $this->is_relative = $status ;
    }

    public function stop_FirstMiddleLast_Criteria()
    {
        $this->matchingRules['firstMiddleLast_Criteria'] = false;
    }

    public function stop_fuzzyName_Criteria()
    {
        $this->matchingRules['fuzzyName_Criteria'] = false;
    }

    public function stop_firstLast_lastFirst_Criteria()
    {
        $this->matchingRules['firstLast_lastFirst_Criteria'] = false;
    }

    public function stop_F_MiddleLast_Criteria()
    {
        $this->matchingRules['F_MiddleLast_Criteria'] = false;
    }

    public function stop_firstMiddle_L_Criteria()
    {
        $this->matchingRules['firstMiddle_L_Criteria'] = false;
    }

    public function stop_first_M_Last_Criteria()
    {
        $this->matchingRules['first_M_Last_Criteria'] = false;
    }

    public function stop_firstMidde_firstLast_Criteria()
    {
        $this->matchingRules['firstMidde_firstLast_Criteria'] = false;
    }

    public function setDisableMiddlenameCriteria($status = true)
    {
        $this->disableMiddlenameCriteria = $status;
    }

    public function filterNamesFromResults($progress_data_names)
    {
        if (!is_array($progress_data_names)) {
            return [];
        }
        $names = [];
        foreach ($progress_data_names as $nameArr) {
            if (empty($nameArr['res']) || isset($nameArr['res']) && $nameArr['res'] == 0) {
                $names[] = $nameArr ;
            }
        }
        return $names ;
    }

    private function ignoredName($name)
    {
        $ignoredNames = [
            "\ud83c\udf38" => "",
            "ud83cudf38"   => "",
            "\ud83d\uddfc" => "",
            "ud83duddfc"   => "",
        ];
        return strtr($name, $ignoredNames);
    }

    /**
     * [Utf8_ansi convert utf8 html into ansi]
     * @param string $valor [description]
     */
    private function Utf8_ansi($valor = '')
    {
        $utf8_ansi2 = [
            "\u00c0" =>"À",
            "\u00c1" =>"Á",
            "\u00c2" =>"Â",
            "\u00c3" =>"Ã",
            "\u00c4" =>"Ä",
            "\u00c5" =>"Å",
            "\u00c6" =>"Æ",
            "\u00c7" =>"Ç",
            "\u00c8" =>"È",
            "\u00c9" =>"É",
            "\u00ca" =>"Ê",
            "\u00cb" =>"Ë",
            "\u00cc" =>"Ì",
            "\u00cd" =>"Í",
            "\u00ce" =>"Î",
            "\u00cf" =>"Ï",
            "\u00d1" =>"Ñ",
            "\u00d2" =>"Ò",
            "\u00d3" =>"Ó",
            "\u00d4" =>"Ô",
            "\u00d5" =>"Õ",
            "\u00d6" =>"Ö",
            "\u00d8" =>"Ø",
            "\u00d9" =>"Ù",
            "\u00da" =>"Ú",
            "\u00db" =>"Û",
            "\u00dc" =>"Ü",
            "\u00dd" =>"Ý",
            "\u00df" =>"ß",
            "\u00e0" =>"à",
            "\u00e1" =>"á",
            "\u00e2" =>"â",
            "\u00e3" =>"ã",
            "\u00e4" =>"ä",
            "\u00e5" =>"å",
            "\u00e6" =>"æ",
            "\u00e7" =>"ç",
            "\u00e8" =>"è",
            "\u00e9" =>"é",
            "\u00ea" =>"ê",
            "\u00eb" =>"ë",
            "\u00ec" =>"ì",
            "\u00ed" =>"í",
            "\u00ee" =>"î",
            "\u00ef" =>"ï",
            "\u00f0" =>"ð",
            "\u00f1" =>"ñ",
            "\u00f2" =>"ò",
            "\u00f3" =>"ó",
            "\u00f4" =>"ô",
            "\u00f5" =>"õ",
            "\u00f6" =>"ö",
            "\u00f8" =>"ø",
            "\u00f9" =>"ù",
            "\u00fa" =>"ú",
            "\u00fb" =>"û",
            "\u00fc" =>"ü",
            "\u00fd" =>"ý",
            "\u00ff" =>"ÿ"
        ];
        return strtr($valor, $utf8_ansi2);
    }

    private function filterName($name)
    {
        // convert \u00e9 into é related to Task#11250 .
        $name = $this->Utf8_ansi($name);
        $name = $this->ignoredName($name);

        // Remove all unicode
        $name = preg_replace("/\\\\u\\d{4}/i", "", $name);
        $name = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $name);

        $name = preg_replace("#[^\w\s\-]#u", "", $name);
        $name = preg_replace("#u(\d+)#u", "", $name);
        return trim($name);
    }

    private function splitName($name)
    {
        $splittedNameIterator = loadService('nameInfo')->nameSplit(new \ArrayIterator([$name]));
        $splittedNameArray = iterator_to_array($splittedNameIterator);
        return $splittedNameArray[0]["splitted"][0]??[];
    }

    public function getNickNamesFromDB($name)
    {
       $nickNamesIterator = loadService('nameInfo')->nickNames(new \ArrayIterator([$name]));
       $nickNamesArray = iterator_to_array($nickNamesIterator);
       return $nickNamesArray[0]['nickNames']??[];
    }
}

