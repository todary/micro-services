<?php

if (!function_exists('getSummary')) {
    //$id, $temp, $include_search_fields = true, $cache = 30, $assoc_profile = false, $is_api = false
    function getSummary($progress)
    {
        $cache = 0;
        $include_search_fields = true;

        $reportService = loadService('reports');
        $report = $reportService->getReport();

        $res = [];
        if ($progress) {
            $res = array();
            $id = $report['id'];
            $progress['person_id'] = $report['id'];

            $res['id'] = encryptID($id);

            $uss = array();
            if ($progress && isset($progress['added_usernames_data'])) {
                $added_usernames = json_decode($progress['added_usernames_data'], true);
                if (is_array($added_usernames)) {
                    foreach ($added_usernames as $added_username) {
                        $uss[] = $added_username['username'];
                    }
                }
            }
            $res['usernames'] = count($uss);

            $res['photos'] = (int) @$progress['photos'];

            $criteria = \SearchApis::liveEditResultCriteria($report['id'], false, true, $report['is_paid']);

            $bridge_criteria = new Search\Helpers\Bridges\BridgeCriteria();
            $bridge_criteria->mergeWith($criteria);
            $result_bridge = new Search\Helpers\Bridges\ResultsBridge($report);
            $res['results'] = $result_bridge->count($bridge_criteria, $cache);

            $res['resultsFilter'] = $report['filters'] != null ? json_decode($report['filters'], true) : null;
            if (CSearch::MAX_RESULTS) {
                $res['results'] = min(CSearch::MAX_RESULTS, $res['results']);
            }

            /*
             * get count yellowpages results
             */
            /*$yellowpagesCriteria_bridge_criteria = new Search\Helpers\Bridges\BridgeCriteria();
            $yellowpagesCriteria_bridge_criteria->compare("main_source", 'yellowpages');
            $yellowpagesCriteria_bridge_criteria->mergeWith($criteria);
            $result_bridge = new Search\Helpers\Bridges\ResultsBridge($report);
            $oldYResults = $result_bridge->count($yellowpagesCriteria_bridge_criteria);
            if ($oldYResults) {
                $res['results'] -= ($oldYResults - 1);
            }*/

            $res['is_paid'] = !!$report['is_paid'];
            $res['is_charge'] = !!$report['is_charge'];

            if (isset($progress['assoc_profiles_data']) && $progress['assoc_profiles_data']) {
                $res['assocProfilesData'] = json_decode($progress['assoc_profiles_data'], true);
            } else {
                $res['assocProfilesData'] = array();
            }

            $res['locations'] = array();
            if (!empty($progress['addresses_data'])) {
                if (null == $progress['addresses_data'] || $progress['addresses_data'] == 'null') {
                    $progress['addresses_data'] = '[]';
                }

                $res['locations'] = json_decode($progress['addresses_data'], true);
                if (is_array($res['locations'])) {
                    $res['locations'] = sortProgressByIndex($res['locations']);
                    $res['locations'] = SearchApis::sortBySmallCity($res['locations']);
                }
                $res['locations'] = CSearch::formateLocations($res['locations']);
            }

            $res['phones'] = array();
            if (!empty($progress['phones_data'])) {
                $res['phones'] = json_decode($progress['phones_data'], true);
            }

            $runOptions = false;
            $profiles = array();

            $bridge_criteria->compare("t.has_profile_data", 1);
            $bridge_criteria->with = ['msource'];

            $result_bridge = new Search\Helpers\Bridges\ResultsBridge($report);
            $profilesData = $result_bridge->getAll($bridge_criteria);

            $keysProfiles = [];
            if ($profilesData) {
                foreach ($profilesData as $key => $profileData) {
                    $site_name = GetSiteName($profileData->content);
                    if ($site_name == "PLUS.GOOGLE") {
                        $profile_source = "Google+";
                    } elseif ($site_name == "GET.GOOGLE") {
                        $profile_source = "Picasa";
                    } elseif ($site_name == "ANGEL") {
                        $profile_source = "AngelList";
                    } else {
                        $profile_source = ucfirst(strtolower($site_name));
                    }

                    if (stripos($profile_source, '500px') !== false) {
                        $profile_source_icon = 'ppc500px';
                    } elseif (stripos($profile_source, '8TRACKS') !== false) {
                        $profile_source_icon = 'icon8tracks';
                    } elseif (stripos($profile_source, 'LAST') !== false) {
                        $profile_source_icon = 'lastfm';
                    } elseif (stripos($profile_source, 'ABOUT') !== false) {
                        $profile_source_icon = 'aboutme';
                    } elseif (stripos($profile_source, 'Google+') !== false) {
                        $profile_source_icon = 'googleplus';
                    } elseif (stripos($profile_source, 'PLUS.GOOGLE') !== false) {
                        $profile_source_icon = 'googleplus';
                    } else {
                        $profile_source_icon = $profile_source;
                    }

                    if ($profileData->main_source == 'picasaweb') {
                        $profileData->main_source = 'picasa';
                    }

                    $profileDataArray = array(
                        "is_relative" => $profileData->is_relative,
                        "res" => $profileData->id,
                        "comb_level" => $profileData->combination_level,
                        "image" => $profileData->profile_image,
                        "source" => $profile_source,
                        "source_icon" => $profile_source_icon,
                        "filter_source" => $profileData->main_source,
                        "name" => $profileData->profile_name,
                        "username" => $profileData->profile_username,
                        "url" => $profileData->content,
                        "comb_rank" => $profileData->comb_rank,
                        "rank" => (INT) $profileData->rank,
                        "first_name" => $profileData->first_name,
                        "flags" => $profileData->flags,
                    );

                    $profiles[] = $profileDataArray;
                    $keysProfiles['res_' . $profileDataArray['res']] = $key;
                }
            }
            $res['profiles'] = $profiles;

            $res['relatives'] = array();
            if (!empty($progress['relatives_data'])) {
                $res['relatives'] = json_decode($progress['relatives_data'], true);
            }

            $res['other_names'] = array();
            $other_names_list = [];
            $other_names_list = $report['names'];
            if (!empty($progress['names_data'])) {
                $other_names = json_decode($progress['names_data'], true);
                foreach ($other_names as $key => $name) {
                    if (isset($name['other_name']) && $name['other_name'] && empty($name['extractedFromProfile'])) {
                        if (!in_array($name["name"], $other_names_list)) {
                            $res['other_names'][$key] = $name;
                            $other_names_list[] = $name["name"];
                        }
                    }
                }
                $res['other_names'] = normalizeDataArray($res['other_names']);
            }

            $res['emails'] = array();
            if (!empty($progress['emails_data'])) {
                $res['emails'] = json_decode($progress['emails_data'], true);
            }

            $res['websites'] = array();
            $website_list = [];
            if (!empty($progress['websites_data'])) {
                $websites_data = json_decode($progress['websites_data'], true);
                foreach ($websites_data as $website) {
                    $url = trim(StrToLower($website["url"]));
                    if (!empty($url)) {
                        if (!in_array($url, $website_list)) {
                            $res['websites'][] = $website;
                            $website_list[] = $url;
                        }
                    }
                }
            }
            $res['websitesLength'] = count($res['websites']);

            $res['completed'] = (int) $report['completed'];
            $res['default_profile'] = "";

            if (!empty($progress['default_profile'])) {
                $default_profile = $progress['default_profile'];
            } else {
                $default_profile = "";
            }

            $res['default_profile'] = $default_profile;
            if ($res['completed'] == 1) {
                $res['progress_completed'] = 1;
                $res['progress_total'] = 1;
            } else {
                $criteria = new Search\Helpers\Bridges\BridgeCriteria();
                $criteria->compare('is_completed', 1);
                $criteria->compare('enabled', 1);
                $CombinationBridge = new Search\Helpers\Bridges\CombinationBridge($report['id']);
                $res['progress_completed'] = $CombinationBridge->count($criteria);

                $criteria = new Search\Helpers\Bridges\BridgeCriteria();
                $criteria->compare('person_id', $id);
                $criteria->compare('enabled', 1);
                $CombinationBridge = new Search\Helpers\Bridges\CombinationBridge($id);
                $res['progress_total'] = $CombinationBridge->count($criteria);
            }

            ## dropDown profiles seaction .
            $dropDownProfiles = getDropDownProfiles($profiles, $progress);
            $res['dropDownProfiles'] = $dropDownProfiles;
            $res['dropDownProfilesArray'] = array_values($dropDownProfiles);
            $res['dropDownProfilesCount'] = count($dropDownProfiles);

            if ((empty($res['default_profile']) || !in_array($res['default_profile'], array_keys($dropDownProfiles))) && !empty($dropDownProfiles)) {
                $dropdownResults = array_column($dropDownProfiles, "res");
                if (!empty($dropdownResults[0])) {
                    $res['default_profile'] = "res_" . $dropdownResults[0];
                }
            }

            if (!empty($progress['nicknames_data'])) {
                $res['alternative_names'] = json_decode($progress['nicknames_data'], true);
            } else {
                $res['alternative_names'] = 0;
            }

            $res['current_location'] = array();
            if (count($res['locations'])) {
                $firstLocation = current($res['locations']);
                $res['current_location'] =
                array(
                    'locationName' => trim($firstLocation['shortAddress'] ?? $firstLocation['fullAddress'], ' , '),
                    'fullAddress' => trim($firstLocation['fullAddress'], ' , '),
                    'assoc_profile' => (isset($firstLocation['assoc_profile']) ? $firstLocation['assoc_profile'] : ""),
                );
            }

            global $is_local;
            if ($is_local) {
                $res['completed'] = 1;
                $res['is_paid'] = 1;
            }
            if ($include_search_fields) {
                $res['searchFields'] = extractSearchFieldsOf($report, $progress, false);
            } else {
                $res['searchFields'] = array();
            }

            $res['searchFields'] = SearchApis::preparePersonInfo($res['searchFields']);

            if ($res['dropDownProfilesCount'] > 0) {
                $res['searchFields']['displayName'] = "";
            }

            $res['firstChar'] = strtoupper(mb_substr($res['searchFields']['displayName'], 0, 1));

            // Undo
            $progressDelete = Yii::app()->db->createCommand()
                ->selectDistinct("delete_category")
                ->from('progress_delete')
                ->where(
                    'is_rolled_back = 0 and person_id=:person_id',
                    array(
                        ':person_id' => $id,
                    )
                )
                ->queryAll();

            $res['has_deleted_any'] = false;
            foreach ($progressDelete as $progressDeleteCategory) {
                $res['has_deleted_' . $progressDeleteCategory['delete_category']] = true;
                $res['has_deleted_any'] = true;
            }

            // Redo
            $progressDelete = Yii::app()->db->createCommand()
                ->selectDistinct("delete_category")
                ->from('progress_delete')
                ->where(
                    'is_rolled_back = 1 and person_id=:person_id',
                    array(
                        ':person_id' => $id,
                    )
                )
                ->queryAll();

            $res['has_redo_any'] = false;
            foreach ($progressDelete as $progressDeleteCategory) {
                $res['has_redo_' . $progressDeleteCategory['delete_category']] = true;
                $res['has_redo_any'] = true;
            }

            $bridge_criteria = new Search\Helpers\Bridges\BridgeCriteria();
            $bridge_criteria->select = ["id"];
            $bridge_criteria->addCondition("(deletion_type is null || deletion_type != 2) ");
            $bridge_criteria->addCondition("(is_deleted = 1 or invisible=1)");
            $bridge_criteria->compare("has_profile_data", 1);
            $bridge_criteria->compare("person_id", $id);
            $result_bridge = new Search\Helpers\Bridges\ResultsBridge($id);
            $res['has_hidden'] = !!$result_bridge->count($bridge_criteria);

            /*if ($is_api) {
            if ($res['locations']) {
            $res['locations'] = normalizeDataArray($res['locations']);
            }

            if ($res['phones']) {
            $res['phones'] = normalizeDataArray($res['phones']);
            }

            if ($res['relatives']) {
            $res['relatives'] = normalizeDataArray($res['relatives']);
            }

            if ($res['emails']) {
            $res['emails'] = normalizeDataArray($res['emails']);
            }

            if ($res['websites']) {
            $res['websites'] = normalizeDataArray($res['websites']);
            }

            if ($res['alternative_names']) {
            $res['alternative_names'] = normalizeDataArray($res['alternative_names']);
            }

            $res['work_experiences'] = array();
            if (!empty($progress['work_experiences_data'])) {
            $res['work_experiences'] = normalizeDataArray(json_decode($progress['work_experiences_data'], true));
            }

            $res['schools'] = array();
            if (!empty($progress['schools_data'])) {
            $res['schools'] = normalizeDataArray(json_decode($progress['schools_data'], true));
            }
            } else {*/
            $res['work_experiences'] = array();
            if (!empty($progress['work_experiences_data'])) {
                $work_experiences = json_decode($progress['work_experiences_data'], true);
                foreach ($work_experiences as $hash => $work_experience) {
                    if (!empty($work_experience['assoc_profile'])) {
                        unset($work_experiences[$hash]);
                    }
                }
                $res['work_experiences'] = array_values(normalizeDataArray($work_experiences));
            }

            $res['schools'] = array();
            if (!empty($progress['schools_data'])) {
                $schools_data = json_decode($progress['schools_data'], true);
                foreach ($schools_data as $hash => $school) {
                    if (!empty($school['assoc_profile'])) {
                        unset($schools_data[$hash]);
                    }
                }
                $res['schools'] = array_values(normalizeDataArray($schools_data));
            }
            // }
        } else {
            $res = array('data' => '');
        }

        if (!empty($res['imageSource']) && $res['imageSource'] == 'picasaweb') {
            $res['imageSource'] = 'picasa';
        }
        return $res;
    }
}

