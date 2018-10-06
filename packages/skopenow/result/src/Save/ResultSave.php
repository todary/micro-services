<?php

namespace Skopenow\Result\Save;

use DB;
use Log;
use Cache;
use Skopenow\Result\Save\UrlPrepare;
use App\Events\AfterResultSaveEvent;
use App\Events\AfterResultUpdateEvent;
use App\Constants\AcceptanceReasons;
use App\Models\ResultData;
use Skopenow\Search\Models\SearchResultInterface;

class ResultSave
{
    protected $dataSource;
    protected $urlInfo;
    protected $urlPrepare;
    protected $scorObj;
    protected $scoringObj;
    protected $rejected;
    protected $relationship;
    
    public function __construct(
        $dataSource,
        $scoringObj,
        $rejected
    ) {
        $this->dataSource = $dataSource;
        $this->scoringObj = $scoringObj;
        $this->rejected = $rejected;
        $this->urlInfo = loadService("urlInfo");
        $this->urlPrepare = new UrlPrepare($this->urlInfo);
        $this->relationship = loadService("relationship");
    }

    public function updateResult(
        array $updatedData,
        int $resultId = null,
        string $url = null,
        $resultData = null
    ) {
        if (is_null($resultId) && is_null($url)) {
            return 0;
        }

        if ($resultData) {
            $url = $resultData->url;
        }

        //check if scoring data need to be updated, rescore data
        if (isset($updatedData["identities"])) {
            if (is_null($url)) {
                $url = $this->getResultURL($resultId);
            }

            Log::info('Rescoring new updates ');
            //dump($updatedData);
            $reScoring = $this->scoringObj->rescore($updatedData, $url, !isset($updatedData['finalScore']));
            Log::debug(json_encode($reScoring));

            $updatedData["identifiers"] = json_encode($reScoring["identifiers"]);
            $updatedData["score_identity"] = json_encode($reScoring["identities"]);
            $updatedData["score_source"] = $reScoring["sourceTypeScore"];
            $updatedData["score_source_type"] = $reScoring["resultTypeScore"];
            $updatedData["score_result_count"] = $reScoring["listCountScore"];
            $updatedData["score"] = $reScoring["finalScore"];
            $updatedData["flags"] = $reScoring["flags"];
            $updatedData["matching_flags"] = $reScoring["matching_flags"];
            $updatedData["input_flags"] = $reScoring["input_flags"];
            $updatedData["extra_flags"] = $reScoring["extra_flags"];

            if(isset($updatedData['listCountScore'])) unset($updatedData['listCountScore']);
            if(isset($updatedData['resultTypeScore'])) unset($updatedData['resultTypeScore']);
            if(isset($updatedData['sourceTypeScore'])) unset($updatedData['sourceTypeScore']);
            if(isset($updatedData['identityScore'])) unset($updatedData['identityScore']);
            if(isset($updatedData['identityScore'])) unset($updatedData['identityScore']);
            if(isset($updatedData['finalScore'])) unset($updatedData['finalScore']);
        }

        Log::debug('Data to updated :'.json_encode($updatedData));

        //update data
        $output =  $this->dataSource->updateResult($updatedData, $resultId, $url);

        //check if any rows affected in updates
        if ($output) {
            if (is_null($url)) {
                $url = $this->getResultURL($resultId);
            }

            if (!$resultData) {
                $resultData = $this->getResultData($url);

            } else {
                if ($resultData && $resultId) {
                    $resultData->id = $resultId;
                }

                if (is_null($resultData->id)) {
                    $uniqeContent = $this->urlInfo->prepareContent($url);

                    $resultId = $this->getResultId($uniqeContent);
                    $resultData->id = $resultId;

                    $this->cacheResultData($resultData);
                }
            }
            
            if (isset($updatedData["identities"])) {
                $resultData->setScore($reScoring["finalScore"]);
                $resultData->setScoreIdentities($reScoring["identities"]);
                $resultData->setFlags($reScoring["flags"]);
                $resultData->setMatchingFlags($reScoring["matching_flags"]);
                $resultData->setInputFlags($reScoring["input_flags"]);
                $resultData->setExtraFlags($reScoring["extra_flags"]);
            }

            Log::debug('run after update events');
            $this->runAfterUpdate($resultData);
            return $resultData->id;
        }

        if (!$resultId && !is_null($url)) {
            $uniqeContent = $this->urlInfo->prepareContent($url);
            $resultId = $this->getResultId($uniqeContent);
        }

        return $resultId;
    }

