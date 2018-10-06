<?php

namespace Skopenow\Search\Managing;

use App\DataTypes\DataType;
use Skopenow\Search\Fetching\FetcherInterface;
use App\DataTypes\DataTypeInterface;
use Skopenow\Search\Models\SearchResultInterface;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchList;

abstract class AbstractManager implements ManagerInterface
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "";

    /**
     * @const bool decide to run On Result Save event.
     */
    const Run_Main_Result_Event = true;

    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;

    /**
     * [$saveToPending decide whether to collect data from pending table and purify them or not]
     * @var boolean
     */
    public $saveToPending = false;

    protected $searchOutput;

    /**
     * Instantiate source search
     * @param FetcherInterface $fetcher The fetcher which will search and get the results
     */
    public function __construct(FetcherInterface $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function execute(): SearchListInterface
    {
        \Log::info("Manager start");
        $this->onStart();

        \Log::info("Featchers start search");
        $hasData = $this->fetcher->execute();

        if (!$hasData) {
            $this->onEnd(false);
            return new SearchList(static::MAIN_SOURCE_NAME);
        }

        $searchOutput = $this->fetcher->getOutput();
        $insiteProfilesNeedProfileInfo = $this->getInsiteProfilesNeedProfileInfo($searchOutput);
        if (!empty($insiteProfilesNeedProfileInfo)) {
            $this->processProfilesInfo($insiteProfilesNeedProfileInfo);
        }
        // dd($searchOutput);
        \Log::debug("Data from fatcher : " . json_encode($searchOutput));

        \Log::info("Loop on data to make Run_Main_Result_Event true");
        foreach ($searchOutput->getResults() as $result) {
            if (static::Run_Main_Result_Event) {
                $this->setResultRunMainEvent($result);//$result->Run_Main_Result_Event = true;
            }
            static::processResult($result, $this);
        }

        /* Use only datapoints from people data
        \Log::info("Loop on data to processDataPoint");
        if (!empty($searchOutput->getDataPoints())) {
            $dataPointService = loadService('datapoint');
            $dataPointService->make(new \ArrayIterator($searchOutput->getDataPoints()));
        }
        */

        // foreach ($searchOutput->getDataPoints() as $type => $dataPoints) {
        //     //     static::processDataPoint($type, $dataPoint, $this);
        //     // foreach ($dataPoints as $dataPoint) {
        //     // }
        // }
        $this->searchOutput = $searchOutput;

        \Log::info("Search end");
        $this->onEnd(true);
        return $searchOutput;
    }

    protected function isSourceCompleted(): bool
    {
        return false;
    }

    protected function onStart()
    {
    }

    protected function onEnd(bool $hasData)
    {
        if ($this->isSourceCompleted()) {
            event(new \App\Events\OnSourceCompletedEvent(static::class));
        }

        if (!$hasData) {
            /*
            ## enable next combination level.
            $combinationService = loadService('combinations');
            $state = $combinationService->enableNextLevel(config('state.combination_id'));
            \Log::info("No Results Found, Enable next level.\n");
            \Log::info("Next level enabling status {$state}.\n");
            */
        } elseif ($hasData && $this->saveToPending) {
            $results = $this->purifyFromPending($this->getPendingResults());
            if (!empty($results)) {
                $this->savePendingResults($results);
            }
        }
    }

    public static function onSourceCompleted()
    {
        echo "On source completed ran for " . static::class . "\n";
    }

    public static function processDataPoint(string $type, DataTypeInterface $dataPoint, ManagerInterface $manager)
    {
        $checkResponse = $manager->checkDataPoint($dataPoint);
        if ($checkResponse) {
            if (!$manager->beforeDataPointSave($dataPoint)) {
                return;
            }
            $dataPoint->save();
        }
    }

    protected function checkDataPoint(string $type, DataTypeInterface $dataPoint)
    {
        return true;
    }

    public static function processResult(SearchResultInterface $result, ManagerInterface $manager)
    {
        \Log::info("Checking result: " . $result->url);
        $checkResponse = $manager->checkResult($result);
        \Log::info("End checking result: " . $result->url);

        if ($checkResponse) {
            if (!$manager->beforeResultSave($result)) {
                return;
            }

            ## save result to pending table.
            if ($manager->saveToPending) {
                $isPending = $manager->saveToPending($result);
                $result->setIsPending($isPending);
                return $isPending;
            }
            \Log::info("Saving result: " . $result->url);
            $result->save();
            \Log::info("Done saving result: " . $result->url);
        }
    }

    protected function checkResult(SearchResultInterface $result)
    {
        #load match service.
        $matchingService = loadService("matching");
        $profileInfo = [
            "name" => [],
            "location" => [],
            "work" => [],
            "school" => [],
            "email" => [],
            "phone" => [],
            "age"   =>  [],
            "username" => [],
        ];
        $this->getResultInfoForMatching($result, $profileInfo);
        $relationData = loadData('relationsFlags');
        foreach ($result->getLinks() as $linkData) {
            if (!empty($linkData['result']) && ($linkData['reason']&$relationData['insite']['value']) == $relationData['insite']['value']) {
                $profileInfo = $this->getResultInfoForMatching($linkData['result'], $profileInfo);
            }
        }
        $matchingService->setResultSource($result->mainSource);

        $matchStatus = $matchingService->check($profileInfo, [], $result->getIsRelative());
        \Log::info("Result profile info ({$result->url}) \n".print_r($profileInfo,true));
        \Log::info("Result matching status \n".print_r($matchStatus,true));
        
        ## set match status.
        $this->setMatchStatus($result, $matchStatus);

        return true;
    }

    protected function setMatchStatus(SearchResultInterface $result, array $matchStatus)
    {
        $result->setMatchStatus($matchStatus);

        ## sets match status into related links, if the relationship of type insite.
        $relationData = loadData('relationsFlags');
        $links = $result->getLinks();
        foreach ($links as $key => &$linkData) {
            if (!empty($linkData['result']) && ($linkData['reason']&$relationData['insite']['value']) == $relationData['insite']['value']) {
                $linkData['result'] = $linkData['result']->setMatchStatus($matchStatus);
            }
        }
    }

    protected function getResultInfoForMatching(SearchResultInterface $result, array &$profileInfo)
    {
        $profileInfo['name'] = $this->filterMatchingArray(array_merge($profileInfo['name'], iterator_to_array(DataType::getMainValues($result->getNames()), true))); 
        $profileInfo['location'] = $this->filterMatchingArray(array_merge($profileInfo['location'], iterator_to_array(DataType::getMainValues($result->getLocations()), true))); 
        $profileInfo['work'] = $this->filterMatchingArray(array_merge($profileInfo['work'], iterator_to_array(DataType::getMainValues($result->getExperiences()), true))); 
        $profileInfo['school'] = $this->filterMatchingArray(array_merge($profileInfo['school'], iterator_to_array(DataType::getMainValues($result->getEducations()), true))); 
        $profileInfo['email'] = $this->filterMatchingArray(array_merge($profileInfo['email'], iterator_to_array(DataType::getMainValues($result->getEmails()), true))); 
        $profileInfo['phone'] = $this->filterMatchingArray(array_merge($profileInfo['phone'], iterator_to_array(DataType::getMainValues($result->getPhones()), true))); 

        if (!empty($result->getAge())) {
            $ageIterator = new \ArrayIterator();
            $ageIterator->append($result->getAge());
            $profileInfo['age'] = $this->filterMatchingArray(array_merge($profileInfo['age'], iterator_to_array(DataType::getMainValues($ageIterator), true)));
        }
        if (!empty($result->getUsername())) {
            $usernameIterator = new \ArrayIterator();
            $usernameIterator->append($result->getUsername());
            $profileInfo['username'] = $this->filterMatchingArray(array_merge($profileInfo['username'], iterator_to_array(DataType::getMainValues($usernameIterator), true)));
        }

        return $profileInfo;
    }

    protected function filterMatchingArray(array $data)
    {
        return array_filter(array_unique($data));
    }

    protected function beforeResultSave(SearchResultInterface $result): bool
    {
        return true;
    }

    protected function beforeDataPointSave(DataTypeInterface $dataPoint): bool
    {
        return true;
    }

    protected function afterResultSave(SearchResultInterface $result)
    {
    }

    protected function afterDataPointSave(DataTypeInterface $dataPoint)
    {
    }

    protected function saveToPending(SearchResultInterface $result)
    {
        $resultService = loadService('result');
        $output = $resultService->saveToPending($result);

        return $output;
    }

    protected function purifyFromPending(\Iterator $results): \Iterator
    {
        ## purify results from pending table.
        return new \ArrayIterator();
    }

    protected function savePendingResults(\Iterator $results)
    {
        while ($results->valid()) {
            $result = $results->current();
            $result->save();
            $results->next();
        }
    }

    protected function getPendingResults(): \Iterator
    {
        $combination_level_id = config('state.combination_level_id');
        $resultService = loadService('result');
        $results = $resultService->getPendingResults(['combination_level_id' => $combination_level_id]);

        return $results;
    }

    protected function getInsiteProfilesNeedProfileInfo(SearchListInterface $searchOutput): array
    {
        $linksData = [];
        foreach ($searchOutput->getResults() as $result) {
            $result->subResultsNeedProfileInfo = [];
            $insiteLinks = $result->getLinks();
            foreach ($insiteLinks as $insiteLink) {
                $subResult = $insiteLink['result'];
                if ($subResult->getIsProfile()) {
                    $linksData[] = ['result' => $subResult, 'parent' => $result];
                }
            }
        }
        return $linksData;
    }

    protected function processProfilesInfo(array $resultsNeedProfileInfo)
    {
        \Log::info("Starting processProfilesInfo");
        if ($resultsNeedProfileInfo) {
            $urlinfo = loadService('UrlInfo');
            $request = loadService('HttpRequestsService');
            $urls = [];
            $syncUrls = [];
            foreach ($resultsNeedProfileInfo as $resultData) {
                $parent = $resultData['parent'];
                $result = $resultData['result'];

                $requestData = $urlinfo->getProfileInfoRequest($result->url);
                if (!$requestData) {
                    $syncUrls []= $result->url;
                    continue;
                }
                $urls []= $requestData['url'];

                $options = $requestData['options'];
                $options['max_retries'] = -1;
                $options['allow_redirects'] = true;
                $options['timeout'] = 10;
                $options['connect_timeout'] = 5;

                $result->html = "";
                $request->createRequest($requestData['url'], null, $requestData['method'], $options, function ($response) use ($result) {
                    $response = $response->getResponse();
                    $response->getBody()->rewind();
                    $body = $response->getBody()->getContents();

                    if (!$body) {
                        return;
                    }

                    $result->html = $body;
                }, function ($err) {
                });
            }

            \Log::info("Getting profile pages of:", $urls);
            $request->processRequests();
            \Log::info("Done getting profile pages");

            foreach ($resultsNeedProfileInfo as $resultData) {
                $parent = $resultData['parent'];
                $result = $resultData['result'];
                if (!$result->html && !in_array($result->url, $syncUrls)) {
                    continue;
                }
                $info = $urlinfo->getProfileInfo($result->url, $result->mainSource, $result->html?['body'=>$result->html]:[]);
                $result->html = "";
                // $this->loadProfileInfo($result, $result->html);
                $result->setProfileInfo($info);
            }

            // $parent->subResultsNeedProfileInfo = [];

        } else {
            \Log::info("No profiles needed to get info for");
        }

        \Log::info("Ending processProfilesInfo");
    } 

    public function setResultRunMainEvent(SearchResultInterface $result)
    {
        $result->Run_Main_Result_Event = true;

        foreach ($result->getLinks() as $linkData) {
            $linkData['result']->Run_Main_Result_Event = true;
        }
    }
}