if (!function_exists('getDropDownProfiles')) {
    function getDropDownProfiles($profiles, $progress)
    {
        $dropDownProfiles = array();
        foreach ($profiles as $key => $profileData) {
            $profileFlags = (INT) $profileData['flags'];
            $expectedFlags = SearchScore::CITY | SearchScore::STATE | SearchScore::FRIEND | SearchScore::PHONE | SearchScore::EMAIL | SearchScore::USERNAME | SearchScore::COMPANY | SearchScore::SCHOOL;
            $FLFlags = SearchScore::F_NAME | SearchScore::L_NAME;
            $is_relative = $profileData['is_relative'];

            ## if the profile ranks #1 add it to dropdown although it has not more than name matche Task#11218 .
            if ((($profileFlags & $expectedFlags) != 0 && ($profileFlags & $FLFlags = $FLFlags) || $key == 0) && !$is_relative) {
                $profileData = dropDownResult($profileData, $progress, getDisplayName());
                $dropDownProfiles["res_" . $profileData['res']] = $profileData;
            }
        }
        return $dropDownProfiles;
    }
}

if (!function_exists('dropDownResult')) {
    function dropDownResult($profileData, $progress, $default_name)
    {
        $relatedProfiles = [];

        $prog_bridge = new Search\Helpers\Bridges\ProgressBridge($progress['person_id']);
        $prog = $prog_bridge->get();
        $verifiedDataPoints = false;

        if (!empty($prog['combined_data'])) {
            $verifiedDataPoints = $prog['combined_data'];
        }

        $combinedTemp = [];
        if ($verifiedDataPoints && !empty($verifiedDataPoints)) {
            $personID = $progress['person_id'];
            $verifiedWork = json_decode($verifiedDataPoints, true)['work_experiences'];
            $verifiedSchool = json_decode($verifiedDataPoints, true)['schools'];

            if (!empty($verifiedWork) || !empty($verifiedSchool)) {
                $criteria = new Search\Helpers\Bridges\BridgeCriteria();
                $criteria->addInCondition("res[]", array($verifiedSchool));
                $criteria->compare('type', 'schools');
                $criteria->order = "id asc";

                $dp_bridge = new Search\Helpers\Bridges\DataPointBridge($personID);
                $dataJsonSchool = $dp_bridge->getAll($criteria);

                $criteria = new Search\Helpers\Bridges\BridgeCriteria();
                $criteria->addInCondition("res[]", array($verifiedWork));
                $criteria->compare('type', 'work_experiences');
                $criteria->order = "id asc";

                $dp_bridge = new Search\Helpers\Bridges\DataPointBridge($personID);
                $dataJsonWork = $dp_bridge->getAll($criteria);

                $resultId[] = $verifiedWork;
                $resultId[] = $verifiedSchool;
                $works = [];
                $schools = [];
                $relatedProfiles = SearchHelpers::getRelatedProfiles($progress['person_id'], $resultId);
                $dataJson = array_merge($dataJsonSchool, $dataJsonWork);

                if ($dataJson) {
                    $combinedTemp = $progress;
                    $combinedTemp['work_experiences_data'] = '';
                    $combinedTemp['schools_data'] = '';

                    foreach ($dataJson as $value) {
                        if ($value['type'] == 'work_experiences') {
                            //TODO:: update res in json_data by
                            $works[$value['hash']] = json_decode($value['data_json'], true);
                        } elseif ($value['type'] == 'schools') {
                            $schools[$value['hash']] = json_decode($value['data_json'], true);
                        }
                    }
                    $combinedTemp['work_experiences_data'] = json_encode($works);
                    $combinedTemp['schools_data'] = json_encode($schools);
                }
            } else {
                SearchApis::logData($progress['person_id'], "Log combined_data verifiedData:\n " . print_r($verifiedDataPoints, true));
            }
        }

        $profileCategories = [
            'names' => 'name',
            'work_experiences' => 'work_experience',
            'schools' => 'school',
            'age' => 'age',
        ];

        foreach ($profileCategories as $category => $singleName) {
            $dataArray = json_decode($progress[$category . "_data"], true);
            if ($category == 'work_experiences' && is_array($dataArray)) {
                $dataArray = CSearch::sortDropDownPositions($dataArray);
            }

            if (!empty($dataArray)) {
                if ($category != "names") {
                    $profileData[$singleName] = array();
                }

                if (in_array($profileData['res'], $relatedProfiles) && ($category == 'work_experiences' || $category == 'schools')) {
                    if ($category == 'work_experiences') {
                        $dataArray = json_decode($combinedTemp['work_experiences_data'], true);
                        if (is_array($dataArray)) {
                            $dataArray = CSearch::sortDropDownPositions($dataArray);
                        }
                    } elseif ($category == 'schools') {
                        $dataArray = json_decode($combinedTemp['schools_data'], true);
                    }
                }
                foreach ($dataArray as $key => $data) {
                    if (!empty($data['res']) && ((int) $data['res'] === (int) $profileData['res']
                        || in_array((int) $data['res'], $relatedProfiles))) {
                        if ($category != "names") {
                            $profileData[$singleName][] = $data;
                        }
                    }
                }
            }
        }

        $profileData['name'] = CSearch::filterDropDownName($profileData['name'], $progress, $default_name);
        $profileData['r'] = EncryptID($profileData['res']);
        $profileData['profile'] = "res_" . $profileData['res'];
        $name = trim($profileData['name'], " (),.{}*&^%$#@!");
        $profileData['firstChar'] = strtoupper(mb_substr($name, 0, 1));
        return $profileData;
    }
}

