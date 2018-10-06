<?php
namespace Skopenow\PeopleData\Sources;

use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use App\Models\ApiAccount;
use Skopenow\PeopleData\Clients\ClientInterface;

class Tloxp extends AbstractSource
{
    /**
     * @var Integer|4 Should has the number of trials to get account .
     */
    protected $account_try = 4;
    /**
     * @var string the IP of the proxy that will call the api
     */
    private $proxy_ip;
    /**
     * @var string the api source .
     */
    public $source = "-";
    /**
     * @var string the username of the proxy that will call the api
     */
    private $proxy_username;
    /**
     * @var string the password of the proxy that will call the api
     */
    private $proxy_password;
    /**
     * @var string the port of the proxy that will call the api
     */
    private $proxy_port;
    /**
     * @var string the username of the api
     */
    private $username;
    /**
     * @var string the username of the api
     */
    private $password;
    /**
     * @var Array that carry criteria to search
     */
    private $search_criteria = [];
    /**
     * @var Array that carry the actual inputs that api has done when search modified
     */
    private $actual_inputs;
    /**
     * @var Integer Store the number of records returned from Api
     */
    private $number_of_records = 20;
    /**
     * @var Integer Store the number of Addresses returned from Api for each person
     */
    private $max_addresses = 50;

    protected $result_rank = 3;
    /**
     * These function initiate important variables such as accounts and soap
     *
     * @param Criteria          $criteria criteria to search
     * @param ApiAccount        $account  account to make search with
     * @param ClientInterface $client   to call server
     */
    public function __construct(Criteria $criteria, ApiAccount $account, ClientInterface $client)
    {
        // initiate criteria
        $this->inputCriteria = $criteria;
        // initiate account
        $this->account = $account;
        // initiate client
        $this->client = $client;
        // initiate proxy configurations
        $this->proxy_ip = $account->associated_proxy_ip;
        $this->proxy_username = $account->associated_proxy_username;
        $this->proxy_password = $account->associated_proxy_password ;
        $this->proxy_port = $account->associated_proxy_port;
        // initiate API configurations
        $this->username = $account->username;
        $this->password = $account->password;
    }

    
    protected function prepareCriteria()
    {
        $search_criteria = [];
        
        if ($this->inputCriteria->full_name) {
            $search_criteria["FullName"] = $this->inputCriteria->full_name;
        }
        if ($this->inputCriteria->phone) {
            $search_criteria['Phone'] =  $this->inputCriteria->phone;
        }
        if ($this->inputCriteria->email) {
            $search_criteria['EmailAddress'] =  $this->inputCriteria->email;
        }
        if ($this->inputCriteria->age) {
            $search_criteria['MinimumAge'] =  $this->inputCriteria->age - 2;
            $search_criteria['MaximumAge'] =  $this->inputCriteria->age + 2;
        }
        if ($this->inputCriteria->state) {
            if (!isset($search_criteria['Address'])) {
                $search_criteria['Address'] = [];
            }
            $search_criteria['Address']["State"] = $this->inputCriteria->state;
        }
        if ($this->inputCriteria->city) {
            if (!isset($search_criteria['Address'])) {
                $search_criteria['Address'] = [];
            }
            $search_criteria['Address']["City"] = $this->inputCriteria->city;
        }
        if ($this->inputCriteria->address) {
            if (!isset($search_criteria['Address'])) {
                $search_criteria['Address'] = [];
            }
            $search_criteria['Address']["Line1"] = $this->inputCriteria->address;
        }
        if ($this->inputCriteria->company) {
            $search_criteria['BusinessName'] =  $this->inputCriteria->company;
        }
        if ($this->inputCriteria->school) {
            $search_criteria['ProfessionalLicenses'] = [];
            $search_criteria['ProfessionalLicenses']['School'] =  $this->inputCriteria->school;
        }
        if ($this->inputCriteria->report_id) {
            $search_criteria['ReportToken']=  $this->inputCriteria->report_id;
        }

        if ($this->inputCriteria->api_options) {
            $search_criteria +=  $this->inputCriteria->api_options;
        }

        return $search_criteria;
    }

