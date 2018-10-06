<?php

namespace Skopenow\Result;

use DB;
use Cache;
use App\Models\Result;
use App\Models\MainSource;
use App\Models\Entity;

class DataSourceLuman implements DataBaseSourceInterface
{
    protected $report;

    protected $reportId;

    public function __construct()
    {
        $this->reportId = config('state.report_id');
        $this->report["id"] = $this->reportId;
    }

    /**
     * [saveResult description]
     *
     * @param array  $data [description]
     *
     * @return int       [description]
     */
    public function saveResult(array $data):int
    {
        //create entity
        $entity = [
            "report_id" => $this->reportId,
            "type" => "result"
        ];
        $entityId = Entity::insertGetId($entity);

        //get source id
        $sourceId = $this->getSourceId($data["main_source"]);

        //prepare data to be saved
        $result_data = [
            "id" => $entityId,
            "report_id" => $this->reportId,
            "type"=>(!empty($data['type']) && $data['type'])?$data['type']:"other",
            "html"=>$data['html']??null,
            "main_combination_id" => $data['combination_id'],
            "source_id" => $sourceId,
            "source" => $data['source'],
            "raw_type"=>$data['raw_type'],
            "unique_content"=>$data['unique_content']??null,
            "url"=>$data['content'],
            "date"=>$data['date']??0,
            "identifiers"=>$data['identifiers']??null,
            "additional_data"=>$data['additional_data']??null,
            "has_siblings"=>$data['has_siblings']??0,
            "username"=>$data['username']??null,
            "combination_level"=>$data['combination_level']??1,
            "comb_rank"=>$data['comb_rank']??0,
            "results_page_type_id"=>$data['results_page_type_id']??null,
            "does_match_name"=>$data['does_match_name']??null,
            "does_match_location"=>$data['does_match_location']??null,
            "does_match_friendslist"=>$data['does_match_friendlist']??null,
            "flags"=>$data['flags']??0,
            "matching_flags"=>$data['matching_flags']??0,
            "input_flags"=>$data['input_flags']??0,
            "extra_flags"=>$data['extra_flags']??0,
            "is_deleted"=>$data['is_deleted']??0,
            "invisible"=>$data['invisible']??0,
            "is_manual"=>$data['is_manual']??0,
            "is_relative"=>$data['is_relative']??0,
            "name_match"=>$data['name_match']??0,
            "distance"=>$data['distance']??null,
            "exact_filter"=>$data['exact_filter']??0,
            "spidered"=>$data['spidered']??0,
            "other_data"=>$data['other_data']??null,
            "score_identity"=>$data['score_identity']??[],
            "score_source"=>$data['score_source']??0,
            "score_source_type"=>$data['score_source_type']??0,
            "score_result_count"=>$data['score_result_count']??0,
            "score"=>$data['score']??0,
            "rank"=>$data['rank']??0,
            "account"=>$data['account']??null,
            "is_profile"=>$data['profile']??0,
            "first_name"=>$data['first_name']??null,
            "tags"=>$data['tags']??null,
            "alternative_unique_content"=>$data['alternative_unique_content']??null,
            "info_extracted" => $data['info_extracted']??null,
            "display_level" => $data['display_level']??null,
            "copied_from_rescan" => $data['copied_from_rescan']??0,
            "profile_image" => $data['profile_image']??null,
            "profile_name" => $data['profile_name']??null,
            "profile_username" => $data['profile_username']??null,
            "custom_source_name" => $data['custom_source_name']??null,
            "meta_deleted" => $data['meta_deleted']??0,
        ];

        //save result and return id
        Result::insert($result_data);

        return $entityId;
    }

    /**
     * [updateResult description]
     *
     * @param array       $data [description]
     * @param int|null    $id   [description]
     * @param string|null $url  [description]
     *
     * @return [type]           [description]
     */
    public function updateResult($data, $id = null, $url = null)
    {
        if (is_null($id) && is_null($url)) {
            throw "No result id or url to update result data";
        }

        //if (isset($data["matchingData"])) {
            unset($data["matchingData"]);
            unset($data["identities"]);
            unset($data["identitiesShouldHave"]);
            unset($data["additionalIdentifiers"]);
            unset($data["resultsCount"]);
            unset($data["link"]);
            unset($data["main_source"]);
            unset($data["isProfile"]);
            unset($data["isRelative"]);
            unset($data["isInput"]);
            unset($data["isPeopleData"]);
        //}

        $uniqeContent = null;
        if (!is_null($url)) {
            $urlInfo = loadService('urlInfo');
            $uniqeContent = $urlInfo->prepareContent($url);
        }

        $update = Result::where(function ($q) use ($id, $uniqeContent) {
            if (!is_null($id)) {
                $q->where("id", $id);

            } elseif (!is_null($uniqeContent)) {
                $q->where("unique_content", $uniqeContent);
                $q->where("report_id", $this->reportId);
            }
        })->update($data);

        return $update;
    }

    /**
     * [getResult description]
     *
     * @param int|null $resultId [description]
     * @param string|null $url      [description]
     *
     * @return Result Collection           [description]
     */
    public function getResult($resultId = null, $url = null)
    {
        $uniqeContent = null;
        if (!is_null($url)) {
            $urlInfo = loadService('urlInfo');
            $uniqeContent = $urlInfo->prepareContent($url);
        }

        $result = Result::where(function ($q) use ($resultId, $uniqeContent) {
            if (!is_null($resultId)) {
                $q->where("id", $resultId);

            } elseif (!is_null($uniqeContent)) {
                $q->where("unique_content", $uniqeContent);
                $q->where("report_id", $this->reportId);
            }
        })->first();

        return $result;
    }

    /**
     * [updateResults description]
     *
     * @param array $data [description]
     * @param array $ids  [description]
     *
     * @return int       [description]
     */
    public function updateResults($data, $ids)
    {
        $update = Result::where("report_id", $this->reportId)->whereIn("id", $ids)
            ->update($data);
        return $update;
    }

    /**
     * [getResults description]
     *
     * @param criteria $criteria [description]
     *
     * @return array           [description]
     */
    public function getResults($criteria)
    {
        if (!$criteria["order"]) {
            $criteria["order"] = "id asc ";
        }
        
        $results = Result::select($criteria["select"])
            ->where(function ($q) use ($criteria) {
                if (isset($criteria["raw"])) {
                    $q->whereRaw($criteria["raw"]);
                }
            })
            ->orderByRaw($criteria["order"]);
        if (!empty($criteria['group'])) {
            $results = $results->groupBy($criteria["group"]);
        }
            
        $results = $results->skip($criteria["offset"])->take($criteria["limit"])
            ->get();

        return $results;
    }

    public function updateByCriteria($data, $criteria)
    {
        $update = Result::where(function ($q) use ($criteria) {
            if (isset($criteria["raw"])) {
                $q->whereRaw($criteria["raw"]);
            }
        })->update($data);
        
        return $update;
    }

    /**
     * [getSourceId description]
     *
     * @param string $mainSource [description]
     *
     * @return int             [description]
     */
    protected function getSourceId(string $mainSource) :int
    {
        $source = MainSource::where("name", $mainSource)->first();
        if ($source) {
            return $source->id;
        }
        throw new Exception("Missing source {$data['main_source']}!");
    }
}
