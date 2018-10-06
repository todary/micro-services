<?php
/**
 * Http Requests client code
 *
 * PHP version 7.0
 *
 * @category Micro_Services-phase_1
 * @package  Combinations Service
 * @author   Mahmoud Mgady <mahmoud.magdy@queentecksolutions.com>
 * @license  QueenTechSolutions, Inc.
 * @link     http://www.queentecksolutions.com
 **/
namespace Skopenow\Reports\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*/
class Report extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'id',
        'first_name', 'middle_name', 'last_name', 'date_of_birth', 'age',
        'address', 'street', 'city', 'state', 'country', 'city_status', 'zip', 'phone',
        'company', 'email', 'usernames', 'added_usernames', 'school', 'all_count',
        'current', 'completed', 'combinations', 'current_combination', 'started', 'case_number',
        'user_id', 'corporate_id', 'is_paid', 'real_start_date', 'insert_date', 'end_date',
        'schedule_interval', 'has_error', 'schedule_now', 'google_exc', 'search_combinations',
        'func', 'full_name', 'searched_names', 'added_emails', 'invoice_id', 'service_id',
        'cost', 'paid_amount', 'reference', 'is_api', 'api_options', 'is_deleted', 'is_hidden',
        'search_origin', 'search_type', 'reverse_source', 'reverse_url', 'user_ip', 'user_agent',
        'version', 'search_dateline', 'last_combination_run', 'profiles_in_results', 'is_charge',
        'info_score', 'score', 'is_rescan', 'rescan_enabled', 'rescan_count', 'rescan_allowed_count',
        'rescan_settings', 'rescan_done', 'rescan_type', 'rescan_from_id', 'rescan_expires', 'number_of_changes',
        'is_comb_proceeded', 'is_premium_search', 'department_id', 'track_number', 'upgraded_to_premium',
        'search_credit_count', 'search_analyst_status', 'on_complete_start_minute', 'on_complete_log_stream',
        'is_public', 'sub_used_plan_id', 'sub_used_price_id', 'sub_used_addon_id', 'sub_is_extra_plan',
        'sub_is_extra_addon', 'sub_is_premium', 'sub_is_extra_credit', 'sub_original_cost', 'is_void',
        'show_void_label', 'void_reason', 'filters', 'init_data', 'view_settings'
    ];

    public $timestamps = false;

    //full name
    public function getFullNameAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setFullNameAttribute(array $fullNames)
    {
        $this->attributes['full_name'] = $this->implodeFilter(',', $fullNames);
    }

    //full name
    public function getSearchedNamesAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setSearchedNamesAttribute(array $fullNames)
    {
        $this->attributes['searched_names'] = $this->implodeFilter(',', $fullNames);
    }

    //first name
    public function getFirstNameAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setFirstNameAttribute(array $firstNames)
    {
        $this->attributes['first_name'] = $this->implodeFilter(',', $firstNames);
    }

    //middle name
    public function getMiddleNameAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setMiddleNameAttribute(array $middleNames)
    {
        $this->attributes['middle_name'] = $this->implodeFilter(',', $middleNames);
    }

    //last name
    public function getLastNameAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setLastNameAttribute(array $lastNames)
    {
        $this->attributes['last_name'] = $this->implodeFilter(',', $lastNames);
    }

    //date of birth
    public function getDateOfBirthAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setDateOfBirthAttribute(array $birthdates)
    {
        $this->attributes['date_of_birth'] = $this->implodeFilter(',', $birthdates);
    }

    //age
    public function getAgeAttribute($value)
    {
        return array_filter(explode(',', $value));
    }


    public function setAgeAttribute(array $ages)
    {
        $this->attributes['age'] = $this->implodeFilter(',', $ages);
    }

    //address
    public function getAddressAttribute($value)
    {
        return array_filter(explode('+', $value));
    }

    public function setAddressAttribute(array $addresses)
    {
        $this->attributes['address'] = $this->implodeFilter('+', $addresses);
    }

    //street
    public function getStreetAttribute($value)
    {
        return array_filter(explode('+', $value));
    }

    public function setStreetAttribute(array $streets)
    {
        $this->attributes['street'] = $this->implodeFilter('+', $streets);
    }

    //location
    public function getCityAttribute($value)
    {
        return array_filter(explode(';', $value));
    }

    public function setCityAttribute(array $cities)
    {
        $this->attributes['city'] = $this->implodeFilter(';', $cities);
    }

    //phone
    public function getPhoneAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setPhoneAttribute(array $phones)
    {
        $this->attributes['phone'] = $this->implodeFilter(',', $phones);
    }

    //occupation
    public function getCompanyAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setCompanyAttribute(array $companies)
    {
        $this->attributes['company'] = $this->implodeFilter(',', $companies);
    }

    //email
    public function getEmailAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setEmailAttribute(array $emails)
    {
        $this->attributes['email'] = $this->implodeFilter(',', $emails);
    }

    //username
    public function getUsernamesAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setUsernamesAttribute(array $usernames)
    {
        $this->attributes['usernames'] = strtolower($this->implodeFilter(',', $usernames));
    }

    //school
    public function getSchoolAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setSchoolAttribute(array $schools)
    {
        $this->attributes['school'] = $this->implodeFilter(',', $schools);
    }

    protected function implodeFilter($seperator, $array)
    {
        return trim(implode($seperator, $array), $seperator);
    }

    public function getZipAttribute($value)
    {
        return array_filter(explode(',', $value));
    }

    public function setZipAttribute(array $zipCodes)
    {
        $this->attributes['zip'] = $this->implodeFilter(',', $zipCodes);
    }
}
