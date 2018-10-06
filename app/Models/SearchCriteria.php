<?php

namespace App\Models;

use Skopenow\Search\Models\CriteriaInterface;

class SearchCriteria implements CriteriaInterface
{
    public $first_name = "";
    public $middle_name = "";
    public $last_name = "";
    public $full_name = "";
    public $username = "";
    public $city = "";
    public $state = "";
    public $country_code = "US";
    public $country = "";
    public $address = "";
    public $zipcode = "";
    public $birth_date = "";
    public $search_type = "";
    public $phone = "";
    public $email = "";
    public $domain = "";
    public $distance = "";
    public $site = "";
    public $company = "";
    public $school = "";
    public $url = "";
    public $results = [];
    public $social_profile_id = "";
    public $result_id = "";
    public $related_to = "";
    public $is_relative = false;
    public $profile_image = "";
    public $profiles = "";
    public $has_verified_profiles = false;
    /**
     * Name Status  [unique/common]
     * @var string
     */
    public $name_status = "";

    /**
     * City status  [smallCity/bigCity]
     * @var string
     */
    public $city_status = "";

    /**
     * Username source [input/generated/verified/not_verified]
     * @var string
     */
    public $username_source = "";
    public $username_status = "";
    public $extra_data = [];
    public $legacy_data = "";

    public static function fromCombination($combinationLevel)
    {
        $stringData = $combinationLevel['data']??'';
        $data = json_decode($stringData, true);

        $criteria = new static;
        if (empty($data) && $stringData) {
            throw new \UnexpectedValueException("Invalid combination data, JSON was expected!");
        } else {
            $criteria->first_name = $data['fn']??"";
            $criteria->middle_name = $data['mn']??"";
            $criteria->last_name = $data['ln']??"";
            $criteria->full_name = trim($criteria->first_name . ' ' . $criteria->middle_name) . ' ' . $criteria->last_name;
            $criteria->city = $data['ct']??"";
            $criteria->state = $data['st']??"";
            $criteria->zipcode = $data['zp']??"";
            $criteria->distance = $data['distance']??"";
            $criteria->country_code = $data['country_code']??"";
            $criteria->address = $data['adr']??"";
            $criteria->birth_date = $data['bd']??"";
            $criteria->search_type = $data['type']??"";
            $criteria->phone = $data['ph']??"";
            $criteria->email = $data['em']??"";
            $criteria->domain = $data['dm']??"";
            $criteria->site = $data['site']??'';
            $criteria->username = $data['un']??"";
            $criteria->company = $data['cm']??"";
            $criteria->school = $data['sc']??"";
            $criteria->name_status = $data['name_status']??"";
            $criteria->city_status = $data['city_status']??"";
            $criteria->username_source = $data['username_source']??"";
            $criteria->username_status = $data['username_status']??"";
            $criteria->social_profile_id = $data['social_profile_id']??"";
            $criteria->profile_image = $data['profile_image']??"";
            $criteria->has_verified_profiles = $data['has_verified_profiles']??false;


            $criteria->profiles = [];
            if (!empty($data['profiles'])) {
                $criteria->profiles = reset($data['profiles']);
            }
            $criteria->result_id = $data['result_id']??[];
            $criteria->related_to = $data['related_to']??[];
            $criteria->is_relative = $data['is_relative']??false;
        }

        return $criteria;
    }

    public function toCombinationData()
    {
        $data = [];
        $data['fn'] = $this->first_name;
        $data['mn'] = $this->middle_name;
        $data['ln'] = $this->last_name;
        $data['adr'] = $this->address;
        $data['un'] = $this->username;
        $data['ct'] = $this->city;
        $data['distance'] = $this->distance;
        $data['st'] = $this->state;
        $data['zp'] = $this->zipcode;
        $data['country_code'] = $this->country_code;
        $data['bd'] = $this->birth_date;
        $data['ph'] = $this->phone;
        $data['em'] = $this->email;
        $data['dm'] = $this->domain;
        $data['cm'] = $this->company;
        $data['sc'] = $this->school;
        $data['site'] = $this->site;
        $data['search_type'] = $this->search_type;
        $data['username_source'] = $this->username_source;
        $data['username_status'] = $this->username_status;
        $data['social_profile_id'] = $this->social_profile_id;
        $data['result_id'] = $this->result_id;
        $data['related_to'] = $this->related_to;
        $data['is_relative'] = $this->is_relative;
        $data['name_status'] = $this->name_status;
        $data['city_status'] = $this->city_status;
        $data['profile_image'] = $this->profile_image;
        $data['has_verified_profiles'] = $this->has_verified_profiles;
        $data['profiles'] = $this->profiles;
        return array_filter($data);
    }
}
