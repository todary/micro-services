<?php
/**
 * Created by PhpStorm.
 * User: todary
 * Date: 21/11/17
 * Time: 05:01 م
 */

namespace Skopenow\Api\Library\Mapping;

use Skopenow\Api\Library\Mapping\MappingInterface;

class Mapping implements MappingInterface
{

    protected $requestArray =
        [
            'id' => 0,
            'offset' => 0,
            'limit' => 999,
            'sort' => 'score',
            'types' => null,
            'stypes' => null,
            'sources' => array(),
            'resultsFilter' => [
                'locationDistance' => null,
                'nameMatch' => 0,
                'familyProfiles' => 1,
                'searchSpecificity' => 1,
            ]
        ];


    protected $results_ids_index = [];
    protected $results_index = [];


    public function getRequestArray($option, $report_id)
    {

        $this->requestArray['id'] = encryptID($report_id);
        $this->requestArray['limit'] = !empty($option['filters']['limit']) ? $option['filters']['limit'] : 999;
        $this->requestArray['offset'] = !empty($option['filters']['offset']) ? $option['filters']['offset'] : 0;
        if (!empty($option['filters'])) {
            foreach ($option['filters'] as $key => $value) {
                switch ($key) {
                    case 'name':
                        $this->requestArray['resultsFilter']['nameMatch'] = $value;
                        break;
                    case 'location':
                        $this->requestArray['resultsFilter']['locationDistance'] = $value;
                        break;
                    case 'family':
                        $this->requestArray['resultsFilter']['familyProfiles'] = $value;
                        break;
                    case 'exact':
                        $this->requestArray['resultsFilter']['searchSpecificity'] = $value;
                        break;
                }
            }
        }


        return $this->requestArray;
    }


    public function mappingResultData($report, $result, $api_options)
    {

        $resultfinal = array();
        $resultfinal['id'] = $report['id'];
        $resultfinal['type'] = 'json';
        if (!empty($api_options['inputs']))
            $resultfinal['inputs'] = $api_options['inputs'];
        $resultfinal['status'] = $result['status'];
        $resultfinal['score'] = $report['score'];
        #filter resultsFetched
        $filterresults = $result['resultsFetched'];

        $result_clear = array();
        $results_index = array();
        $results_ids_index = array();
        foreach ($filterresults as $k => $filterresult) {
            if (empty($filterresult['resultSourceArray'])) continue;
            $details = array();
            foreach ($filterresult['resultSourceArray'] as $resultSourceArray) {
                $tags = array();
                if (!empty($resultSourceArray['tags']) && $resultSourceArray['tags'][0] != "pending123") {
                    $tags = $resultSourceArray['tags'];
                }

                $type = 'profile';
                if (!empty($resultSourceArray['type'])) {
                    $type = $resultSourceArray['type'];
                }

                $details[] = array(
                    'image' => $resultSourceArray['image'],
                    //'thumb' => $resultSourceArray['thumb'],
                    'type' => strtolower($type),
                    'url' => $resultSourceArray['url'],
                    'tags' => $tags,
                );
            }

            $res_clear = array(
                'source' => $filterresult['source'],
                'identifiers' => !empty($filterresult['comb_fields']) ? $filterresult['comb_fields'] : null,
                'url' => !empty($filterresult['resultSourceArray'][0]['url']) ? $filterresult['resultSourceArray'][0]['url'] : null,
                'ip' => !empty($filterresult['resultSourceArray'][0]['host_ip']) ? $filterresult['resultSourceArray'][0]['host_ip'] : null,
                'searchdate' => $filterresult['date'],
                'details' => $details,
            );

            if (empty($res_clear['ip']) || $res_clear['ip'] == "Not Available")
                $res_clear['ip'] = null;

            $resultfinal['results'] = $res_clear;

            if (!empty($filterresult['resultSourceArray'][0]['url']))
                $this->results_index[$filterresult['resultSourceArray'][0]['url']] = $k;

            if (!empty($filterresult['id']))
                $this->results_ids_index[$filterresult['id']] = $k;
        }

        return $resultfinal;
    }


