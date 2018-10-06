<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;
use App\Models\SearchCriteria;

abstract class AbstractSearchFetcher extends AbstractSource
{
    const SOURCE_NAME = "";
    const FETCHER_SOURCE = "";

    public function __construct(Criteria $criteria, ApiAccount $account, ClientInterface $client)
    {
        $this->inputCriteria = $criteria;
        $this->account = $account;
        $this->client = $client;
    }

    protected function doSearch($search_criteria)
    {
        if (!$search_criteria) {
            return false;
        }

        $is_cached = $this->getCached((array)$this->inputCriteria, "Fetcher_" . static::SOURCE_NAME);
        if (!$is_cached) {
            $search = loadService('search');
            $searchList = $search->fetch(static::FETCHER_SOURCE, $search_criteria);
            
            if ($searchList->getResults()->count() || $searchList->getDataPoints()) {
                $this->cacheResult((array)$this->inputCriteria, $searchList, "Fetcher_" . static::SOURCE_NAME);
            }
        } else {
            $searchList = $is_cached;
        }

        $this->search_result = $searchList;
        return true;
    }

    protected function processResponse(): array
    {
        $final_result = [];
        
        $results = $this->search_result->getResults();
        // loop through search results
        foreach ($results as $i => $result) {
            $locations = $result->getLocations();
            $names = $result->getNames();

            if (!$names->count() || !$locations->count()) {
                continue;
            }
            
            $final_result[$i] = new OutputModel;
            $final_result[$i]->result_rank = $this->result_rank;
            $final_result[$i]->link = static::FETCHER_SOURCE;
            $final_result[$i]->source = static::SOURCE_NAME;
            if (!empty($names->count())) {
                $names = iterator_to_array($names);
                $name = $names[0];
                $final_result[$i]->first_name = ucwords(strtolower($name['first_name']));
                $final_result[$i]->middle_name = ucwords(strtolower($name['middle_name']));
                $final_result[$i]->last_name = ucwords(strtolower($name['last_name']));
                $final_result[$i]->full_name = ucwords(strtolower($name['full_name']));
                
                unset($names[0]);
                foreach ($names as $name) {
                    $new_other_name = [];
                    $new_other_name['first_name'] = ucwords(strtolower($name['first_name']));
                    $new_other_name['middle_name'] = ucwords(strtolower($name['middle_name']));
                    $new_other_name['last_name'] = ucwords(strtolower($name['last_name']));
                    $new_other_name['full_name'] = ucwords(strtolower($name['full_name']));
                    $final_result[$i]->other_names[] = $new_other_name ;
                }
            } else {
                continue;
            }

            if (!empty($locations)) {
                foreach ($locations as $address) {
                    $new_address = [];
                    $new_address['street'] = $address['street']??"";
                    $new_address['city'] = $address['city']??"";
                    $new_address['state'] = $address['state']??"";
                    if (!empty($address['state'])) {
                        if (strlen($address['state']) >2) {
                            $new_address['state'] = strtoupper(states_abv($address['state']));
                        } else {
                            $new_address['state'] = strtoupper($address['state']);
                        }
                        $new_address['state'] = ( strlen($address['state']) >2)? ucwords(strtolower($address['state'])):strtoupper($address['state']);
                    }
                    $new_address['location'] = trim(((!empty($new_address['city'])&&$new_address['city'])?$new_address['city'].", " : "").($new_address['state']??""), ", ");
                    $new_address['address'] = trim((($new_address['street'])?$new_address['street'].", ":"").(($new_address['location'])?$new_address['location']:""), ", ");
                    $new_address['zip'] = $address['zip_code']??"";
                    if (!$final_result[$i]->city && $new_address['address']) {
                        $final_result[$i]->address = $new_address['address'] ;
                        $final_result[$i]->city = $new_address['city'];
                        $final_result[$i]->state = $new_address['state'];
                        $final_result[$i]->location = $new_address['location'] ;
                        $final_result[$i]->street = $new_address['street'];
                        $final_result[$i]->zip = $new_address['zip'] ;
                        continue;
                    }
                    $final_result[$i]->addresses[] = $new_address;
                }
                if (!$final_result[$i]->city) {
                    foreach ($final_result[$i]->addresses as $location) {
                        if ($location['city'] && $location['state']) {
                            $final_result[$i]->address = $location['address'] ;
                            $final_result[$i]->city = $location['city'];
                            $final_result[$i]->state = $location['state'];
                            $final_result[$i]->location = $location['location'] ;
                            $final_result[$i]->street = $location['street'];
                            $final_result[$i]->zip = $location['zip'] ;
                            break;
                        }
                    }
                }

                if (!$final_result[$i]->city && !empty($final_result[$i]->addresses)) {
                    $final_result[$i]->address = $final_result[$i]->addresses[0]['address'] ;
                    $final_result[$i]->city = $final_result[$i]->addresses[0]['city'];
                    $final_result[$i]->state = $final_result[$i]->addresses[0]['state'];
                    $final_result[$i]->location = $final_result[$i]->addresses[0]['location'] ;
                    $final_result[$i]->street = $final_result[$i]->addresses[0]['street'];
                    $final_result[$i]->zip = $final_result[$i]->addresses[0]['zip'] ;
                    unset($final_result[$i]->addresses[0]);
                    array_values($final_result[$i]->addresses);
                }
            }

            if ($phones = $result->getPhones()) {
                foreach ($phones as $phone) {
                    $final_result[$i]->phones[] = (string)$phone['phone'];
                }
            }
            if ($emails = $result->getEmails()) {
                foreach ($emails as $email) {
                    $final_result[$i]->emails[] = $email['email'];
                }
            }
            if ($experiences = $result->getExperiences()) {
                foreach ($experiences as $job) {
                    if (!empty($job['title']) && !empty($job['company'])) {
                        $final_result[$i]->companies[] = $job['title']." - ".$job['company'];
                    } elseif (!empty($job['title'])) {
                        $final_result[$i]->companies[] = $job['title'];
                    } elseif (!empty($job['company'])) {
                        $final_result[$i]->companies[] = $job['company'];
                    }
                }
            }

            if ($educations = $result->getEducations()) {
                foreach ($educations as $education) {
                    $final_result[$i]->educations[] = $education['name'];
                }
            }


            if ($result->username) {
                $final_result[$i]->usernames[] = $result->username->username;
            }

            if (!empty($result->image)) {
                $final_result[$i]->images[] = $result->image;
            }

            $urls = iterator_to_array($result->getLinks());
            if ($result->url && $result->url!="-") {
                array_unshift($urls, ['url'=>$result->url, 'reason'=>-1, 'id'=>$result->id]);
            }

            if (!empty($urls)) {
                foreach ($urls as $url) {
                    $profile = [];
                    $profile['domain'] = str_replace("www.", "", parse_url($url['url'], PHP_URL_HOST));
                    if (strpos($profile['domain'], 'm.')===0) {
                        $profile['domain'] = substr($profile['domain'], 2);
                    }

                    $profile['url']= $url['url'];
                    $final_result[$i]->profiles[] = $profile;
                }
            }
        }

        return $final_result;
    }

    protected function prepareCriteria()
    {
        $searchCriteria = new SearchCriteria;

        if ($this->inputCriteria->full_name) {
            $parts = name_parts($this->inputCriteria->full_name);
            $searchCriteria->first_name = $parts['first_name'];
            $searchCriteria->middle_name = $parts['middle_name'];
            $searchCriteria->last_name = $parts['last_name'];
        }
        $searchCriteria->username = $this->inputCriteria->username;
        $searchCriteria->city = $this->inputCriteria->city;
        $searchCriteria->state = $this->inputCriteria->state;
        $searchCriteria->address = $this->inputCriteria->address;
        $searchCriteria->birth_date = $this->inputCriteria->age;
        $searchCriteria->phone = $this->inputCriteria->phone;
        $searchCriteria->email = $this->inputCriteria->email;
        $searchCriteria->company = $this->inputCriteria->company;
        $searchCriteria->school = $this->inputCriteria->school;

        if ($searchCriteria == new SearchCriteria) {
            return null;
        }

        $searchCriteria->search_type = "PeopleData";

        return $searchCriteria;
    }
}
