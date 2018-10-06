<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Pipl extends AbstractSource
{
    /**
     * @var String the api source .
     */
    public $source = "pipl";
    /**
     * @var String the link that will call api
     */
    protected $url = "http://api.pipl.com/search/";
    /**
     * @var String the key tha used in calling api
     */
    protected $key;

    protected $result_rank = 2;

    protected $detailed_results = [];

    public function __construct(Criteria $criteria, ApiAccount $account, ClientInterface $client)
    {
        $this->inputCriteria = $criteria;
        $this->key = $account->password;
        $this->accounts_status = true;
        $this->client = $client;
    }
    protected function prepareCriteria()
    {
        // TODO: Make it as array and overridable by the client

        $search_criteria = [];

        if ($this->inputCriteria->full_name) {
            $search_criteria['raw_name'] = $this->inputCriteria->full_name;
        }
        if ($this->inputCriteria->phone) {
            /*
            $inputs = ["phones" => [$this->inputCriteria->phone]];
            $phonesInput = new \ArrayIterator($inputs);
            $formaterObj = loadService("formatter");
            $output = $formaterObj->format($phonesInput);
            $search_criteria['phone'] = $output;
            */
            $search_criteria['phone'] = $this->inputCriteria->phone;
        }
        if ($this->inputCriteria->email) {
            $search_criteria['email'] = $this->inputCriteria->email;
        }
        if ($this->inputCriteria->age) {
            $search_criteria['age'] = ($this->inputCriteria->age - 5)."-".($this->inputCriteria->age + 5);
        }
        $address = [];
        if ($this->inputCriteria->address) {
            $address []= $this->inputCriteria->address;
        }
        if ($this->inputCriteria->city) {
            $address []= $this->inputCriteria->city;
        }
        if ($this->inputCriteria->state) {
            $address []= $this->inputCriteria->state;
        }
        if ($this->inputCriteria->country) {
            $address []= $this->inputCriteria->country;
        }

        if ($address) {
            $search_criteria['raw_address'] = implode(", ", $address);
        }

        if (!$search_criteria) {
            return null;
        }

        $search_criteria = ['key'=>$this->key??"", 'minimum_match'=>0.1, 'hide_sponsored'=>'true'] + $search_criteria;

        if ($this->inputCriteria->api_options) {
            $search_criteria = $this->inputCriteria->api_options + $search_criteria;
        }

        return $search_criteria;
    }

    protected function doSearch($search_criteria)
    {
        if (!$search_criteria) {
            return false;
        }

        $search_query = http_build_query($search_criteria);

        $search_param = $this->url."?".$search_query;

        try {
            $is_cached = $this->getCached((array)$search_criteria, "Pipl");
            if (!$is_cached) {
                $output = $this->client->call($search_param);
                if (isset($output)) {
                    $result = json_decode($output, true);
                    $output = json_decode($output);
                }

                if (isset($result['@http_status_code']) && $result['@http_status_code']== 200 && !isset($result['error'])) {
                    $this->cacheResult((array)$search_criteria, $output, "Pipl");
                }
            } else {
                $output = $is_cached;
                \Log::debug('PIPL got response from Cache: ' . $search_param, [$output]);
            }

            if ($output) {
                $this->search_json_result = $output;
            }

            if (!is_array($output)) {
                $result = json_decode(json_encode($output), true);
            } else {
                $result = $output;
            }

            if (isset($result['@http_status_code']) && $result['@http_status_code']!= 200) {
                if (isset($result['error'])) {
                    $this->search_error_code = $result['@http_status_code'];
                }
            } else {
                if (!empty($result['person'])) {
                    $this->search_result[] = (array) $result['person'];
                } else if (!empty($result['possible_persons'])) {
                    $this->search_modified = true;
                    $this->search_result = (array) $result['possible_persons'];

                    $request = loadService('HttpRequestsService');
                    foreach ($this->search_result as $result) {
                        if (empty($result['@search_pointer'])) {
                            continue;
                        }
                        $requestData = [
                            'key' => $search_criteria['key'],
                            'search_pointer' => $result['@search_pointer'],
                        ];
                        $url = $this->url."?". http_build_query($requestData);

                        $options = [];
                        $options['max_retries'] = 1;
                        $options['timeout'] = app()->environment(['production'])?2:10;
                        $options['connect_timeout'] = app()->environment(['production'])?2:10;
                        $options['ignore_auto_select_ip'] = 1;

                        $request->createRequest($url, null, 'GET', $options, function ($response) use ($result) {
                            $response = $response->getResponse();
                            $response->getBody()->rewind();
                            $body = $response->getBody()->getContents();

                            if (!$body) {
                                return;
                            }

                            $detailedResult = json_decode($body, true);

                            $search_pointer = $detailedResult['query']['@search_pointer']??null;
                            if (!$search_pointer) {
                                return;
                            }

                            $this->detailed_results[$search_pointer] = $detailedResult;
                        }, function ($err) {
                        });
                    }

                    \Log::debug('Getting detailed results...');

                    $request->processRequests();

                    \Log::debug('Done getting detailed results');

                    foreach ($this->search_result as &$result) {
                        if (empty($result['@search_pointer'])) {
                            continue;
                        }

                        $detailed_result = $this->detailed_results[$result['@search_pointer']]??null;

                        if (!$detailed_result) {
                            continue;
                        }

                        $result['@id'] = $detailed_result['person']['@id']??null;
                        $result['names'] = $detailed_result['person']['names']??[];
                        $result['addresses'] = $detailed_result['person']['addresses']??[];
                        $result['relationships'] = $detailed_result['person']['relationships']??[];
                        $result['phones'] = $detailed_result['person']['phones']??[];
                        $result['emails'] = $detailed_result['person']['emails']??[];
                        $result['jobs'] = $detailed_result['person']['jobs']??[];
                        $result['educations'] = $detailed_result['person']['educations']??[];
                        $result['usernames'] = $detailed_result['person']['usernames']??[];
                        $result['images'] = $detailed_result['person']['images']??[];
                        $result['urls'] = $detailed_result['person']['urls']??[];
                        $result['dob'] = $detailed_result['person']['dob']??[];
                    }
                } else {
                    $this->search_result = [];
                }
            }

            return !empty($this->search_result);
        } catch (Exception $e) {
            return false;
        }
    }

    protected function processResponse(): array
    {
        $final_results = [];

        $highestMatch = 0;
        // loop through search results
        foreach ($this->search_result as $i => $result) {
            if (empty($result['addresses']) || empty($result['names']) || (empty($result['urls']) && empty($result['usernames']))) {
                continue;
            }

            $final_result = new OutputModel;
            $final_result->result_rank = $this->result_rank;
            $final_result->modified = $this->search_modified;
            $final_result->link = "Pipl.api";
            $final_result->source = $this->source;
            $final_result->match_ratio = $result['@match']??null;

            if ($final_result->match_ratio) {
                $highestMatch = max($highestMatch, $final_result->match_ratio);
            }

            if (isset($result['names'])) {
                $final_result->first_name = ucwords(strtolower($result['names'][0]['first']));
                $final_result->middle_name = ucwords(strtolower($result['names'][0]['middle'] ??""));
                $final_result->last_name = ucwords(strtolower($result['names'][0]['last']));
                $final_result->full_name = ucwords(strtolower($result['names'][0]['display']??""));
                $final_result->gender = $result['gender']['content']??"";
                unset($result['names'][0]);
                foreach ($result['names'] as $name) {
                    $new_other_name = [];
                    $new_other_name['first_name'] = ucwords(strtolower($name['first']??""));
                    $new_other_name['middle_name'] = ucwords(strtolower($name['middle']??""));
                    $new_other_name['last_name'] = ucwords(strtolower($name['last']??""));
                    $new_other_name['full_name'] = ucwords(strtolower($name['display']??""));
                    $final_result->other_names[] = $new_other_name ;
                }
            } else {
                continue;
            }

            foreach ($result['addresses'] as $address) {
                $new_address = [];
                $new_address['street'] = ((isset($address['house'])?$address['house']." ":"")).($address['street']??"");
                $new_address['city'] = $address['city']??"";
                $new_address['state'] = "";
                if (isset($address['state'])) {
                    if (strlen($address['state']) >2) {
                        $new_address['state'] = strtoupper(states_abv($address['state']));
                    } else {
                        $new_address['state'] = strtoupper($address['state']);
                    }
                    $new_address['state'] = ( strlen($address['state']) >2)? ucwords(strtolower($address['state'])):strtoupper($address['state']);
                }
                $new_address['location'] = trim(((isset($new_address['city'])&&$new_address['city'])?$new_address['city'].", " : "").($new_address['state']??""), ", ");
                
                $new_address['country'] = "US";
                if (!empty($address['country'])) {
                    $new_address['country'] = $address['country'];
                }

                if (!empty($address['display'])) {
                    $new_address['address'] = $address['display'];
                } else {
                    $new_address['address'] = trim((($new_address['street'])?$new_address['street'].", ":"").(($new_address['location'])?$new_address['location']:""), ", ");
                }
                $new_address['zip'] = $address['zip_code']??"";
                if (!$final_result->address && $new_address['address']) {
                    $final_result->address = $new_address['address'] ;
                    $final_result->city = $new_address['city'];
                    $final_result->state = $new_address['state'];
                    $final_result->location = $new_address['location'] ;
                    $final_result->street = $new_address['street'];
                    $final_result->zip = $new_address['zip'] ;
                    continue;
                }

                if ($new_address['address']) {
                    $final_result->addresses[] = $new_address;
                }
            }

            /*
            if (!$final_result->city) {
                foreach ($final_result->addresses as $location) {
                    if ($location['city'] && $location['state']) {
                        $final_result->address = $location['address'] ;
                        $final_result->city = $location['city'];
                        $final_result->state = $location['state'];
                        $final_result->location = $location['location'] ;
                        $final_result->street = $location['street'];
                        $final_result->zip = $location['zip'] ;
                        break;
                    }
                }
            }
            if (!$final_result->city && !empty($final_result->addresses)) {
                $final_result->address = $final_result->addresses[0]['address'] ;
                $final_result->city = $final_result->addresses[0]['city'];
                $final_result->state = $final_result->addresses[0]['state'];
                $final_result->location = $final_result->addresses[0]['location'] ;
                $final_result->street = $final_result->addresses[0]['street'];
                $final_result->zip = $final_result->addresses[0]['zip'] ;
                unset($final_result->addresses[0]);
                array_values($final_result->addresses);
            }
            */

            if (isset($result['relationships'])) {
                foreach ($result['relationships'] as $relative) {
                    if (!empty($relative['@type'])) {
                        $new_relative = [];
                        $new_relative['first_name'] = ucwords(strtolower($relative['names'][0]['first']??""));
                        $new_relative['middle_name'] = ucwords(strtolower($relative['names'][0]['middle']??""));
                        $new_relative['last_name'] = ucwords(strtolower($relative['names'][0]['last']??""));
                        $new_relative['full_name'] = ucwords(strtolower($relative['names'][0]['display']??""));
                        $new_relative['zip'] ="";
                        $new_relative['ct'] =$final_result->city;
                        $new_relative['st'] ="";
                        $new_relative['location'] = $final_result->city;
                        $new_relative['address'] = "";
                        $new_relative['db'] ="";

                        if ($relative['@type'] != "family") {
                            $new_relative['other_relative'] = true;
                        }

                        $final_result->relatives[] = $new_relative;
                    }
                }
            }

            if (isset($result['phones'])) {
                foreach ($result['phones'] as $phone) {
                    $final_result->phones[] = (string)$phone['number'];
                }
            }
            if (isset($result['emails'])) {
                foreach ($result['emails'] as $email) {
                    if ($email['address'] == "full.email.available@business.subscription") {
                        continue;
                    }
                    $final_result->emails[] = $email['address'];
                }
            }
            /*
            if (isset($result['jobs'])) {
                foreach ($result['jobs'] as $job) {
                    if (isset($job['title']) && isset($job['organization'])) {
                        $final_result->companies[] = $job['title']." - ".$job['organization'];
                    } elseif (isset($job['title'])) {
                        $final_result->companies[] = $job['title'];
                    } elseif (isset($job['organization'])) {
                        $final_result->companies[] = $job['organization'];
                    }
                }
            }
            if (isset($result['educations'])) {
                foreach ($result['educations'] as $education) {
                    $final_result->educations[] = $education['school'];
                }
            }
            */
            if (isset($result['usernames'])) {
                foreach ($result['usernames'] as $username) {
                    $final_result->usernames[] = $username['content'];
                }
            }
            if (isset($result['images'])) {
                foreach ($result['images'] as $image) {
                    $final_result->images[] = $image['url'];
                }
            }
            if (isset($result['urls'])) {
                foreach ($result['urls'] as $url) {
                    $profile = [];
                    $profile['domain'] = $url['@domain'];
                    $profile['url']= $url['url'];
                    $final_result->profiles[] = $profile;
                }
            }

            if (!empty($result['dob']['display'])) {
                $final_result->age = (int)$result['dob']['display'];
            }
            
            $final_result->report_id = $result['@id']??'';

            if (!$final_result->report_id) {
                $final_result->report_id = $result['@search_pointer']??'';
            }

            $final_results [] = $final_result;
        }

        if ($highestMatch>0) {
            $filteredResults = [];
            foreach ($final_results as $final_result) {
                if ($final_result->match_ratio>0 && $final_result->match_ratio < $highestMatch /2) {
                    continue;
                }

                $filteredResults [] = $final_result;
            }
            $final_results = $filteredResults;
        }

        if (count($final_results) <= 3) {
            foreach ($final_results as $final_result) {
                $final_result->modified = false;
            }
        }

        return $final_results;
    }
}
