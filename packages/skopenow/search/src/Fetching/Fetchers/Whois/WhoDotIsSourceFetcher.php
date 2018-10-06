<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Website;

class WhoDotIsSourceFetcher implements DomainFetcherInterface
{
    public function makeRequest(string $url)
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($url, 'GET', []);
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];

        } catch (\Exception $ex) {
            return ['body' => ''];
        }
    }

    public function processResponse(string $body, $criteria)
    {
        $list = new SearchList(WhoisFetcher::MAIN_SOURCE_NAME);

        $url = "http://".$criteria->domain;
        
        $list->setUrl("");

        $content = html_entity_decode(str_replace("&nbsp;", " ", $body));
        if (stripos($content, "Registrant Privacy") !== false) {
            return $list;
        }
        
        $result = new SearchResult($url);
        preg_match("/Registrant Contact Information:(.+)<img/i", $content, $registrantContent);

        preg_match("/Administrative Contact Information:(.+)<img/i", $content, $adminContent);

        if (isset($registrantContent[1])) {
            $registName = $this->getName($registrantContent[1]);
            if (!empty($registName)) {
                $result->addName(
                    Name::create(
                        ["full_name"=>$registName],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $registLocation = $this->getLocation($registrantContent[1]);
            if (!empty($registLocation["full_address"])) {
                $result->addLocation(
                    Address::create($registLocation, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }

            $registphone = $this->getPhone($registrantContent[1]);
            if (!empty($registphone)) {
                $result->addPhone(
                    Phone::create(
                        ['phone' => $registphone],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }
        }

        if (isset($adminContent[1])) {
            $adminName = $this->getName($adminContent[1]);
            if ($registName != $adminName && !empty($adminName)) {
                $result->addName(
                    Name::create(
                        ["full_name"=>$adminName],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $adminLocation = $this->getLocation($adminContent[1]);
            if (!empty($adminLocation["full_address"])
                && $adminLocation["full_address"] !== $registLocation["full_address"]
                && !empty($adminLocation["full_address"])
            ) {
                $result->addLocation(
                    Address::create($adminLocation, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }

            $adminphone = $this->getPhone($adminContent[1]);
            if ($adminphone !== $registphone && !empty($adminphone)) {
                $result->addPhone(
                    Phone::create(
                        ['phone' => $adminphone],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }
        }
        $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));
        $result->source = WhoisFetcher::MAIN_SOURCE_NAME;
        $result->mainSource = WhoisFetcher::MAIN_SOURCE_NAME;
        $list->addResult($result);
        return $list;
    }

    protected function getName($content)
    {
        $ex = '/Name<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getLocation($content)
    {
        $location = [
            "full_address" => "",
            "street" => "",
            "city" => "",
            "state" => "",
            'country' => "",
            'zip' => 0
        ];
        $exAddress = '/Address<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($exAddress, $content, $matches);
        if (isset($matches[1])) {
            $location["street"] = $matches[1];
        }

        $exCity = '/City<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($exCity, $content, $matches);
        if (isset($matches[1])) {
            $location["city"] = $matches[1];
        }

        $exState = '/State \/ Province<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($exState, $content, $matches);
        if (isset($matches[1])) {
            $location["state"] = $matches[1];
        }

        $exCountry = '/Country<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($exCountry, $content, $matches);
        if (isset($matches[1])) {
            $location["country"] = $matches[1];
        }

        $exZip = '/Postal Code<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($exZip, $content, $matches);
        if (isset($matches[1])) {
            $location["zip"] = $matches[1];
        }

        $location["full_address"] = "";
        if (isset($location["street"])) {
            $location["full_address"] .= trim($location["street"], ",").", ";
        }

        if (isset($location["city"])) {
            $location["full_address"] .= trim($location["city"], ",").", ";
        }

        if (isset($location["state"])) {
            $location["full_address"] .= trim($location["state"], ",").", ";
        }

        $location["full_address"] = trim($location["full_address"], ", ");

        return $location;
    }

    protected function getPhone($content)
    {
        $ex = '/Phone<\/strong><\/div><div class="col-md-7">(.*?)<\/div>/i';
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }
}