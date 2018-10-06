<?php
namespace Skopenow\Reports\Models;
use Illuminate\Database\Eloquent\Model;

/**
*
*/
class ReportInfo extends Model
{
    
    protected $table = 'report_info';

    protected $fillable = [
        'id', 'report_id', 'company_name', 'search_date', 'subject_name', 'current_address',
        'date_of_birth', 'email', 'phone', 'occupation', 'school', 'usernames', 'relatives',
        'previous_locations', 'social_footprint', 'options', 'additional_notes', 'additional_notes_update_date',
        'additional_notes_mail_is_sent', 'profiles_data', 'phones_data', 'relatives_data', 'emails_data', 'addresses_data',
        'data_updated', 'report_changed'
    ];

    public $timestamps = false;

    //Options
    public function getOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setOptionsAttribute(array $options)
    {
        $this->attributes['options'] = json_encode($options);
    }
    
    //Subject name
    public function getSubjectNameAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setSubjectNameAttribute(array $names)
    {
        $this->attributes['subject_name'] = json_encode($names);
    }

    // //current address
    // public function getCurrentAddressAttribute($value)
    // {
    //     return json_decode($value, true);
    // }

    // public function setCurrentAddressAttribute(array $addresses)
    // {
    //     $this->attributes['current_address'] = json_encode($addresses);
    // }

    //previous_locations
    public function getPreviousLocationsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPreviousLocationsAttribute(array $previousLocations)
    {
        $this->attributes['previous_locations'] = json_encode($previousLocations);
    }

    //date of birth
    public function getDateOfBirthAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setDateOfBirthAttribute(array $dateOfBirth)
    {
        $this->attributes['date_of_birth'] = json_encode($dateOfBirth);
    }

    //email
    public function getEmailAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setEmailAttribute(array $email)
    {
        $this->attributes['email'] = json_encode($email);
    }

    //phone
    public function getPhoneAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPhoneAttribute(array $phone)
    {
        $this->attributes['phone'] = json_encode($phone);
    }


    //occupation
    public function getOccupationAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setOccupationAttribute(array $occupation)
    {
        $this->attributes['occupation'] = json_encode($occupation);
    }

    //school
    public function getSchoolAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setSchoolAttribute(array $school)
    {
        $this->attributes['school'] = json_encode($school);
    }

    //usernames
    public function getUsernamesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setUsernamesAttribute(array $usernames)
    {
        $this->attributes['usernames'] = json_encode($usernames);
    }

    //relatives
    public function getRelativesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRelativesAttribute(array $relatives)
    {
        $this->attributes['relatives'] = json_encode($relatives);
    }

    //phones_data
    public function getPhonesDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPhonesDataAttribute(array $phonesData)
    {
        $this->attributes['phones_data'] = json_encode($phonesData);
    }

    //relatives_data
    public function getRelativesDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRelativesDataAttribute(array $relativesData)
    {
        $this->attributes['relatives_data'] = json_encode($relativesData);
    }

    //emails_data
    public function getEmailsDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setEmailsDataAttribute(array $emailsData)
    {
        $this->attributes['emails_data'] = json_encode($emailsData);
    }

    //addresses_data
    public function getAddressesDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setAddressesDataAttribute(array $addressesData)
    {
        $this->attributes['addresses_data'] = json_encode($addressesData);
    }
}
