<?php

namespace Skopenow\Search\Managing\Managers;

use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;
use App\Models\ResultData;

class GoogleManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "google";

    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;

    protected function checkResult(SearchResultInterface $result)
    {
        // Do not get youtube videos from google
        if ($result->mainSource == "youtube" && stripos($result->url, '/watch')) {
            return false;
        }

        // 1. check banned
        $return = array("status"=>false);
        $bannedList = array("White Pages","Whitepages","phone","lookup","reverse","email","address","directory","background","checks");

        $url = $result->url;
        if (!empty($result->url) && stripos($result->url, "http://") === false && stripos($result->url, "https://") === false) {
            $url = "http://".$result->url;
        }

        $parse_url = parse_url($url);
        $host = "";
        if (!empty($parse_url['host'])) {
            if (stripos($parse_url['host'], "www.") !== false) {
                $parse_url['host'] = str_ireplace("www.", "", $parse_url['host']);
                $host= $parse_url['host'];
            }
        }

        $title = trim($result->title);
        $url_status = false;
        $values = implode("|", $bannedList);

        $values_profile = implode("|", $bannedList);
        $ban_reason = "";

        $pattern = "/(".$values_profile.")/i";
        if (!empty($host) && preg_match($pattern, $host, $match)) {
            if (!empty($match[1])) {
                $url_status = true ;
                $ban_reason .= "Matching [{$match[1]}] with the url (".$host.") \n";
                $return = array("status"=>true,"reason" => $ban_reason);
            }
        }

        $pattern = "/([\\/ \"'.,_-]|[^a-z0-9])(".$values.")([\\/ \"'.,_-]|[^a-z0-9]*)/i";
        if (!empty($title) && preg_match($pattern, $title, $match)) {
            if (!empty($match[2])) {
                $ban_reason .= "Matching [{$match[2]}] with the title (".$title.") \n";
                $return = array("status"=>true,"reason" => $ban_reason);
            }
        }

        if (!empty($result->description) && preg_match($pattern, $result->description, $match)) {
            if (!empty($match[1])) {
                $ban_reason .= "Matching [{$match[2]}] with the descrip (".$result->description.") \n";
                $return = array("status"=>true,"reason" => $ban_reason);
            }
        }

        if (!empty($host) && !empty($ban_reason) && $url_status) {
            $check = \App\Models\BannedDomains::where('domain', $host)->first();
            if (!isset($check->domain)) {
                $model = new \App\Models\BannedDomains();
                $model->domain = $host;
                $model->save();
            }
        }

        if ($return["status"]) {
            return false;
        }

        // Todo exact Name Match
        // Todo exact Address Match

        if (!$result->getIsProfile()) {
            $this->addAdditionalIdentifiers($result);
        }
        return parent::checkResult($result);
    }

    public function addAdditionalIdentifiers(ResultData $result)
    {
        $name = [
            $this->fetcher->criteria->first_name,
            $this->fetcher->criteria->middle_name,
            $this->fetcher->criteria->last_name,
        ];
        $name = array_filter($name);
        $name = implode(' ', $name);
        $result->addAdditionalIdentifier($name);

        $location = [
            $this->fetcher->criteria->city,
            $this->fetcher->criteria->state,
        ];
        $location = implode(', ', array_filter($location));
        $result->addAdditionalIdentifier($location);

        $result->addAdditionalIdentifier($this->fetcher->criteria->company);
        $result->addAdditionalIdentifier($this->fetcher->criteria->school);
        $result->addAdditionalIdentifier($this->fetcher->criteria->email);
        $result->addAdditionalIdentifier($this->fetcher->criteria->phone);
        $result->addAdditionalIdentifier($this->fetcher->criteria->address);
    }
}
