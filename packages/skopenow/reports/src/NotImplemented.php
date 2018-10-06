<?php

/**
*
*/
class NotImplemented
{
    public static function setCombinationAndSaveUrl($parameters)
    {
        ## This is Parameters Rules..
        $param_rules = array(
                'combination_source'    => array('required'=> true),
                'combinationUrl'        => array('required'=> true),
                'comb_fields'           => array('required'=> true),
                'person'                => array('required'=> true),
                'main_source'           => array('defaultValue'=> false),
                'source'                => array('defaultValue'=> false),
                'facebookPraimary'      => array('defaultValue'=> false),
                'setMatched'            => array('defaultValue'=> true),
                'result'                => array('defaultValue'=> true),
                //'setScoreIdentFire'  => array('defaultValue'=> false),
            );
        ## // ..

        ## this is for validate Parmiters with rules
        $params  = SearchApis::parametersCheck($parameters, $param_rules);
        ## extract all paramiters from $params.
        extract($params);

        $combs_fields = array();
        if (isset($comb_fields[1])) {
            $combs_fields = $comb_fields ;
        } else {
            $combs_fields[1] = $comb_fields;
        }

        $combinationId = $this->combinationsService->store($combination_source);
        $combinationService->addCombinationLevel($combinationId, $combinationUrl, $combFields[1]);

        // SearchApis::store_combination(null,null,$combination_source, $combinationUrl, null, null, $combs_fields, $person, false,"",null,$getCombID);

        $check_status = ["name" => 1, "location" => 1] ;
        if ($setMatched) {
            if (array_key_exists('ln', $combs_fields[1])) {
                $check_status['name'] =1;
                $check_status['nameDetails'] = array('fn', 'ln', 'IN');
                if (!empty($combs_fields['mn'])) {
                    array_push($check_status['nameDetails'], "mn");
                }
            }

            if (array_key_exists('ct', $combs_fields[1]) || array_key_exists('st', $combs_fields[1])) {
                $check_status['location'] = 1;
                $check_status['found_location'] = 1;
                $check_status['locationDetails'] = array();
                if (isset($result['location'])) {
                    $result['location'] = trim($result['location']);
                }
                if (!empty($result['location'])) {
                    $check_status['locationDetails'] = array('exct','st');
                }
            }

            if (array_key_exists('sc', $combs_fields[1])) {
                $check_status['school'] = 1;
                $check_status['schoolDetailes'] = ['sc'];
            }

            if (array_key_exists('cm', $combs_fields[1])) {
                $check_status['work'] = 1;
                $check_status['workDetailes'] = ['cm'];
            }

            if (array_key_exists('un', $combs_fields[1])) {
                $check_status['usernameVerified'] = 1;
            }
        }

        $check_status['reverseVerified'] =1;

        $check_status = array_merge($check_status, $combs_fields[1]);

        if (!$main_source) {
            $main_source = $combination_source;
        }

        if (!$source) {
            $source = $main_source;
        }

        $combs_fields = serialize($combs_fields);
        $source = preg_replace("#^([^_]*)_#", '', $source);
        $main_source = preg_replace("#^([^_]*)_#", '', $main_source);

        $res_temp = array(
            "source" => $source,
            "main_source" => $main_source,
            "type" => 'result',
            "raw_type" => 'result',
            "content" => $combinationUrl,
            "process_url" => true,
            "combination_id" => $getCombID,
            "combination_level" => 1,
            "combs_fields" => $combs_fields,
            "is_relative" => 0,
            "check_status" => $check_status,
            "first_name" => (isset($combs_fields[1]['fn']))?$combs_fields[1]['fn']:null,
            'score_result_count'=> 1
        );

        $resultId = $this->resultsService->store($res_temp);

        if ($resultId) {
            if ($facebookPraimary) {
                SearchApis::setAsPrimary($person, $res_temp['content'], ['location'=>true], $res_id, ['rltv']);
            }

            ## get the work experiences and schools of the added result .
            if ($main_source == "linkedin" || $main_source == "facebook") {
                $result = $this->resultsService->getById($res_id);

                if (!empty($result)) {
                    if ($main_source == "linkedin") {
                        $searchApis->storeLinkedinProfileDataInProgress($combinationUrl, $person, $result, $getCombID);
                    }
                    if ($main_source == "facebook") {
                        $searchApis->storeFacebookMoreInfo($combinationUrl, $person, $result);
                    }
                }
            }
        }
    }