    public function saveResult($resultData)
    {
        Log::info("start save data");
        Log::debug(serialize($resultData));

        $output = [];

        Log::info("Get source and mainSource from URL");
        $urlSource = $this->urlInfo->determineSource($resultData->url);
        $source = $urlSource[0];
        $mainSource = $urlSource[1];
        Log::debug($source . "&" . $mainSource);

        Log::info("score result Data");
        $scoring = $this->scoringObj->init($resultData->getMatchStatus());
        //dd($scoring);
        Log::debug(json_encode($scoring));

        Log::info("Check Acceptance");
        $acceptObj = loadService('acceptance');
        $acceptance = $acceptObj->checkAcceptance($resultData, $scoring["flags"]);
        Log::debug(json_encode($acceptance));

        //save rejectected result
        if (!$acceptance["acceptance"]["acceptance"]) {
            Log::info("Data is rejected. Save data in rejected");
            $this->rejected->save($resultData, $acceptance["acceptance"]["reason"]);

            $output[$resultData->url]["action"] = "rejected";
            $output[$resultData->url]["resultId"] = null;
            $output[$resultData->url]["reason"] = $acceptance["acceptance"]["reason"];
            return $output;
        }

        $output[$resultData->url]["invisible_reason"] = $acceptance["visible"]["reason"];

        Log::info("Prepare data to save it");
        $data = [];
        
        //prepre url
        $resultData->url = $this->urlPrepare->prepareUrl(
            $resultData->url,
            $resultData->getIsProfile()
        );

        $data["html"] = $resultData->getHtml();
        $resultsPageTypeId = null;
        if (isset($resultData->getMatchStatus()["matchingData"]["results_page_type_id"])) {
            $resultsPageTypeId =
                $resultData->getMatchStatus()["matchingData"]["results_page_type_id"];
        }
        $data["results_page_type_id"] = $resultsPageTypeId;
        
        $title = $this->urlInfo->getSiteName($resultData->url);
        $searchTag = $this->urlInfo->getSiteTag($resultData->url);
        
        if (($searchTag == "Profile"
                && $title == "FACEBOOK"
                && $resultData->getIsProfile())
            || ($data["results_page_type_id"] == 2 && $title = "FACEBOOK")
        ) {
            $data["has_siblings"] = 1;
        }

        $data["raw_type"] = $resultData->getRawType();
        $data["related_to"] = $resultData->getRelatedTo();
        $data["is_relative"] = $resultData->getIsRelative();

        $matchStatus = $resultData->getMatchStatus();
        if (isset($matchStatus["matchingData"]['additional'])
        ) {
            $data["additional_data"] = $matchStatus["matchingData"]['additional'];
        }

        if (isset($resultData->getNames()[0]["first_name"])) {
            $data["first_name"] = $resultData->getNames()[0]["first_name"];
        }
        
        $data["tags"] = json_encode($resultData->getTags());
        $data["alternative_unique_content"] = $this->urlInfo->prepareContent(
            $resultData->getAlternativeUrl()
        );
        $data["other_data"] = $this->isFile($resultData->url);

        $data["name_match"] = 0;

        if (isset($matchStatus['matchingData']['matchingData']['location'])) {
            if (isset($matchStatus['matchingData']['matchingData']['location']['distance'])) {
                $data["distance"] = $matchStatus['matchingData']['matchingData']['location']['distance'];
            }
        }

        $data["spidered"] = $resultData->getSpidered();
        $data["is_delete"] = $resultData->getIsDelete();

        $ignoredLists = array('google','facebook','linkedin');
        if (in_array($mainSource, $ignoredLists)
            && $resultData->getRawType() == 'list') {
            $data["is_delete"] = 1;
            $resultData->setIsDelete(1);
        }

        $data["invisible"] = $resultData->getInvisible();
        $data["person_id"] = config("state.report_id");
        $data["source"] = $source;
        $data["main_source"] = $mainSource;
        $data["content"] = $resultData->url;
        $data["unique_content"] = $this->urlInfo->prepareContent(
            $resultData->url
        );
        $data["date"] = time();
        $data["combination_id"] = config("state.combination_id");
        $data["profile"] = $resultData->getIsProfile();
        $data["combination_level"] = config("state.combination_level_id");
        $data["is_manual"] = $resultData->getIsmanual();
        $data["account"] = $resultData->getAccountUsed();


        if ($resultData->username) {
            $data["username"] = $resultData->username['username'];
            $data["profile_username"] = $resultData->username['username'];
        }
        
        if ($resultData->getNames()->count()) {
            $data["profile_name"] = $resultData->getNames()[0]["full_name"];
        } else {
            $data["profile_name"] = $data["profile_username"]??null;
        }
        $data["profile_image"] = $resultData->image;

        $data["identifiers"] = json_encode($scoring["identifiers"]);
        $data["score_identity"] = json_encode($scoring["identities"]);
        $data["score_source"] = $scoring["sourceTypeScore"];
        $data["score_source_type"] = $scoring["resultTypeScore"];
        $data["score_result_count"] = $scoring["listCountScore"];
        $data["score"] = $scoring["finalScore"];
        $data["flags"] = $scoring["flags"];
        $data["matching_flags"] = $scoring["matching_flags"];
        $data["input_flags"] = $scoring["input_flags"];
        $data["extra_flags"] = $scoring["extra_flags"];
        
        //TODO Comes from scoring
        $data["comb_rank"] = null;
        $data["rank"] = null;

        $data["invisible"] = 1;
        $resultData->setInvisible(1);
        if (!$resultData->getIsProfile() || $acceptance["visible"]["visible"]) {
            $data["invisible"] = 0;
            $resultData->setInvisible(0);
        }

        $data["invisible_reason"] = $acceptance["visible"]["reason"];

        Log::debug(json_encode($data));

        DB::beginTransaction();
        try {
            $resultData->setScore($scoring["finalScore"]);
            $resultData->setScoreIdentities($scoring["identities"]);
            $resultData->setFlags($scoring["flags"]);
            $resultData->setMatchingFlags($scoring["matching_flags"]);
            $resultData->setInputFlags($scoring["input_flags"]);
            $resultData->setExtraFlags($scoring["extra_flags"]);
            
            //save and get result id
            $resultId = $this->dataSource->saveResult($data);
            $this->setRelationship($resultId);
            Log::info("result saved and its id is:" . $resultId);

            $output[$resultData->url]["action"] = "save";
            $output[$resultData->url]["resultId"] = $resultId;
            
            DB::commit();

            //save related urls
            $links = $this->saveRelatedUrl($resultData->links, $data["invisible"]);

            if ($resultId) {
                //set result id ans scoring data
                $resultData->id = $resultId;
                
                Log::info("Run after insert event for the result data");
                $resultData->setLinks($links);

                //cashe ResultData
                $this->cacheResultData($resultData);
                
                //run after insert event
                $this->runAfterInsert($resultData);
            }
            return $output;

        } catch (\Exception $ex) {
            if (stripos($ex->getMessage(), "Duplicate entry") !== false
                || stripos($ex->getMessage(), "Cannot add or update a child row") !== false
                || stripos($ex->getMessage(), "UNIQUE constraint failed") !== false
            ) {
                Log::info('result already Saved before start to update it');

                DB::rollBack();

                $updatedData = $resultData->getMatchStatus();

                if ($resultData->getIsmanual()) {
                    $updatedData["is_delete"] = 1;
                    $updatedData["invisible"] = 0;
                }

                if ($data["invisible"] == 0) {
                    $updatedData["invisible"] = 0;
                }

                if ($identities = $resultData->getScoreIdentities()) {
                    $updatedData["identities"] = $identities;
                }

                if ($identitiesShouldHave = $resultData->getIdentitiesShouldHave()) {
                    $updatedData["identities"] = array_merge($identities, iterator_to_array($identitiesShouldHave, true));
                }

                //save related urls
                $links = $this->saveRelatedUrl($resultData->links, $data["invisible"]);
                $resultData->setLinks($links);

                // $updatedData = array_merge($updatedData, $scoring);

                
                $resultId = $this->updateResult($updatedData, null, $resultData->url, $resultData);
                $this->setRelationship($resultId);
                $resultData->id = $resultId;

                //cashe ResultData
                $this->cacheResultData($resultData);
                
                $output[$resultData->url]["action"] = "update";
                $output[$resultData->url]["resultId"] = $resultId;

                DB::commit();
                return $output;
            }

            if (stripos($ex->getMessage(), "Deadlock found") !== false
                || stripos($ex->getMessage(), "Lock wait timeout") !== false
            ) {
                DB::rollBack();
                Log::error($ex);
                throw $ex;
            }

            Log::error($ex);
            DB::rollBack();
            throw $ex;
        }

        Log::info('Save Finished \r\n');
    }