    public function mappingSummaryData($result)
    {

        $summaryData = [
            'locations' => ['title' => 'locations', "attributes" =>
                ['locationName' => 'value', 'locationLat?null' => 'lat', 'locationLng?null' => 'lng', 'fullAddress' => 'address']],

            'phones' => ['title' => 'phones', "attributes" =>
                ['number' => 'value',]],

            'relatives' => ['title' => 'relatives', "attributes" =>
                ['name' => 'value', 'location?null' => 'location']],

            'emails' => ['title' => 'emails', "attributes" =>
                ['emailAddress' => 'value',]],

            'websites' => ['title' => 'websites', "attributes" =>
                ['url' => 'value',]],

            'work_experiences' => ['title' => 'jobs', "attributes" =>
                ['position?null' => 'position', 'company?null' => 'company', 'start_date?null' => 'start_date', 'end_date?null' => 'end_date', 'index' => 'result_index',]],

            'schools' => ['title' => 'schools', "attributes" =>
                ['school' => 'value', 'start_date?null' => 'start_date', 'end_date?null' => 'end_date', 'index' => 'result_index',]],

            'alternative_names' => ['title' => 'nicknames', "attributes" =>
                ['names' => '__SETCOMBINE__',]],

            'profiles' => ['title' => 'profiles', "attributes" =>
                ['name' => 'value', 'source' => 'source', 'is_relative?int' => 'is_relative', 'url' => 'url', 'image?null' => 'image', '__SERIAL__' => 'rank', 'index' => 'result_index']],
        ];

        $resultfinal['summary'] = array();
        foreach ($summaryData as $key => $structure) {
            $resultfinal['summary'][$structure['title']] = [];
            $arr = [];
            if (empty($result['summary'][$key])) {
                continue;
            }

            foreach ($result['summary'][$key] as $k => $item) {
                $data = array();
                foreach ($structure['attributes'] as $attr => $alias) {
                    $is_nullable = false;
                    $filter_funcation = null;
                    if (strpos($attr, "?null")) {
                        $is_nullable = true;
                        $attr = str_replace("?null", "", $attr);
                    }
                    if (strpos($attr, "?int")) {
                        $filter_funcation = "intval";
                        $attr = str_replace("?int", "", $attr);
                    }
                    if ($attr == "__SERIAL__") {
                        $data[$alias] = $k;
                        continue;
                    }

                    if ($alias == "__ARRAY__") {
                        $data = $item[$attr];
                        if ($is_nullable && !$data) $data = null;
                        break;
                    }

                    if ($alias == "__SET__") {
                        $arr = $item[$attr];
                        break 2;
                    }

                    if ($alias == "__SETCOMBINE__") {
                        $arr = array_unique(array_merge($arr, $item[$attr]));
                        continue;
                    }

                    if ($attr == "index" && !empty($item['url'])) {
                        $index = -1;
                        if (isset($results_index[$item['url']]))
                            $index = $this->results_index[$item['url']];
                        $data[$alias] = $index;
                        continue;
                    }

                    if ($attr == "index") {
                        $index = -1;
                        if (!empty($item['res'])) {
                            $res_id = encryptID($item['res']);
                            if (isset($results_ids_index[$res_id]))
                                $index = $this->results_ids_index[$res_id];
                        }
                        $data[$alias] = $index;
                        continue;
                    }

                    if (isset($item[$attr])) {
                        if (is_array($item[$attr])) {
                            $data[$alias] = implode(',', $item[$attr]);
                        } else {
                            $data[$alias] = $item[$attr];
                        }
                    }

                    if ($is_nullable && empty($data[$alias]))
                        $data[$alias] = null;
                    else if ($filter_funcation)
                        $data[$alias] = $filter_funcation($data[$alias]);
                }

                if ($alias != "__SET__" && $alias != "__SETCOMBINE__") {
                    $arr[$k] = $data;
                }
            }

            $resultfinal['summary'][$structure['title']] = array_values($arr);
        }

        return $resultfinal;
    }


    public function removeHideData($option, $resultfinal)
    {

        if (isset($option['filters']["hide"])) {
            foreach ($option['filters']["hide"] as $key) {

                switch ($key) {
                    case 'summary':
                        unset($resultfinal['summary']);
                        break;
                    case 'score':
                        unset($resultfinal['score']);
                        break;
                    case 'location':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['locations']);
                        }
                        break;
                    case 'phones':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['phones']);
                        }
                        break;

                    case 'relatives':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['relatives']);
                        }
                        break;
                    case 'emails':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['emails']);
                        }
                        break;
                    case 'nicknames':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['nicknames']);
                        }
                        break;
                    case 'websites':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['websites']);
                        }

                        break;
                    case 'profiles':
                        if (isset($resultfinal['summary'])) {
                            unset($resultfinal['summary']['profiles']);
                        }
                        break;
                    case 'photos':
                        if (isset($resultfinal['summary'])) {
                            foreach ($resultfinal['summary']['profiles'] as $key => $value) {
                                unset($resultfinal['summary']['profiles'][$key]['image']);
                            }
                        }
                        foreach ($resultfinal['results'] as $res_key => $res_value) {

                            foreach ($resultfinal['results']['details'] as $key => $value) {

                                unset($resultfinal['results']['details'][$key]['image']);
                            }
                        }
                        break;
                    case 'urls':
                        if (isset($resultfinal['summary'])) {
                            foreach ($resultfinal['summary']['profiles'] as $key => $value) {
                                unset($resultfinal['summary']['profiles'][$key]['url']);
                            }
                        }
                        foreach ($resultfinal['results'] as $res_key => $res_value) {
                            foreach ($resultfinal['results']['details'] as $key => $value) {
                                unset($resultfinal['results']['details'][$key]['url']);
                            }
                        }
                        break;
                    case 'tags':
                        foreach ($resultfinal['results'] as $res_key => $res_value) {
                            foreach ($resultfinal['results']['details'] as $key => $value) {
                                unset($resultfinal['results']['details'][$key]['tags']);
                            }
                        }
                        break;
                    case 'ip':
                        foreach ($resultfinal['results'] as $res_key => $res_value) {

                            unset($resultfinal['results']['ip']);

                        }
                        break;

                }
            }
        }

        return $resultfinal;
    }

}