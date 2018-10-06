<?php
namespace Skopenow\Reports\Transformers;

class ReportSuggestionTransformer
{
    public $result = [];
    public function transform($foundData)
    {
        foreach ($foundData as $offset => $data) {
            $this->result[$offset]['gender'] = $data->gender;
            $this->result[$offset]['source'] = $data->source;
            $this->result[$offset]['first_name'] = $data->first_name;
            $this->result[$offset]['middle_name'] = $data->middle_name;
            $this->result[$offset]['last_name'] = $data->last_name;
            $this->result[$offset]['full_name'] = $data->full_name??'';
            $this->result[$offset]['location'] = $data->location;
            $this->result[$offset]['street'] = $data->street;
            $this->result[$offset]['address'] = $data->address;
            $this->result[$offset]['city'] = $data->city;
            $this->result[$offset]['state'] = $data->state;
            $this->result[$offset]['zip'] = $data->zip;
            $this->result[$offset]['age'] = $data->age;
            $this->result[$offset]['phones'] = $data->phones;
            $this->result[$offset]['emails'] = $data->emails;
            $this->result[$offset]['relatives'] = $data->relatives;
            $this->result[$offset]['other_names'] = $data->other_names;
            $this->result[$offset]['usernames'] = $data->usernames;
            $this->result[$offset]['addresses'] = $data->addresses;
            $this->result[$offset]['work'] = $data->companies;
            $this->result[$offset]['school'] = $data->educations;
            $this->result[$offset]['images'] = $data->images;
            $this->result[$offset]['profiles'] = $data->profiles;
            $this->result[$offset]['merged_sources'] = $data->merged_sources;
            $this->result[$offset]['type'] = 'peopleData';
        }
        return $this->result;
    }
}