    protected function saveRelatedUrl($relatedUrls, $invisible)
    {
        if ($relatedUrls) {
            Log::info("Start save Related Lnks");
            $i=0;
            Log::info("Loop on related Url to save it");
            foreach ($relatedUrls as $relatedUrl) {
                $data = [];

                Log::info("Start save related url");
                Log::debug($relatedUrl['url'], []);

                $resultData = $relatedUrl["result"];

                $urlSource = $this->urlInfo->determineSource($resultData->url);
                $source = $urlSource[0];
                $mainSource = $urlSource[1];

                $data["html"] = $resultData->getHtml();
                $resultsPageTypeId = null;
                
                if (isset($resultData->getMatchStatus()["matchingData"]["results_page_type_id"])) {
                    $resultsPageTypeId =
                        $resultData->getMatchStatus()["matchingData"]["results_page_type_id"];
                }
                $data["results_page_type_id"] = $resultsPageTypeId;
                
                $title = $this->urlInfo->getSiteName($resultData->url);
                $searchTag = $this->urlInfo->getSiteTag($resultData->url);
                
                if (($searchTag == "Profile"
                        && $title == "FACEBOOK"
                        && $resultData->getIsProfile())
                    || ($data["results_page_type_id"] == 2 && $title = "FACEBOOK")
                ) {
                    $data["has_siblings"] = 1;
                }

                $data["raw_type"] = $resultData->getRawType();
                $data["related_to"] = $resultData->getRelatedTo();
                $data["is_relative"] = $resultData->getIsRelative();

                $matchStatus = $resultData->getMatchStatus()["matchingData"];
                if (isset($matchStatus['additional'])
                ) {
                    $data["additional_data"] = $matchStatus['additional'];
                }

                if (isset($resultData->getNames()[0]["first_name"])) {
                    $data["first_name"] = $resultData->getNames()[0]["first_name"];
                }
                
                $data["tags"] = json_encode($resultData->getTags());
                $data["alternative_unique_content"] = $this->urlInfo->prepareContent(
                    $resultData->getAlternativeUrl()
                );
                $data["other_data"] = $this->isFile($resultData->url);

                $data["name_match"] = 0;

                if (isset($matchStatus['matchingData']['location'])) {
                    if (isset($matchStatus['matchingData']['location']['distance'])) {
                        $data["distance"] = $matchStatus['matchingData']['location']['distance'];
                    }
                }

                $uniqeContent = $this->urlInfo->prepareContent($resultData->url);
                $data["spidered"] = $resultData->getSpidered();
                $data["is_delete"] = $resultData->getIsDelete();
                $data["invisible"] = $invisible;
                $data["person_id"] = config("state.report_id");
                $data["source"] = $source;
                $data["main_source"] = $mainSource;
                $data["content"] = $resultData->url;
                $data["unique_content"] = $uniqeContent;
                $data["date"] = time();
                $data["combination_id"] = config("state.combination_id");
                $data["profile"] = $resultData->getIsProfile();
                $data["combination_level"] = config("state.combination_level_id");
                $data["is_manual"] = $resultData->getIsmanual();
                $data["account"] = $resultData->getAccountUsed();

                if ($resultData->username) {
                    $data["username"] = $resultData->username['username'];
                    $data["profile_username"] = $resultData->username['username'];
                }
                
                if ($resultData->getNames()->count()) {
                    $data["profile_name"] = $resultData->getNames()[0]["full_name"];
                } else {
                    $data["profile_name"] = $data["profile_username"]??null;
                }

                $data["profile_image"] = $resultData->image;

                Log::info("score result Data");
                $scoring = $this->scoringObj->init($resultData->getMatchStatus());
                Log::debug(json_encode($scoring));

                $data["identifiers"] = json_encode($scoring["identifiers"]);
                $data["score_identity"] = json_encode($scoring["identities"]);
                $data["score_source"] = $scoring["sourceTypeScore"];
                $data["score_source_type"] = $scoring["resultTypeScore"];
                $data["score_result_count"] = $scoring["listCountScore"];
                $data["score"] = $scoring["finalScore"];
                $data["flags"] = $scoring["flags"];
                $data["matching_flags"] = $scoring["matching_flags"];
                $data["input_flags"] = $scoring["input_flags"];
                $data["extra_flags"] = $scoring["extra_flags"];
                $data["comb_rank"] = null;
                $data["rank"] = null;
                
                DB::beginTransaction();
                try {

                    $resultData->setScore($scoring["finalScore"]);
                    $resultData->setScoreIdentities($scoring["identities"]);
                    $resultData->setFlags($scoring["flags"]);
                    $resultData->setMatchingFlags($scoring["matching_flags"]);
                    $resultData->setInputFlags($scoring["input_flags"]);
                    $resultData->setExtraFlags($scoring["extra_flags"]);
                    
                    $relatedResultId = $this->dataSource->saveResult($data);
                    $this->setRelationship($relatedResultId);
                    $resultData->id = $relatedResultId;
                    Log::info("Related Url saved with id: " . $relatedResultId);

                    $output[$relatedUrl["url"]]["action"] = "save";
                    $output[$relatedUrl["url"]]["resultId"] = $relatedResultId;

                    //set result id
                    $relatedUrls[$i]["id"] = $relatedResultId;
                    $relatedUrls[$i]["status"] = "save";
                    
                    DB::commit();

                    //cashe ResultData
                    $this->cacheResultData($resultData);
                
                    Log::info("Run After Insert Event");
                    $this->runAfterInsert($resultData);
                    
                } catch (\Exception $ex) {
                    if (stripos($ex->getMessage(), "Duplicate entry") !== false
                        || stripos($ex->getMessage(), "Cannot add or update a child row") !== false
                        || stripos($ex->getMessage(), "UNIQUE constraint failed") !== false
                    ) {
                        Log::info("Related Url already Saved before start to update its data");

                        DB::rollBack();

                        $updatedData = $resultData->getMatchStatus();

                        if ($data["invisible"] == 0) {
                            $updatedData["invisible"] = 0;
                        }

                        if ($identities = $resultData->getScoreIdentities()) {
                            $updatedData["identities"] = $identities;
                        }

                        if ($identitiesShouldHave = $resultData->getIdentitiesShouldHave()) {
                            $updatedData["identities"] = array_merge($identities, iterator_to_array($identitiesShouldHave, true));
                        }

                        $updatedData = array_merge($updatedData, $scoring);
                        
                        
                        $resultId = $this->updateResult($updatedData, null, $relatedUrl["url"], $resultData);
                        $this->setRelationship($resultId);
                        $resultData->id = $resultId;
                        
                        $output[$relatedUrl["url"]]["action"] = "update";
                        $output[$relatedUrl["url"]]["resultId"] = $resultId;

                        //set result id
                        $relatedUrls[$i]["id"] = $resultId;
                        $relatedUrls[$i]["status"] = "update";
                    }

                    if (stripos($ex->getMessage(), "Deadlock found") !== false
                        || stripos($ex->getMessage(), "Lock wait timeout") !== false
                    ) {
                        Log::error($ex);
                        DB::rollBack();
                        throw $ex;
                    }

                    DB::rollBack();
                }
                $i++;
            }
            Log::info("Finished save and updates for related Url");
        }
        return $relatedUrls;
    }

