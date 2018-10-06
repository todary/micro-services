<?php
namespace Skopenow\PeopleData;

/**
 * @category Class
 * @author Abdullah Al-Nahhal
 * @package Search
 * These Class Work for initiating Criteria template
 */
class Criteria
{
    private $api;
    private $full_name;
    private $city;
    private $state;
    private $country;
    private $address;
    private $phone;
    private $email;
    private $username;
    private $age;
    private $company;
    private $school;
    private $report_id;
    private $api_options =[];

    public function __construct(array $inputCriteria)
    {
        $allowedInputs = [
            'apis',
            'strategy',
            'name',
            'city',
            'state',
            'country',
            'address',
            'phone',
            'email',
            'username',
            'age',
            'company',
            'school',
            'report_id',
            'api_options',
            'sandbox',
        ];

        $invalidInputs = array_diff(array_keys($inputCriteria), $allowedInputs);
        if (!empty($invalidInputs)) {
            throw new \Exception('Unknown inputs: ' . implode(",", $invalidInputs));
        }

        $inputCriteria = array_filter($inputCriteria);

        $this->full_name = $inputCriteria["name"]??"";
        $this->api = $inputCriteria["api"]??"";
        $this->city = $inputCriteria["city"]??"";
        $this->state = $inputCriteria["state"]??"";
        $this->country = $inputCriteria["country"]??"";
        $this->address = $inputCriteria["address"]??"";
        $this->email = $inputCriteria["email"]??"";
        $this->phone = $inputCriteria["phone"]??"";
        $this->username = $inputCriteria["username"]??"";
        $this->age = $inputCriteria["age"]??"";
        $this->company = $inputCriteria["company"]??"";
        $this->school = $inputCriteria["school"]??"";
        $this->report_id = $inputCriteria["report_id"]??"";
        $this->api_options = $inputCriteria["api_options"]??"";
    }

    public function __set($variable, $value)
    {
        if (!property_exists($this, $variable)) {
            throw new \Exception("These variable can't be set");
            return false;
        }
        $this->{$variable} = $value;
    }
    
    public function __get($variable)
    {
        if (!property_exists($this, $variable)) {
            throw new \Exception("These variable can't be get");
            return false;
        }
        return $this->{$variable};
    }
}
