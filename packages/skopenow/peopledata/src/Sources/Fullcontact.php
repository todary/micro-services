<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\Clients\ClientInterface;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;

class Fullcontact extends AbstractSearchFetcher
{
    const SOURCE_NAME = "fullcontact";
    const FETCHER_SOURCE = "fullcontact";

    protected function processResponse(): array
    {
        $dataPoints = $this->search_result->getDataPoints();
        if (!$dataPoints) {
            return [];
        }

        $output = new OutputModel;
        $output->result_rank = $this->result_rank;
        $output->link = static::FETCHER_SOURCE;
        $output->source = static::SOURCE_NAME;

        if (isset($dataPoints['name']) && $dataPoints['name']->count()) {
            $names = iterator_to_array($dataPoints['name']);
            $name = $names[0];
            $output->first_name = ucwords(strtolower($name['first_name']));
            $output->middle_name = ucwords(strtolower($name['middle_name']));
            $output->last_name = ucwords(strtolower($name['last_name']));
            $output->full_name = ucwords(strtolower($name['full_name']));
        }

        if (isset($dataPoints['address']) && $dataPoints['address']->count()) {
            $locations = $dataPoints['address'];
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
                if (!$output->city && $new_address['address']) {
                    $output->address = $new_address['address'] ;
                    $output->city = $new_address['city'];
                    $output->state = $new_address['state'];
                    $output->location = $new_address['location'] ;
                    $output->street = $new_address['street'];
                    $output->zip = $new_address['zip'] ;
                    continue;
                }
                $output->addresses[] = $new_address;
            }
            if (!$output->city) {
                foreach ($output->addresses as $location) {
                    if ($location['city'] && $location['state']) {
                        $output->address = $location['address'] ;
                        $output->city = $location['city'];
                        $output->state = $location['state'];
                        $output->location = $location['location'] ;
                        $output->street = $location['street'];
                        $output->zip = $location['zip'] ;
                        break;
                    }
                }
            }

            if (!$output->city && !empty($output->addresses)) {
                $output->address = $output->addresses[0]['address'] ;
                $output->city = $output->addresses[0]['city'];
                $output->state = $output->addresses[0]['state'];
                $output->location = $output->addresses[0]['location'] ;
                $output->street = $output->addresses[0]['street'];
                $output->zip = $output->addresses[0]['zip'] ;
                unset($output->addresses[0]);
                array_values($output->addresses);
            }
        }

        if (isset($dataPoints['phone']) && $dataPoints['phone']->count()) {
            $phones = $dataPoints['phone'];
            foreach ($phones as $phone) {
                $output->phones[] = (string)$phone['phone'];
            }
        }
        if (isset($dataPoints['email']) && $dataPoints['email']->count()) {
            $emails = $dataPoints['email'];
            foreach ($emails as $email) {
                $output->emails[] = $email['email'];
            }
        }
        if (isset($dataPoints['work']) && $dataPoints['work']->count()) {
            $experiences = $dataPoints['work'];
            foreach ($experiences as $job) {
                if (!empty($job['title']) && !empty($job['company'])) {
                    $output->companies[] = $job['title']." - ".$job['company'];
                } elseif (!empty($job['title'])) {
                    $output->companies[] = $job['title'];
                } elseif (!empty($job['company'])) {
                    $output->companies[] = $job['company'];
                }
            }
        }

        if (isset($dataPoints['website']) && $dataPoints['website']->count()) {
            $websites = $dataPoints['website'];
            foreach ($websites as $website) {
                $output->profiles[] = ['url'=>'http://' . $website['url'], 'domain'=>$website['url']];
            }
        }

        $results = $this->search_result->getResults();
        foreach ($results as $result) {
            $profile = [];
            $profile['domain'] = str_replace("www.", "", parse_url($result->url, PHP_URL_HOST));
            if (strpos($profile['domain'], 'm.')===0) {
                $profile['domain'] = substr($profile['domain'], 2);
            }

            $profile['url']= $result->url;
            $output->profiles[] = $profile;
        }
        
        return [$output];
    }
}