    protected function setRelationship(int $resultId)
    {
        try {
            $combinationId = config("state.combination_id");
            $params = ["type" => "C2R", "reason" => 0];

            $this->relationship->insert()->setRelationshipWithIds($combinationId, $resultId, $params);
            return true;
        } catch (\Exception $e) {
            notifyDevForException($e);
            \Log::error($e);
        }
        
    }

    protected function getResultData(string $url)
    {
        $uniqeContent = $this->urlInfo->prepareContent($url);

        $key = "Reportdata_".config("state.report_id").md5($uniqeContent);
        
        $resultData = @unserialize(Cache::get($key));

        if (!is_object($resultData)) {
            $resultData = null;
            Cache::delete($key);
        }

        if ($resultData) {
            if (is_null($resultData->id)) {
                $resultId = $this->getResultId($uniqeContent);
                $resultData->id = $resultId;
                $this->cacheResultData($resultData);
            }
            return $resultData;
        }
        $urlSource = $this->urlInfo->determineSource($url);
        $mainSource = null;
        if (isset($urlSource[1])) {
            $mainSource = $urlSource[1];
        }

        $info = $this->urlInfo->getProfileInfo($url, $mainSource);
        $resultData = new ResultData($url);

        $resultData->setProfileInfo($info, true);

        if (is_null($resultData->id)) {
            $resultId = $this->getResultId($uniqeContent);
            $resultData->id = $resultId;

            $this->cacheResultData($resultData);
        }

        $this->cacheResultData($resultData);

        return $resultData;
    }