    protected function runReverseTloxp($reverseResult)
    {
        // filter location
        $reverseResult['city'] = SearchHelpers::doFilterLocationAreas($reverseResult['city']);
        $reverseResult['state'] = SearchHelpers::doFilterLocationAreas((!empty($reverseResult['state'])?$reverseResult['state']:$reverseResult['city']));
        $reverseResult['location'] = SearchHelpers::doFilterLocationAreas($reverseResult['location']);
        $reverseResult['address'] = SearchHelpers::doFilterLocationAreas($reverseResult['address']);

        // if active
        if (true) {
            $model = self::prepare_person_before_reverse($reverseResult, $model);
            // save to tloxp and piple
            $sType = new Tloxp();
            $sType->active = $sType->test();
            if (!$sType->active) {
                $sType = new Automation\pipl($model);
            } else {
                $sType->person = $model;
            }
            $reverseResults = $sType->result;
            return $reverseResults;
        }
    }

    protected function prepareReverseResults($reverseResults, $pendingResultId)
    {
        foreach ($reverseResults as $key => $value) {
            $reverseResults[$key]["pend_id"] = $pendingResultId ;
            if (count($reverseResults[$key]['results']) == 1) {
                if (!empty($reverseResults[$key]['results'][0]['positions'])){
                    $reverseResults[$key]['positions'] = $reverseResults[$key]['results'][0]['positions'] ;
                }
                if (!empty($reverseResults[$key]['results'][0]['education'])) {
                    $reverseResults[$key]['education'] = $reverseResults[$key]['results'][0]['education'] ;
                }
            }
            unset($reverseResults[$key]['results']);
            $reverseResults[$key] = $this->preparePersonInfo($reverseResults[$key]);
        }

        return $reverseResults;
    }


   /**
    * preparePersonInfo to prepare how the information will shows
    * @param  array  $data Add all data you want to prepare, support  name , location, phone, email
    * @return [type]       return data prepared
    */
    protected function preparePersonInfo(array $data)
    {
        $output = [];
        ## Name
        if (isset($data['name']) && is_array($data['name'])) {
            if (isset($data['displayName'])) {
                $output['displayName'] = ucwords($data['displayName']);
            }

            $output['name'] = array_map(
                function ($_v) {
                        return ucwords($_v);
                }, $data['name']);
        } elseif (isset($data['name'])) {
            $output['name'] = ucwords($data['name']);
        }

        ### prepare each part of name
        if (isset($data['first_name'])) {
            $output['first_name'] = ucfirst($data['first_name']);
        }

        if (isset($data['middle_name'])) {
            $output['middle_name'] = ucfirst($data['middle_name']);
        }

        if (isset($data['last_name'])) {
            $output['last_name'] = ucfirst($data['last_name']);
        }
        ## // Name ..

        ## location
        if (isset($data['location']) && is_array($data['location'])) {
            foreach ($data['location'] as $_l) {
                $ex_location = explode(',', $_l['location'], 2);
                $l_output = ucwords($ex_location[0]);
                if (isset($ex_location[1])) {
                    $_state = strlen($ex_location[1]) > 3? ucwords($ex_location[1]):strtoupper($ex_location[1]);

                    $l_output .= ', '. $_state;
                }
                $l_output = preg_replace('#\s+#', ' ', $l_output);
                $_locationDetailes['location'] = $l_output;
                if (isset($_l['zip'])) {
                    $_locationDetailes['zip'] = $_l['zip'];
                }
                $output['location'][] = $_locationDetailes;
            }
        } elseif (!empty($data['location'])) {
            $ex_location = explode(',', $data['location']);
            $output['location'] = ucwords($ex_location[0]);
            if (isset($ex_location[1])) {
                $_state = strlen($ex_location[1]) > 3? ucwords($ex_location[1]):strtoupper($ex_location[1]);
                $output['location'] .= ', '. $_state;
            }
            $output['location']  = preg_replace('#\s+#', ' ', $output['location']);
        }

        ## phone
        if (isset($data['phone']) && is_array($data['phone'])) {
            $data['phone'] = array_map(function ($_p) {
                 return SearchApis::phone_format($_p, true);
            }, $data['phone']);
        } elseif (isset($data['phone'])) {
            $output['location'] = SearchApis::phone_format($data['phone'], true);
        }

        ## email
        if (isset($data['emails']) && is_array($data['emails'])) {
            $data['emails'] = array_map(function ($_em) {
                 return ucfirst(strtolower($_em));
            }, $data['emails']);
        } elseif (isset($data['emails'])) {
            $output['emails'] = ucfirst(strtolower($data['emails']));
        }

        ## relatives
        if (isset($data['relatives'])) {
            $output['relatives'] = $data['relatives'];
        }

        ## other names
        if (isset($data['other_names'])) {
            $output['other_names'] = $data['other_names'];
        }
        ## addresses
        if (isset($data['addresses'])) {
            $output['addresses'] = $data['addresses'];
        }
        $output = array_merge($data, $output);

        return $output;
    }

