<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\SearchList;

class WhoisFetcher extends AbstractFetcher
{
    const MAIN_SOURCE_NAME = "whois";

    protected $whoxyKey = "e9ddbbd683d80d4xp03bcd61620bf20e";

    //In this function check reiateria and select which way will be use [domain or reverse]
    protected function prepareRequest()
    {
        \Log::info("start Whois Fetcher");
        $sources = new \ArrayIterator();
        
        if ($this->criteria->domain) {
            $domainName = $this->criteria->domain;
            
            $sources->append([
                "title" => "Whoxy",
                "url" => "http://api.whoxy.com/?key=$this->whoxyKey&whois=$domainName"
            ]);
            $sources->append([
                "title" => "WhoDotIs",
                "url" => "https://who.is/whois/$domainName"
            ]);
            $sources->append([
                "title" => "WhoisDotCom",
                "url" => "http://www.whois.com/whois/$domainName"
            ]);
            $sources->append([
                "title" => "WhoisologyDotCom",
                "url" => "https://whoisology.com/archive_12/$domainName"
            ]);
        } else {
            $data = [];

            if (!empty($this->criteria->email)) {
                $data["email"] = $this->criteria->email;
            } else {
                if (!empty($this->criteria->full_name)) {
                    $data["name"] = $this->criteria->full_name;
                }
            }

            if (empty($data)) {
                $list = new SearchList(WhoisFetcher::MAIN_SOURCE_NAME);
                return $list;
            }

            $query = http_build_query($data);

            $sources->append([
                "title" => "WhoxyReverse",
                "url" => "http://api.whoxy.com/?key=$this->whoxyKey&format=json&reverse=whois&$query"
            ]);
            
        }
        \Log::debug("Requests will be :".json_encode($sources));
        return $sources;
    }

    //make requests from the chosen way
    protected function makeRequest()
    {
        foreach ($this->request as $source) {
            $namespace = "Skopenow\\Search\\Fetching\\Fetchers\\Whois\\";
            $fetcher = $namespace.$source["title"]."SourceFetcher";
            $sourceFetcher = new $fetcher();

            $response =  $sourceFetcher->makeRequest($source["url"]);
            if ($response["body"]) {
                return [$sourceFetcher, $response['body']];
            }
        }
        return [null, null];
    }

    //get data from response and return it
    protected function processResponse($response) : SearchListInterface
    {
        list($sourceFetcher, $body) = $response;
        if ($sourceFetcher) {
            return $sourceFetcher->processResponse($body, $this->criteria);
        }

        $list = new SearchList(WhoisFetcher::MAIN_SOURCE_NAME);
        $list->setUrl("");
        return $list;
    }
}