    protected function getResultId($uniqeContent)
    {
        $key = "Reportdata_".config("state.report_id").md5($uniqeContent);
        $resultData = @unserialize(Cache::get($key));

        if ($resultData && !is_null($resultData->id)) {
            return $resultData->id;
        }

        //get result from database
        $result = $this->dataSource->getResult(null, $uniqeContent);
        if (count($result)) {
            return $result["id"];
        }
        return 0;
    }

    protected function cacheResultData($resultData)
    {
        $key = "Reportdata_".config("state.report_id").md5($resultData->unique_url);
        Cache::put($key, serialize($resultData), 15*60);
    }

    protected function getResultURL(int $resultId):string
    {
        $result = $this->dataSource->getResult($resultId);
        if (count($result)) {
            return $result["url"]??$result['content'];
        }
        return "";
    }

    public function runAfterInsert($resultData)
    {
        event(new AfterResultSaveEvent($resultData));
    }

    public function runAfterUpdate($resultData)
    {
        event(new AfterResultUpdateEvent($resultData));
    }

    /**
     * [isFile description]
     *
     * @return boolean [description]
     */
    protected function isFile($url)
    {
        $isFile = null;
        $fileExt = null;
        $typePatterns = array(
            'pdf' => array(
                "#\.pdf($|\W)#i",
                "#article\/download#i"
            ),
            'doc' => array(
                "#\.doc($|\W)#i",
                "#\.docx($|\W)#i",
                "#\.dot($|\W)#i",
                "#\.w6w($|\W)#i",
                "#\.wiz($|\W)#i",
                "#\.word($|\W)#i",
                "#\.rtf($|\W)#i",
                "#\.wri($|\W)#i",
                "#\.odt($|\W)#i",
                "#\.hwp($|\W)#i",
            ),
            'xls' => array(
                "#\.xls($|\W)#i",
                "#\.xlsx($|\W)#i",
                "#\.ods($|\W)#i",
            ),
            'ppt' => array(
                "#\.ppt($|\W)#i",
                "#\.pptx($|\W)#i",
                "#\.odp($|\W)#i",
            ),
            'txt' => array(
                "#\.txt($|[^&a-z]\W?)#i",
                "#\.tex($|[^&a-z]\W?)#i",
                "#\.text($|[^&a-z]\W?)#i",
                "#\.bas($|[^&a-z]\W?)#i",
                "#\.cpp($|[^&a-z]\W?)#i",
                "#\.xml($|[^&a-z]\W?)#i",
                "#\.gpx($|[^&a-z]\W?)#i",
                "#\.kml($|[^&a-z]\W?)#i",
                "#\.kmz($|[^&a-z]\W?)#i",
                "#\.dwf($|[^&a-z]\W?)#i",
            ),
            'swf' => array(
                "#\.swf($|\W)#i",
            ),
        );

        foreach ($typePatterns as $ext => $pats) {
            foreach ($pats as $pat) {
                $matchesCount = preg_match($pat, $url);
                if ($matchesCount) {
                    $isFile = 1;
                    $fileExt = $ext;
                    break 2;
                }
            }
        }

        return $isFile;
    }

