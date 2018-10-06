<?php

namespace Skopenow\Matching;

class Helpers {
    public function extractLinksInfo(&$links, $person, $combination)
    {
        $ret = [
            "names" => [],
            "locations" => [],
            "schools" => [],
            "work_Exps" => []
        ];
        foreach ($links as $link => $value) {
            $entry = loadservice('urlInfo');

            $siteName = strtolower($entry->getSiteName($link));
            switch ($siteName) {
                case 'facebook':
                    $profileInfo = $entry->getProfileInfo($link, 'facebook');
                    break;
                case 'linkedin':
                    $profileInfo = $entry->getProfileInfo($link, 'linkedin');
                    break;
                case 'twitter':
                    $profileInfo = $entry->getProfileInfo($link, 'twitter');
                    break;
                default:
                    $profileInfo = [];
                    break;
            }
            if (
                $siteName == "facebook" &&
                !empty($profileInfo['page_type']['results_page_type_id']) &&
                $profileInfo['page_type']['results_page_type_id'] == 2
            ) {
                $profileInfo = [] ;
            }
            if (
                (
                    isset($profileInfo['status']) &&
                    ($profileInfo['status'] === '404' ||
                        $profileInfo['status'] === 404)
                ) ||
                $profileInfo === false
            ) {
                // TODO
                // searchApis::logData($person['id'],"This profile ({$link}) returned with 404 , ignored .\n",$combination);
                unset($links[$link]);
                continue;
            }
            if (
                isset($profileInfo['name']) &&
                $profileInfo['name'] == false
            ) {
                // TODO
                // searchApis::logData($person['id'],"This profile ({$link}) returned without name , ignored .\n",$combination);
                unset($links[$link]);
                continue;
            }
            if (!empty($profileInfo['name'])) {
                $ret['names'][] = $profileInfo['name'];
            }
            if (!empty($profileInfo['location'])) {
                if (is_array($profileInfo['location'])) {
                    $ret['locations'] = array_merge($ret['locations'], array_values($profileInfo['location']));
                } elseif(is_string($profileInfo['location'])) {
                    $ret['locations'][] = $profileInfo['location'];
                }
            }
            if (isset($profileInfo['education']['education'])) {
                if (!empty($profileInfo['education']['education'])) {
                    $ret['schools'] = array_merge($ret['schools'], $profileInfo['education']['education']);
                }
            } elseif (!empty($profileInfo['education'])) {
                $ret['schools'] = array_merge($ret['schools'], $profileInfo['education']);
            }
            if (!empty($profileInfo['education']['age'])) {
                $ret['age'] = $profileInfo['education']['age'];
            }
            if (!empty($profileInfo['experience'])) {
                $ret['work_Exps'] = array_merge($ret['work_Exps'], $profileInfo['experience']);
            } elseif (!empty($profileInfo['positions'])) {
                $ret['work_Exps'] = array_merge($ret['work_Exps'], $profileInfo['positions']);
            }
            $links[$link] = ["profileInfo" =>$profileInfo];
        }
        return $ret;
    }

    public function createInsiteLinkeCombinations(
        $person,
        $combination,
        $mainProfileUrl,
        $links,
        $status,
        $combination_source
    )
    {
        $comb_array = [
            "main_profile_url" => $mainProfileUrl,
            "combination_level" => $combination['combination_level'],
            "sources" => []
        ];
        foreach ($links as $link => $data) {
            list($source, $main_source) = $entry->determineSource($link);
            $comb_array['sources'][$main_source][] = [
                "source" => $main_source,
                "check_status" => $status,
                "link" => $link,
                "profile_name" => ""
            ];
        }

        // SearchApis::store_combination(
        //     null,
        //     null,
        //     $combination_source,
        //     json_encode($comb_array),
        //     null,
        //     null,
        //     $combination['combs_fields'],
        //     $person
        // );
    }

    public function getPersonNames($id)
    {
        // $prog_bridge = new \Search\Helpers\Bridges\ProgressBridge($id);
        // return $prog_bridge->get();
        return \DB::table('persons')->find($id);
    }
}
