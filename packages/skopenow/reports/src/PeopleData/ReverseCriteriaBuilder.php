<?php

namespace Skopenow\Reports\PeopleData;

class ReverseCriteriaBuilder
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->buildReverseCriterias();
    }

    protected function buildReverseCriterias()
    {
        $criteria = [];
        if (!empty($this->data->email)) {
            // Level 0
            $criteria[] = $this->buildEmailCriteria();
        }
        if (!empty($this->data->phone)) {
            // Level 1
            $criteria[] = $this->buildPhoneCriteria();
        }
        if (!empty($this->data->username)) {
            // Level 2
            $criteria[] = $this->buildUsernameCriteria();
        }
        if (!empty($this->data->email)) {
            // Level 3
            $criteria[] = $this->buildEmailCriteria();
        }
        if (!empty($this->data->address)) {
            // Level 4
            $criteria[] = $this->buildAddressCriteria();
        }
        return $criteria;
    }

    protected function buildEmailCriteria()
    {
        $criteria = [];
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

    protected function buildPhoneCriteria()
    {
        $criteria = [];
        foreach ($this->data->phone as $phone) {
            $criteria[] = [
                ['api' => 'pipl', 'phone' => $phone],
                ['api' => 'tloxp', 'phone' => $phone],
                ['api' => 'whois', 'phone' => $phone],
            ];
        }
        return $criteria;
    }

    protected function buildUsernameCriteria()
    {
        $criteria = [];
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
        $criteria = [];
        foreach ($this->data->address as $address) {
            $criteria[] = [
                ['api' => 'tloxp', 'address' => $address],
                ['api' => 'pipl', 'address' => $address],
            ];
        }
        return $criteria;
    }
}