if (!function_exists('extractSearchFieldsOf')) {
    function extractSearchFieldsOf($report, $progress, $check = true)
    {
        $ltm = $report['cities'];
        $ztm = $report['zipCodes'];

        $ct = count($ltm);

        $locs = array();
        for ($i = 0; $i < $ct; $i++) {
            if (!(isset($ltm[$i]) && $ltm[$i]) && !(isset($ztm[$i]) && $ztm[$i])) {
                continue;
            }

            $location = (isset($ltm[$i]) ? $ltm[$i] : '');
            if (!empty($location)) {
                if (SearchApis::inUS($location)) {
                    $location = ucfirst(strtolower(getStateName($location))) . ', United States';
                }
            }

            if (!$location) {
                continue;
            }

            $locs[] = array(
                'location' => $location,
                'zip' => (isset($ztm[$i]) ? $ztm[$i] : ''),
            );
        }

        ## In case we searched with username and did not find any name (add the username instead) .
        ## Task #10970 .
        $displayNames = empty($report['names']) ? $report['usernames'] : $report['names'];

        $searchFields = array(
            //"displayName" => $report['full_name'],
            'displayName' => empty($displayNames) ? '' : reset($displayNames),
            'name' => array_unique($report['names']),
            'location' => array_filter($locs),
            'address' => $report['addresses'],
            'phone' => $report['phones'],
            'birthDate' => $report['birthDates'],
            'age' => $report['ages'],
            'occupation' => $report['companies'],
            'email' => $report['emails'],
            'username' => $report['usernames'],
            'school' => $report['schools'],
            'flag' => array(),
            'associations' => array(),
            'additional' => array(),

            'claim' => [$report['reference'] != '00000000' ? $report['reference'] : ''],
            //[($model['reference'] != "00000000")?$model['reference']:''],
        );

        ## do not check this in case of getSummary (send false).
        if (!empty($progress['names_data']) && $check) {
            $dataArray = json_decode($progress['names_data'], true);
            if (!empty($dataArray)) {
                $searchFields['displayName'] = '';
            }
        }

        return $searchFields;
    }
}
