<?php

/**
 * Flickr search
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;

class FullcontactFetcher extends AbstractFetcher
{
    
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "fullcontact";

    public $availableProfileInfo = ['name'];
    
    protected function prepareRequest()
    {

        $apikey = config('settings.fullcontact_key');

        include_once __DIR__.'/../../Libraries/fullcontact/Services/FullContact.php';

        //initialize our FullContact API object
        $fullcontact = new \Services_FullContact_Person($apikey);

        return ['url'=>'','fullcontact'=>$fullcontact,'method'=>'lookupByEmail', 'search'=>$this->criteria->email];
    }
    
    protected function makeRequest()
    {
        try {
            $fullcontact = $this->request['fullcontact'];
            $method = $this->request['method'];
            $response = $fullcontact->$method($this->request['search']);

            return ['body' => $response];
        } catch (\Exception $ex) {
            return ['body' => ''];
        }
    }
    
    protected function processResponse($response) : SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);
     
        $data = $response['body'];
        if (empty($data)) {
            return $list;
        }

        $urlInfo = loadService('urlInfo');

        if (!empty($data->contactInfo)) {
            $list->addDataPoint('name', \App\DataTypes\Name::create(['full_name'=>$data->contactInfo->fullName], self::MAIN_SOURCE_NAME));

            if (!empty($data->contactInfo->websites)) {
                foreach ($data->contactInfo->websites as $website) {
                    $domains = explode(",", $website->url);
                    foreach ($domains as $domain) {
                        $domain = trim($domain);
                        if ($urlInfo->isDomain($domain)) {
                            $list->addDataPoint('website', \App\DataTypes\Website::create(['url'=>$domain], self::MAIN_SOURCE_NAME));
                        }
                    }
                }
            }
        }

        /*
        if (!empty($data->photos)) {
            $mainData->image = $data->photos[0]->url;
        }
        */

        if (!empty($data->organizations)) {
            foreach ($data->organizations as $organization) {
                $list->addDataPoint('work', \App\DataTypes\Work::create(['company'=>$organization->name, 'title'=>$organization->title??null, 'start'=>$organization->startDate??null, 'end'=>$organization->endDate??null], self::MAIN_SOURCE_NAME));
            }
        }


        if (!empty($data->demographics->locationDeduced)) {
            $list->addDataPoint('address', \App\DataTypes\Address::create(['full_address'=>$data->demographics->locationDeduced->normalizedLocation, 'city'=>$data->demographics->locationDeduced->city->name??null, 'state'=>$data->demographics->locationDeduced->state->name??null, 'country'=>$data->demographics->locationDeduced->country->name??null], self::MAIN_SOURCE_NAME));
        }

        if (!empty($data->socialProfiles)) {
            foreach ($data->socialProfiles as $profile) {
                $mainData = new SearchResult($profile->url);
                
                if ($this->onResultFound($mainData)) {
                    $list->addResult($mainData);
                }
            }
        }

        return $list;
    }
}
