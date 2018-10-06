<?php

namespace Skopenow\Reports\PeopleData;

use Skopenow\Reports\Models\Report;
use Skopenow\Reports\CombinationCreators\CombinationsMaker;

class PeopleDataCriteriaBuilder
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
        $this->combinationsMaker = new CombinationsMaker;
    }

    public function build()
    {
        $report = $this->transformReport();
        $config = loadData('peopleDataConfig');
        $levels = $config['levels'];
        $input_priority = $config['input_priority'];
        $sources = $config['source'];
        $apis_order_config = $config['apis_order_config'];
        $api_options = $config['api_options'];
        $criteria = [];
        foreach ($input_priority as $index => $inputs) {
            foreach ($inputs as $input) {
                foreach ($report[$input] as $typeIndex => $reportData) {
                    $inputSources = $sources[$input];
                    // [['pipl', 'tloxp'], ['whois'], ['fullcontact']]
                    foreach ($inputSources as $inputApis) {
                        if (!empty($inputApis['ignoreWith'])) {
                            foreach ($inputApis['ignoreWith'] as $values) {
                                foreach ($values as $value) {
                                    if (empty($report[$value])) {
                                        continue 2;
                                    }
                                }
                                continue 2;
                            }
                        }

                        foreach ($inputApis as $api_index => $inputApi) {
                            if (in_array($api_index, ['ignoreWith'], true)) {
                                continue;
                            }
                            //dump($inputApi, $input);continue;
                            if ($inputApi == 'pipl' || $inputApi == 'tloxp') {
                                $level = 'pipltlo';
                            } else {
                                $level = $inputApi;
                            }
                            if (array_key_exists($input, $apis_order_config)) {
                                if (!empty($apis_order_config[$input][$inputApi])) {
                                    $options = $apis_order_config[$input][$inputApi];
                                } else {
                                    $options = ['apis' => [$inputApi]];
                                }
                            } else {
                                $options = ['apis' => [$inputApi]];
                            }
                            if (array_key_exists($input, $api_options) && array_key_exists($inputApi, $api_options[$input])) {
                                $options['api_options'] = $api_options[$input][$inputApi];
                            }
                            if (array_key_exists($input, $levels) && ($inputApi == 'pipl' || $inputApi == 'tloxp')) {
                                foreach ($levels[$input] as $try) {
                                    $data = [];
                                    foreach ($try as $type) {
                                        if (empty($report[$type])) {
                                            if ($type = 'state' && !empty($report['city'])) {
                                                continue;
                                            }
                                            continue 2;
                                        }
                                        if ($type == $input) {
                                            $data[$type] = [$reportData];
                                            continue;
                                        }
                                        $data[$type] = $report[$type];
                                    }
                                    foreach ($this->addCriteriaLevel($data, $options) as $value) {
                                        $criteria[$level]['try' . $index]['try' . $input . $typeIndex][] = $value;
                                    }
                                }
                            } else {
                                $data = [];
                                $data[$input] = $reportData;
                                foreach ($this->addCriteriaLevel($data, $options) as $value) {
                                        $criteria[$level]['try' . $index]['try' . $input . $typeIndex][] = $value;
                                    }
                                // $criteria[$level]['try' . $index]['try' . $input . $typeIndex][] = $this->addCriteriaLevel($data, [$inputApi]);
                            }
                        }
                    }
                }
            }
        }
        return $criteria;
    }

    protected function transformReport()
    {
        $data = [];
        $locationService = loadService('location');
        $cities = [];
        $states = [];
        foreach ($this->report->city as $location) {
            $locationIterator = new \ArrayIterator([$location]);
            $city = array_unique($locationService->extractCity($locationIterator)->getArrayCopy());
            $city = $city[$location];
            $state = array_unique($locationService->extractState($locationIterator)->getArrayCopy());
            $state = $state[$location];
            if (empty($city) && empty($state)) {
                $addressParts = explode(",", $location);
                $city = trim(end($addressParts));
            }
            $cities[] = $city;
            $states[] = $state;
        }
        $nameInfoService = loadService('nameInfo');
        // load name splitter service here
        $names = $nameInfoService->nameSplit(new \ArrayIterator($this->report->full_name));
        $data['name'] = [];
        foreach ($names as $name) {
            foreach ($name['splitted'] as $splittedName) {
                $data['name'][] = $splittedName['fullName'];
            }
        }
        $data['email'] = $this->report->email;
        $data['phone'] = $this->report->phone;
        $data['username'] = $this->report->usernames;
        $data['company'] = $this->report->company;
        $data['school'] = $this->report->school;
        $data['address'] = $this->report->address;
        $data['location'] = $this->report->city;
        $data['city'] = $cities;
        $data['state'] = $states;
        $data['age'] = $this->report->age;
        return $data;
    }

    public function addCriteriaLevel(array $data, array $options) : array
    {
        foreach ($data as $type => $values) {
            if (!is_array($values)) {
                $this->combinationsMaker->set($type, [$values]);
                continue;
            }
            $this->combinationsMaker->set($type, $values);
        }
        $combinations = $this->combinationsMaker
            ->withEach(array_keys($data))
            ->get();
        $criteria = [];
        foreach ($combinations as $combination) {
            $values = [];
            foreach ($combination->getData() as $type => $value) {
                $values[$type] = reset($value);
            }
            $criteria[] = array_merge($values, $options);
        }
        return $criteria;
    }

    protected function buildReverseCriterias()
    {
        $reverse = new ReverseCriteriaBuilder($this->data);
        return $reverse->build();
    }

    protected function buildNameStateEmailCriteria()
    {
        $criteria = [];
        $location = loadService('location');
        $cityStates = $location->splitLocation(new \ArrayIterator($this->data->city));
        foreach ($this->data->email as $email) {
            foreach ($this->data->full_name as $name) {
                foreach ($cityStates as $cityState) {
                    $criteria[] = [
                        ['api' => 'pipl', 'name' => $name, 'email' => $email, 'state' => $cityState['state']],
                        ['api' => 'tloxp', 'name' => $name, 'email' => $email, 'state' => $cityState['state']],
                        ['api' => 'whois', 'name' => $name, 'email' => $email, 'state' => $cityState['state']],
                        ['api' => 'fullcontact', 'name' => $name, 'email' => $email, 'state' => $cityState['state']],
                    ];
                }
            }
        }
        return array_merge($criteria, $this->buildNameEmailCriteria());
    }

    protected function buildNameEmailCriteria()
    {
        foreach ($this->data->email as $email) {
            foreach ($this->data->full_name as $name) {
                $criteria[] = [
                    ['api' => 'pipl', 'name' => $name, 'email' => $email],
                    ['api' => 'tloxp', 'name' => $name, 'email' => $email],
                    ['api' => 'whois', 'name' => $name, 'email' => $email],
                    ['api' => 'fullcontact', 'name' => $name, 'email' => $email],
                ];
            }
        }
        return array_merge($criteria, $this->buildEmailCriteria());
    }

    protected function buildEmailCriteria()
    {
        foreach ($this->data->email as $email) {
            $criteria[] = [
                ['api' => 'pipl', 'email' => $email],
                ['api' => 'tloxp', 'email' => $email],
                ['api' => 'whois', 'email' => $email],
                ['api' => 'fullcontact', 'email' => $email],
            ];
        }
        return $criteria;
    }

    protected function buildNameStatePhoneCriteria()
    {
        $criteria = [];
        $location = loadService('location');
        $cityStates = $location->splitLocation(new \ArrayIterator($this->data->city));
        foreach ($this->data->phone as $phone) {
            foreach ($this->data->full_name as $name) {
                foreach ($cityStates as $cityState) {
                    $criteria[] = [
                        ['api' => 'pipl', 'name' => $name, 'phone' => $phone, 'state' => $cityState['state']],
                        ['api' => 'tloxp', 'name' => $name, 'phone' => $phone, 'state' => $cityState['state']],
                        ['api' => 'whois', 'name' => $name, 'phone' => $phone, 'state' => $cityState['state']],
                        ['api' => 'fullcontact', 'name' => $name, 'phone' => $phone, 'state' => $cityState['state']],
                    ];
                }
            }
        }
        return array_merge($criteria, $this->buildNamePhoneCriteria());
    }

    protected function buildNamePhoneCriteria()
    {
        foreach ($this->data->phone as $phone) {
            foreach ($this->data->full_name as $name) {
                $criteria[] = [
                    ['api' => 'pipl', 'name' => $name, 'phone' => $phone],
                    ['api' => 'tloxp', 'name' => $name, 'phone' => $phone],
                    ['api' => 'whois', 'name' => $name, 'phone' => $phone],
                ];
            }
        }
        return array_merge($criteria, $this->buildPhoneCriteria());
    }

    protected function buildPhoneCriteria()
    {
        foreach ($this->data->phone as $phone) {
            $criteria[] = [
                ['api' => 'pipl', 'phone' => $phone],
                ['api' => 'tloxp', 'phone' => $phone],
                ['api' => 'whois', 'phone' => $phone],
            ];
        }
        return $criteria;
    }


    protected function buildNameStateUsernameCriteria()
    {
        $criteria = [];
        $location = loadService('location');
        $cityStates = $location->splitLocation(new \ArrayIterator($this->data->city));
        foreach ($this->data->username as $username) {
            foreach ($this->data->full_name as $name) {
                foreach ($cityStates as $cityState) {
                    $criteria[] = [
                        ['api' => 'pipl', 'name' => $name, 'username' => $username, 'state' => $cityState['state']],
                        ['api' => 'tloxp', 'name' => $name, 'username' => $username, 'state' => $cityState['state']],
                        ['api' => 'whois', 'name' => $name, 'username' => $username, 'state' => $cityState['state']],
                        ['api' => 'fullcontact', 'name' => $name, 'username' => $username, 'state' => $cityState['state']],
                    ];
                }
            }
        }
        return array_merge($criteria, $this->buildNameUsernameCriteria());
    }

    protected function buildNameUsernameCriteria()
    {
        foreach ($this->data->username as $username) {
            foreach ($this->data->full_name as $name) {
                $criteria[] = [
                    ['api' => 'pipl', 'name' => $name, 'username' => $username],
                    ['api' => 'tloxp', 'name' => $name, 'username' => $username],
                    ['api' => 'whois', 'name' => $name, 'username' => $username],
                ];
            }
        }
        return array_merge($criteria, $this->buildPhoneCriteria());
    }

    protected function buildUsernameCriteria()
    {
        foreach ($this->data->username as $username) {
            $criteria[] = [
                ['api' => 'pipl', 'username' => $username],
                ['api' => 'tloxp', 'username' => $username],
                ['api' => 'whois', 'username' => $username],
            ];
        }
        return $criteria;
    }

    protected function buildAddressCriteria()
    {

    }

    protected function buildLocationCriteria()
    {

    }
}
