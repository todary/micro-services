<?php

namespace Skopenow\Search\Fetching;

use Skopenow\Search\Models\CriteriaInterface;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResultInterface;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Work;
use App\DataTypes\School;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Age;
use App\DataTypes\Username;

abstract class AbstractFetcher implements FetcherInterface
{
    /**
     * @var CriteriaInterface The requested data to be searched for
     */
    public $criteria;

    /**
     * @var SearchListInterface Search result
     */
    protected $output;

    /**
     * @var mixed Request info
     */
    protected $request;

    /**
     * @var int Max results to get from search list
     */
    public $maxResults = 5;

    /**
     * @var string The selected account for search
     * ["name", "location","image","experiences","educations","age","phones","emails","websites","insite_links"]
     */
    public $availableProfileInfo = ["name", "location", "image", "websites", "insite_links"];
    protected $resultsNeedProfileInfo = [];

    /**
     * Instantiate source search
     * @param CriteriaInterface $criteria The requested data to be searched for
     */
    public function __construct(CriteriaInterface $criteria)
    {
        $this->criteria = $criteria;
    }

    public function execute(): bool
    {
        $this->request = $this->prepareRequest();
        \Log::info("Start Request Data");
        $response = $this->makeRequest();

        \Log::info("Start processResponse");
        $searchList = $this->processResponse($response);
        \Log::info("End processResponse");

        $this->processProfilesInfo();

        $this->output = $searchList;

        $return = $this->output->getResults()->count() || !empty($this->output->getDataPoints());
        //$this->output->getResults() || $this->output->getDataPoints();
        $this->addLog($searchList);
        return $return;
    }

    abstract protected function prepareRequest();
    abstract protected function makeRequest();
    abstract protected function processResponse($response): SearchListInterface;

    protected function addLog($searchList)
    {
        $state = [
            "report_id" => config("state.report_id"),
            "combination_id" => config("state.combination_id"),
            "combination_level_id" => config("state.combination_level_id"),
            "environment" => env("APP_ENV")
        ];

        $countResultFound = $searchList->getResults()->count();
        $loggerData = [
            "criteria" => $this->criteria,
            "search_count" => $countResultFound
        ];

        if ($countResultFound > 0) {
            foreach ($searchList->getResults() as $result) {
                $resultData = clone($result);
                unset($resultData->searchList);
                $loggerData["url"] = $result->url;
                $loggerData["source"] = $result->source;
                $loggerData["resultData"] = $resultData;
                
                $logger = loadService("logger", [160]);
                $output = $logger->addLog($state, $loggerData);
            }
        }
    }

    protected function processProfilesInfo()
    {
        \Log::info("Starting processProfilesInfo");

        if ($this->resultsNeedProfileInfo) {
            $urlinfo = loadService('UrlInfo');
            $request = loadService('HttpRequestsService');
            $urls = [];
            $syncUrls = [];
            foreach ($this->resultsNeedProfileInfo as $result) {
                $requestData = $urlinfo->getProfileInfoRequest($result->url);
                if (!$requestData) {
                    $syncUrls []= $result->url;
                    continue;
                }
                $urls []= $requestData['url'];

                $options = $requestData['options'];
                $options['max_retries'] = -1;
                if ($result->mainSource != 'linkedin') {
                    $options['allow_redirects'] = true;
                }
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
            foreach ($this->resultsNeedProfileInfo as $result) {
                if (!$result->html && !in_array($result->url, $syncUrls)) {
                    continue;
                }
                $this->loadProfileInfo($result, $result->html);
                $result->html = "";
            }
        } else {
            \Log::info("No profiles needed to get info for");
        }

        \Log::info("Ending processProfilesInfo");
    }
    protected function onDataPointFound(DataPoint $dataPoint): bool
    {
        return true;
    }

    protected function onResultFound(SearchResultInterface $result): bool
    {
        \Log::info("Found Result:" . $result->url);

        if ($result->getIsProfile()) {
            if ((in_array("image", $this->availableProfileInfo) && empty($result->image)) ||
                (in_array("name", $this->availableProfileInfo) && !$result->getNames()->count()) ||
                (in_array("location", $this->availableProfileInfo) && !$result->getLocations()->count()) ||
                (in_array("insite_links", $this->availableProfileInfo) && !$result->getLinks()->count()) ||
                $result->username === null) {
                $this->resultsNeedProfileInfo []= $result;
            }
        }

        return true;
    }

    public function loadProfileInfo(SearchResultInterface $result, string $html = '')
    {
        \Log::info("Load profile info for: " . $result->url . (($html)?' using predefined html body':' using new request'));

        $entryPoint = loadService('UrlInfo');
        $info = $entryPoint->getProfileInfo($result->url, $result->mainSource, $html?['body'=>$html]:[]);
        
        ## add emails & phones from criteria to info.
        if (!empty($this->criteria->phone)) {
            $info['phones'][] = $this->criteria->phone;
        }

        if (!empty($this->criteria->email)) {
            $info['emails'][] = $this->criteria->email;
        }

        $logInfo = $info;
        unset($logInfo['body']);
        unset($logInfo['profile']);

        \Log::info("Setting profile info for:" . $result->url, [$logInfo]);

        $result->setProfileInfo($info);

        \Log::info("Done setting profile info for:" . $result->url);
    }

    public function getOutput(): SearchListInterface
    {
        return $this->output;
    }
}
