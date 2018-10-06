<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Website;

class WhoxySourceFetcher implements DomainFetcherInterface
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

        $json = json_decode($body);

        if (empty($json)) {
            return $list;
        }

        if (!$json->status) {
            return $list;
        }

        if ($json->registrant_contact->full_name == "Registrant Privacy") {
            return $list;
        }

        $result = new SearchResult($url);

        $registName = "";
        if (isset($json->registrant_contact->full_name)) {
            $registName = $json->registrant_contact->full_name;
            $result->addName(
                Name::create(
                    ["full_name"=>$json->registrant_contact->full_name],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }

        if (isset($json->administrative_contact->full_name)
            && $registName != $json->administrative_contact->full_name
        ) {
            $result->addName(
                Name::create(
                    ["full_name"=>$json->administrative_contact->full_name],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }
        $registAddress = "";
        if (isset($json->registrant_contact)) {
            $registAddress = $this->getLocation($json->registrant_contact);
            if (!empty($registAddress["full_address"])) {
                $result->addLocation(
                    Address::create($registAddress, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }
        }

        if (isset($json->administrative_contact)) {
            $adminAddress = $this->getLocation($json->administrative_contact);
            if (!empty($adminAddress["full_address"])
                && $registAddress["full_address"] != $adminAddress["full_address"]
            ) {
                $result->addLocation(
                    Address::create($adminAddress, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }
        }

        $registEmail = "";
        if (isset($json->registrant_contact->email_address)) {
            $registEmail = $json->registrant_contact->email_address;
            $result->addEmail(
                Email::create(
                    ['email' => $json->registrant_contact->email_address],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }

        if (isset($json->administrative_contact->email_address)
            && $registEmail!= $json->administrative_contact->email_address
        ) {
            $result->addEmail(
                Email::create(
                    ['email' => $json->administrative_contact->email_address],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }

        $registPhone = "";
        if (isset($json->registrant_contact->phone_number)) {
            $registPhone = $json->registrant_contact->phone_number;
            $result->addPhone(
                Phone::create(
                    ['phone' => $json->registrant_contact->phone_number],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }

        if (isset($json->administrative_contact->phone_number)
            && $registPhone != $json->administrative_contact->phone_number
        ) {
            $result->addPhone(
                Phone::create(
                    ['phone' => $json->administrative_contact->phone_number],
                    WhoisFetcher::MAIN_SOURCE_NAME
                )
            );
        }

        $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));
        
        $result->source = WhoisFetcher::MAIN_SOURCE_NAME;
        $result->mainSource = WhoisFetcher::MAIN_SOURCE_NAME;
        $list->addResult($result);
        return $list;
    }

    protected function getLocation($obj)
    {
        $location = [
            "full_address" => "",
            "street" => "",
            "city" => "",
            "state" => "",
            'country' => "",
            'zip' => 0
        ];

        if (isset($obj->mailing_address)) {
            $location["street"] = $obj->mailing_address;
        }

        if (isset($obj->city_name)) {
            $location["city"] = $obj->city_name;
        }

        if (isset($obj->state_name)) {
            $location["state"] = $obj->state_name;
        }

        if (isset($obj->country_name)) {
            $location["country"] = $obj->country_name;
        }

        if (isset($obj->zip_code)) {
            $location["zip"] = $obj->zip_code;
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
}