    protected function doSearch($search_criteria)
    {
        $search_input = [
            'genericSearchInput'=>[
                'Username' => $this->username,
                'Password' => $this->password,
                'DPPAPurpose' => '0',
                'GLBPurpose' => '0',
                'PermissibleUseCode' => '30',
                'Version'=> '40',
                'NumberOfRecords' => $this->number_of_records,
                'StartingRecord' => 1,
                'MaximumAddresses' => $this->max_addresses,
                'UseExactFirstNameMatch' => 'Yes'
            ]
        ];
        if (empty($search_criteria)) {
            return false;
        }
        $search_input['genericSearchInput'] = $search_criteria + $search_input['genericSearchInput'];

        try {
            $is_cached = $this->getCached($search_input, "Tloxp");

            if (!$is_cached) {
                $output = $this->client->PersonSearch($search_input);
                if (isset($output->PersonSearchResult) && !isset($output->PersonSearchResult->ErrorMessage)) {
                    $this->cacheResult($search_input, $output, "Tloxp");
                }
                \Log::debug('Tloxp got response directly: ' . json_encode($search_input), [$output]);
            } else {
                $output = $is_cached;
                \Log::debug('Tloxp got response from Cache: ' . json_encode($search_input), [$output]);
            }

            if (isset($output->PersonSearchResult)) {
                $this->search_json_result = $output;
                if (isset($output->PersonSearchResult->NumberOfRecordsFoundWithModifiedSearch) && $output->PersonSearchResult->NumberOfRecordsFoundWithModifiedSearch !== 0) {
                    $this->search_modified = true;
                }
                if (isset($output->PersonSearchResult->SearchModified) && $output->PersonSearchResult->SearchModified === "Y") {
                    $this->search_modified = true;
                }
                if (isset($output->PersonSearchResult->PersonSearchOutputRecords->TLOPersonSearchOutputRecord)) {
                    $result = json_decode(json_encode($output->PersonSearchResult->PersonSearchOutputRecords->TLOPersonSearchOutputRecord), true);
                    
                    if (isset($result['Names'])) {
                        $result = [$result];
                    }

                    $this->search_result = $result;
                }
                if (isset($output->PersonSearchResult->ActualSearchInput) && $output->PersonSearchResult->ActualSearchInput) {
                    $this->actual_inputs = json_decode(json_encode($output->PersonSearchResult->ActualSearchInput, true));
                }
            }

            $this->search_error_code = $output->PersonSearchResult->ErrorCode;
            return !empty($this->search_result);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function processResponse(): array
    {
        if (!$this->active) {
            return [];
        }

        $final_result = [];
        // loop through search results
        foreach ($this->search_result as $i => $result) {
            if (isset($result['DatesOfDeath']) && !empty($result['DatesOfDeath']['DateOfDeathRecord'])) {
                continue;
            }

            $final_result[$i] = new OutputModel;
            $final_result[$i]->result_rank = $this->result_rank;
            if (isset($result['ReportToken'])) {
                    $final_result[$i]->report_id = $result['ReportToken'];
            }
            $final_result[$i]->modified = $this->search_modified;
            $final_result[$i]->link = "Tloxp.api";
            $final_result[$i]->source = $this->source;
            if (isset($result['Names']['BasicName'])) {
                $final_result[$i]->first_name = ucwords(strtolower($result['Names']['BasicName'][0]['FirstName']));
                $final_result[$i]->middle_name = ucwords(strtolower($result['Names']['BasicName'][0]['MiddleName']));
                $final_result[$i]->last_name = ucwords(strtolower($result['Names']['BasicName'][0]['LastName']));
                $final_result[$i]->full_name = $final_result[$i]->first_name." ".($final_result[$i]->middle_name ? ($final_result[$i]->middle_name." ") : "" ).$final_result[$i]->last_name;
                unset($result['Names']['BasicName'][0]);
                if (!empty($result['Names']['BasicName'])) {
                    $result['Names']['BasicName'] = array_values($result['Names']['BasicName']);
                    foreach ($result['Names']['BasicName'] as $key => $name) {
                        $final_result[$i]->other_names[$key] = [];
                        $final_result[$i]->other_names[$key]['first_name'] = ucwords(strtolower($name['FirstName']));
                        $final_result[$i]->other_names[$key]['middle_name'] = ucwords(strtolower($name['MiddleName']));
                        $final_result[$i]->other_names[$key]['last_name'] = ucwords(strtolower($name['LastName']));
                        $final_result[$i]->other_names[$key]['full_name'] = $final_result[$i]->other_names[$key]['first_name']." ".($final_result[$i]->other_names[$key]['middle_name'] ? ($final_result[$i]->other_names[$key]['middle_name']." ") : "" ).$final_result[$i]->other_names[$key]['last_name'];
                    }
                }
            }
            if (isset($result['EmailAddresses']) && !empty($result['EmailAddresses']['string'])) {
                $final_result[$i]->emails = $result['EmailAddresses']['string'];
            }
            if (!empty($result['OtherPhones']['BasicPhoneListing'])) {
                $phones = $result['OtherPhones']['BasicPhoneListing'];
                foreach ($phones as $phone) {
                    if (!empty($phone['Phone']) && $phone['Score'] > 70) {
                        $final_result[$i]->phones[] = (string)$phone['Phone'];
                    }
                }
            }
            if (isset($result['Addresses']) && !empty($result['Addresses']['BasicAddressRecord'])) {
                if (!empty($result['AddressSatisfyingSearch']['Line1']) && stripos($result['AddressSatisfyingSearch']['Line1'], "Suppressed") === false) {
                    $final_result[$i]->street = ucwords(strtolower($result['AddressSatisfyingSearch']['Line1']??""));
                    $final_result[$i]->city = ucwords(strtolower($result['AddressSatisfyingSearch']['City']??""));
                    $final_result[$i]->state = ( strlen($result['AddressSatisfyingSearch']['State']??"") >2)? ucwords(strtolower($result['AddressSatisfyingSearch']['State']??"")):strtoupper($result['AddressSatisfyingSearch']['State']??"");
                    $final_result[$i]->location = ((isset($final_result[$i]->city)&&$final_result[$i]->city)?$final_result[$i]->city.", " : "").$final_result[$i]->state??"";
                    $final_result[$i]->zip = $result['AddressSatisfyingSearch']['Zip']??"";
                    $final_result[$i]->address = (($final_result[$i]->street)?$final_result[$i]->street.", ":"").(($final_result[$i]->location)?$final_result[$i]->location:"");
                }
                foreach ($result['Addresses']['BasicAddressRecord'] as $key => $address) {
                    if (!empty($address['Address']['Line1']) && stripos($address['Address']['Line1'], "Suppressed") !== false) {
                        continue;
                    }

                    if (!$final_result[$i]->address) {
                        $final_result[$i]->street = ucwords(strtolower($address['Address']['Line1']??""));
                        $final_result[$i]->city = ucwords(strtolower($address['Address']['City']??""));
                        $final_result[$i]->state = ( strlen($address['Address']['State']??"") >2)? ucwords(strtolower($address['Address']['State']??"")):strtoupper($address['Address']['State']??"");
                        $final_result[$i]->location = ((isset($final_result[$i]->city)&&$final_result[$i]->city)?$final_result[$i]->city.", " : "").$final_result[$i]->state??"";
                        $final_result[$i]->zip = $address['Address']['Zip']??"";
                        $final_result[$i]->address = (($final_result[$i]->street)?$final_result[$i]->street.", ":"").(($final_result[$i]->location)?$final_result[$i]->location:"");

                        continue;
                    }
                    $final_result[$i]->addresses[$key] = [];
                    $final_result[$i]->addresses[$key]['street'] = ucwords(strtolower($address['Address']['Line1']??""));
                    $final_result[$i]->addresses[$key]['city'] = ucwords(strtolower($address['Address']['City']??""));
                    $final_result[$i]->addresses[$key]['state'] = ( strlen($address['Address']['State']??"") >2)? ucwords(strtolower($address['Address']['State']??"")):strtoupper($address['Address']['State']??"");
                    $final_result[$i]->addresses[$key]['location'] = ((isset($final_result[$i]->addresses[$key]['city'])&&$final_result[$i]->addresses[$key]['city'])?$final_result[$i]->addresses[$key]['city'].", " : "").$final_result[$i]->addresses[$key]['state']??"";
                    $final_result[$i]->addresses[$key]['zip'] = $address['Address']['Zip']??"";
                    $final_result[$i]->addresses[$key]['address'] = (($final_result[$i]->addresses[$key]['street'])?$final_result[$i]->addresses[$key]['street'].", ":"").(($final_result[$i]->addresses[$key]['location'])?$final_result[$i]->addresses[$key]['location']:"");

                    if ($final_result[$i]->addresses[$key]['address'] == $final_result[$i]->address) {
                        unset($final_result[$i]->addresses[$key]);
                    }
                }
            }
            $final_result[$i]->addresses = array_values($final_result[$i]->addresses);

            if (!empty($result['Relatives']['TLOPersonSearchRelative'])) {
                $relatives = $result['Relatives']['TLOPersonSearchRelative'];
                //TODO: Remove relative of matches search name (First name exactly)
                foreach ($relatives as $key => $relative) {
                    $final_result[$i]->relatives[$key]['first_name'] = ucwords(strtolower($relative['Name']['FirstName']));
                    $final_result[$i]->relatives[$key]['middle_name'] = ucwords(strtolower($relative['Name']['MiddleName']));
                    $final_result[$i]->relatives[$key]['last_name'] = ucwords(strtolower($relative['Name']['LastName']));

                    $final_result[$i]->relatives[$key]['full_name'] = str_replace('  ', ' ', $final_result[$i]->relatives[$key]['first_name'] . ' ' . $final_result[$i]->relatives[$key]['middle_name'] . ' ' . $final_result[$i]->relatives[$key]['last_name']);
                }
            }
            if (isset($result['DatesOfBirth']['BasicDateOfBirthRecord'])) {
                foreach ($result['DatesOfBirth']['BasicDateOfBirthRecord'] as $date) {
                    if (isset($date['CurrentAge'])) {
                        $final_result[$i]->age = $date['CurrentAge'];
                    }
                }
            }
        }

        return $this->result = $final_result;
    }
}
