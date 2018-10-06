<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Website;

class WhoxyReverseSourceFetcher implements ReverseFetcherInterface
{
    public function makeRequest(string $url)
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($url, 'GET', []);
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

        $json = json_decode($body, true);

        if ($json['total_results'] == 0 ||$json['status'] = 0) {
            return $list;
        }
        $domains = [];

        foreach ($json['search_result'] as $item) {
            $url = "http://".$item['domain_name'];
            if (in_array($url, $domains)) {
                continue;
            }
            $domains[] = $url;

            $result = new SearchResult($url);

            $registName = "";
            if (!empty($item['registrant_contact']['full_name']) && trim($item['registrant_contact']['full_name'])) {
                $registName = $item['registrant_contact']['full_name'];
                $result->addName(
                    Name::create(
                        ["full_name"=>$item['registrant_contact']['full_name']],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            if (!empty($item['administrative_contact']['full_name']) && trim($item['administrative_contact']['full_name'])) {
                if ($registName != $item['administrative_contact']['full_name']) {
                    $result->addName(
                        Name::create(
                            ["full_name"=>$item['administrative_contact']['full_name']],
                            WhoisFetcher::MAIN_SOURCE_NAME
                        )
                    );
                }
            }

            $registAddress = "";
            if (!empty($item['registrant_contact'])) {
                $registAddress = $this->getLocation($item['registrant_contact']);

                if (!empty($registAddress["full_address"])) {

                    $result->addLocation(
                        Address::create($registAddress, WhoisFetcher::MAIN_SOURCE_NAME)
                    );
                }
            }

            $adminAddress = "";
            if (!empty($item['administrative_contact'])) {
                $adminAddress = $this->getLocation($item['administrative_contact']);
                if (!empty($adminAddress["full_address"])
                    && $registAddress["full_address"] != $adminAddress["full_address"]
                ) {
                    $result->addLocation(
                        Address::create($adminAddress, WhoisFetcher::MAIN_SOURCE_NAME)
                    );
                }
            }

            $registEmail = "";
            if (!empty($item['registrant_contact']['email_address']) && trim($item['registrant_contact']['email_address'])
                && filter_var($item['registrant_contact']['email_address'], FILTER_VALIDATE_EMAIL)
            ) {
                $registEmail = $item['registrant_contact']['email_address'];
                $result->addEmail(
                    Email::create(
                        ['email' => $registEmail],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            if (!empty($item['administrative_contact']['email_address']) && trim($item['administrative_contact']['email_address'])
                && $registEmail != $item['administrative_contact']['email_address']
                && filter_var($item['administrative_contact']['email_address'], FILTER_VALIDATE_EMAIL)
            ) {
                $result->addEmail(
                    Email::create(
                        ['email' => $item['administrative_contact']['email_address']],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $registPhone = "";
            if (!empty($item['registrant_contact']['phone_number']) && trim($item['registrant_contact']['phone_number'])) {
                $registPhone = $item['registrant_contact']['phone_number'];
                $result->addPhone(
                    Phone::create(
                        ['phone' => $item['registrant_contact']['phone_number']],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            if (!empty($item['administrative_contact']['phone_number']) && trim($item['administrative_contact']['phone_number'])
                && $registPhone != $item['administrative_contact']['phone_number']
            ) {
                $result->addPhone(
                    Phone::create(
                        ['phone' => $item['administrative_contact']['phone_number']],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $result->addWebsite(Website::create(['url' =>$item['domain_name']], WhoisFetcher::MAIN_SOURCE_NAME));
            
            $result->source = WhoisFetcher::MAIN_SOURCE_NAME;
            $result->mainSource = WhoisFetcher::MAIN_SOURCE_NAME;

            $list->addResult($result);
        }
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

        if (isset($obj['mailing_address'])) {
            $location["street"] = $obj['mailing_address'];
        }

        if (isset($obj['city_name'])) {
            $location["city"] = $obj['city_name'];
        }

        if (isset($obj['state_name'])) {
            $location["state"] = $obj['state_name'];
        }

        if (isset($obj['country_name'])) {
            $location["country"] = $obj['country_name'];
        }

        if (isset($obj['zip_code'])) {
            $location["zip"] = $obj['zip_code'];
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
