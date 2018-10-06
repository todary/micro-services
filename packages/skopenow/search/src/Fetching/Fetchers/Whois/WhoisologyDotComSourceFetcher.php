<?php

namespace Skopenow\Search\Fetching\Fetchers\Whois;

use Skopenow\Search\Fetching\Fetchers\Whois\WhoisFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Website;

class WhoisologyDotComSourceFetcher implements DomainFetcherInterface
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
        preg_match('/<h2>Registrant Contact(.*?)<\/div>/ims', $content, $registrantContent);

        preg_match("/<h2>Admin Contact(.*?)<\/div>/ims", $content, $adminContent);

        if (isset($registrantContent[1])) {
            $registName = $this->getRegistName($registrantContent[1]);
            if (!empty($registName)) {
                $result->addName(
                    Name::create(
                        ["full_name"=>$registName],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $registLocation = $this->getRegistLocation($registrantContent[1]);
            if (!empty($registLocation["full_address"])) {
                $result->addLocation(
                    Address::create($registLocation, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }

            $registphone = $this->getRegistPhone($registrantContent[1]);
            if (!empty($registphone)) {
                $result->addPhone(
                    Phone::create(
                        ['phone' => $registphone],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $registemail = $this->getRegistEmail($registrantContent[1]);
            if (!empty($registemail)) {
                $result->addEmail(
                    Email::create(
                        ['email' => $registemail],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }
        }

        if (isset($adminContent[1])) {
            $adminName = $this->getAdminName($adminContent[1]);
            if ($registName != $adminName && !empty($adminName)) {
                $result->addName(
                    Name::create(
                        ["full_name"=>$adminName],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $adminLocation = $this->getAdminLocation($adminContent[1]);
            if (!empty($adminLocation["full_address"])
                && $adminLocation["full_address"] !== $registLocation["full_address"]
                && !empty($adminLocation["full_address"])
            ) {
                $result->addLocation(
                    Address::create($adminLocation, WhoisFetcher::MAIN_SOURCE_NAME)
                );
            }

            $adminphone = $this->getAdminPhone($adminContent[1]);
            if ($adminphone !== $registphone && !empty($adminphone)) {
                $result->addPhone(
                    Phone::create(
                        ['phone' => $adminphone],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $adminemail = $this->getAdminEmail($adminContent[1]);
            if ($adminphone !== $registphone && !empty($adminphone)) {
                $result->addEmail(
                    Email::create(
                        ['email' => $adminemail],
                        WhoisFetcher::MAIN_SOURCE_NAME
                    )
                );
            }

            $result->addWebsite(Website::create(['url' =>$criteria->domain], WhoisFetcher::MAIN_SOURCE_NAME));
        }

        $result->source = WhoisFetcher::MAIN_SOURCE_NAME;
        $result->mainSource = WhoisFetcher::MAIN_SOURCE_NAME;
        $list->addResult($result);
        return $list;
    }

    protected function getRegistName($content)
    {
        $ex = "/ id='registrant_name'>(.*?)<\/a>/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getAdminName($content)
    {
        $ex = "/ id='admin_name'>(.*?)<\/a>/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getRegistLocation($content)
    {
        $location = [
            "full_address" => "",
            "street" => "",
            "city" => "",
            "state" => "",
            'country' => "",
            'zip' => 0
        ];
        $exAddress = "/ id='registrant_street1'>(.*?)<\/a>/ims";
        preg_match($exAddress, $content, $matches);
        if (isset($matches[1])) {
            $location["street"] = $matches[1];
        }

        $exCity = "/ id='registrant_city'>(.*?)<\/a>/ims";
        preg_match($exCity, $content, $matches);
        if (isset($matches[1])) {
            $location["city"] = $matches[1];
        }

        $exState = "/ id='registrant_state'>(.*?)<\/a>/ims";
        preg_match($exState, $content, $matches);
        if (isset($matches[1])) {
            $location["state"] = $matches[1];
        }

        $exCountry = "/ id='registrant_country'>(.*?)<\/a>/ims";
        preg_match($exCountry, $content, $matches);
        if (isset($matches[1])) {
            $location["country"] = $matches[1];
        }

        $exZip = "/ id='registrant_postal_code'>(.*?)<\/a>/ims";
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

    protected function getAdminLocation($content)
    {
        $location = [
            "full_address" => "",
            "street" => "",
            "city" => "",
            "state" => "",
            'country' => "",
            'zip' => 0
        ];
        $exAddress = "/ id='admin_street1'>(.*?)<\/a>/ims";
        preg_match($exAddress, $content, $matches);
        if (isset($matches[1])) {
            $location["street"] = $matches[1];
        }

        $exCity = "/ id='admin_city'>(.*?)<\/a>/ims";
        preg_match($exCity, $content, $matches);
        if (isset($matches[1])) {
            $location["city"] = $matches[1];
        }

        $exState = "/ id='admin_state'>(.*?)<\/a>/ims";
        preg_match($exState, $content, $matches);
        if (isset($matches[1])) {
            $location["state"] = $matches[1];
        }

        $exCountry = "/ id='admin_country'>(.*?)<\/a>/ims";
        preg_match($exCountry, $content, $matches);
        if (isset($matches[1])) {
            $location["country"] = $matches[1];
        }

        $exZip = "/ id='admin_postal_code'>(.*?)<\/a>/ims";
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

    protected function getRegistPhone($content)
    {
        $ex = "/ id='registrant_telephone'>(.*?)<\/a>/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getAdminPhone($content)
    {
        $ex = "/ id='admin_telephone'>(.*?)<\/a>/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getRegistEmail($content)
    {
        $ex = "/id='registrant_email' title=\"(.*?)\">/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }

    protected function getAdminEmail($content)
    {
        $ex = "/id='admin_email' title=\"(.*?)\">/ims";
        preg_match($ex, $content, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return "";
    }
}