    //TODO Move it to scoring service
    /*protected function combRank($combsFields, $source, $combinationLevel)
    {
        $combRank = null;
        if ($combsFields) {
            $unSer = null;
            if ($combsFields) {
                $unSer = unserialize($combsFields);
            }
            
            if (is_array($unSer)) {
                $combRank = $this->getCombsScore();
            }

            if (strpos(strtolower($source()), 'google') !== false) {
                $combRank = $combRank / 2.2;
            }
        }

        return $combRank;
    }

    //TODO Move it to scoring service
    protected function getCombsScore($combsFields, $combsLevel)
    {
        $combsFields = unserialize($combsFields);
        
        if (!isset($combsFields[$combsLevel])
            || !is_array($combsFields[$combsLevel])
        ) {
            return false;
        }

        $rules = array(
            'n' => 0.5,
            'ct' => 0,
            'st' => 0,
            'cm' => 0,
            'sc' => 0,
            'bd' => 0,
            'zp' => 0,
            'un' => 0.8,
            'adr' => 1,
            'em' => 1,
            'ph' => 1,
            'n-ct' => 0.8,
            'n-st' => 0.6,
            'n-cm' => 0.9,
            'n-sc' => 0.9,
            'n-bd' => 0.75,
            'n-un' => 1,
        );
    
        array_walk($rules, function (&$v) {
            $v = trim($v);
        });

        
        $sumScore = 0;
        foreach ($combsFields[$combsLevel] as $key => $field) {
            
            $key = trim($key);
            if (isset($rules[$key])) {
                $sumScore = $rules[$key] < $sumScore ? $sumScore : $rules[$key];
            }

            if (isset($rules['n-' . $key])) {
                $sumScore = $rules['n-' . $key] < $sumScore ? $sumScore : $rules['n-' . $key];
            }

        }

        return $sumScore;
    }*/
}