    protected function logReverseResults($foundResults, $user_id = 1)
    {
        $result_count =0;
        $reverse_types =[];
        $reverse_sources =[];
        $reverse_log = new \ReverseLog;
        $reverse_log->user_id = $user_id;
        ## loop through results
        if (is_array($foundResults)) {
            foreach ($foundResults as $res) {
                if (is_array($res)) {
                    ## set types and result count
                    $result_count = $result_count + $res['#'];
                    if (!isset($res['searchType'])) {
                        $value['searchType'] = "tloxp";
                    } elseif (is_array($res['searchType'])) {
                        foreach ($res['searchType'] as $search_type) {
                            $reverse_types[] =$search_type;
                        }
                    } else {
                        $reverse_types[] = $res['searchType'];
                    }
                }
            }
        }

        $reverse_log->reverse_type = \CJSON::encode(array_unique($reverse_types));
        $reverse_log->result_count = $result_count;
        $reverse_log->save();
    }


    protected function extractNamesParts($names)
    {
        $firstNames = array();
        $middleNames = array();
        $lastNames = array();

        // load name splitter service here
        $splittedNames = $this->nameInfoService->nameSplit(new \ArrayIterator($names));

        foreach ($splittedNames as $splittedName) {
            if ($splittedNameDetails = $splittedName['splitted'][0]) {
                if (!empty($splittedNameDetails["firstName"])) {
                    $firstNames[] = $splittedNameDetails["firstName"];
                }

                if (!empty($splittedNameDetails["middleName"])) {
                    $middleNames[] = $splittedNameDetails["middleName"];
                }

                if (!empty($splittedNameDetails["lastName"])) {
                    $lastNames[] = $splittedNameDetails["lastName"];
                }
            }
        }

        return [
            'first_names' => $firstNames,
            'middle_names' => $middleNames,
            'last_names' => $lastNames,
        ];
    }


    protected function s($reverseResults)
    {
        foreach ($reverseResults as $k => $v) {
            $reverseResults[$k]["reverse_flag"] = TRUE;
        }

        if (count($foundResults)==1) {
            // set the other names
            foreach ($foundResults as $k => $v) {
                $foundResults[$k]["other_names"][] = ["first_name"=>$v["first_name"], "middle_name" => $v["middle_name"], "last_name" => $v["last_name"]];
            }

            if (SearchApis::reverse_save($foundResults,$model)) {
                $found_result = true ;
                $return['type'] = "full";
                $return['id'] = encryptID($model->id);
            }

            if (isset($foundResults[0]['results'])) {
                $results = $foundResults[0]['results'];

                foreach ($results as $value) {
                    $params = [
                        'combination_source'=> 'reverse_'.$value['source'].'_custom',
                        'combinationUrl'    => (!empty($value['link']))?$value['link'] : "",
                        'main_source'       => $value['source'],
                        'source'            => $value['source'].'(reverse)',
                        'comb_fields'       => isset($value['comb_fields'])?$value['comb_fields']:[],
                        'person'            => $model,
                        'setMatched'        => true,
                        'result'            =>  $value,
                    ];

                    if ($value['source'] == "facebook") {
                        $params['facebookPraimary'] = true;
                    }

                    SearchApis::setCombinationAndSaveUrl($params);
                }
            }
        }
    }
}
