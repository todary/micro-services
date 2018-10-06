<?php

namespace App\Models;

class PeopleDataResult
{
    public $report_id ="";
    public $gender = "";
    public $link = "";
    public $modified = false;
    public $first_name = "";
    public $middle_name ="";
    public $last_name = "";
    public $full_name = "";
    public $location = "";
    public $street ="";
    public $address = "";
    public $city = "";
    public $state = "";
    public $zip = "";
    public $age ="";
    public $other_names = [];
    public $phones = [];
    public $emails = [];
    public $relatives = [];
    public $addresses = [];
    public $usernames = [];
    public $companies = [];
    public $educations = [];
    public $images = [];
    public $profiles = [];
    public $source = "";
    public $comb_fields = [];
    public $searchType = "";
    public $recall = false;
    
    public $result_rank = 1;
    public $merged_sources = [];
    public $match_ratio = null;

    public $group ="";
    public $trial ="";
    public $plan ="";
    public $key ="";

    public function extractResultNames(): array
    {
        $resultNames = ['has_middle_name'=>false, 'names'=> []];

        if (!empty($this->full_name)) {
            $resultNames['names'] []= ['full_name'=> $this->full_name,'first_name'=>$this->first_name, 'middle_name'=>$this->middle_name, 'last_name'=>$this->last_name];

            if (!empty($this->middle_name)) {
                $resultNames['has_middle_name'] = true;
            }
        }

        foreach ($this->other_names as $other_name) {
            if (empty($other_name['full_name'])) {
                continue;
            }
            $resultNames['names'] []= ['full_name'=> $other_name['full_name'],'first_name'=>$other_name['first_name'], 'middle_name'=>$other_name['middle_name'], 'last_name'=>$other_name['last_name']];

            if (!empty($other_name['middle_name'])) {
                $resultNames['has_middle_name'] = true;
            }
        }

        return $resultNames;
    }

    public function extractRelativeNames(): array
    {
        $relativeNames = ['has_middle_name'=>false, 'names'=> []];

        foreach ($this->relatives as $relative_name) {
            if (empty($relative_name['full_name'])) {
                continue;
            }

            $other_relative = false;

            $relativeData = ['full_name'=> $relative_name['full_name'],'first_name'=>$relative_name['first_name'], 'middle_name'=>$relative_name['middle_name'], 'last_name'=>$relative_name['last_name']];

            if (!empty($relative_name['other_relative'])) {
                $relativeData['other_relative'] = true;
            }
            
            $relativeNames['names'] []= $relativeData;

            if (!empty($relative_name['middle_name'])) {
                $relativeNames['has_middle_name'] = true;
            }
        }

        return $relativeNames;
    }

    public function extractPhones(): array
    {
        return $this->phones;
    }

    public function extractEmails(): array
    {
        return $this->emails;
    }

    public function extractAddresses(): array
    {
        return $this->addresses;
    }

    public function extractUsernames(): array
    {
        return $this->usernames;
    }

    public function extractImages(): array
    {
        return $this->images;
    }

    public function extractProfiles(): array
    {
        return $this->profiles;
    }
}
