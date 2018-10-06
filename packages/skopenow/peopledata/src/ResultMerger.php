<?php
namespace Skopenow\PeopleData;

use Skopenow\PeopleData\OutputModel;

/**
 * these class is merging results returned from api
 */
class ResultMerger implements ResultMergerInterface
{
    public function mergeAll(array $results, ResultMatcherInterface $matcher): array
    {
        $merged_results = [];
        foreach ($results as $new_result) {
            foreach ($merged_results as $result) {
                if ($matcher->doMatch($result, $new_result)) {
                    $this->merge($result, $new_result);
                    continue 2;
                }
            }

            $merged_results []= $new_result;
        }

        return $merged_results;
    }

    public function merge(OutputModel $result1, OutputModel $result2)
    {
        $addRank = false;

        // Copy empty attributes
        $attributes = get_object_vars($result1);
        foreach ($attributes as $attribute => $value) {
            if ($value === "" || $value === [] || $value === null) {
                $result1->$attribute = $result2->$attribute;
            }
        }

/*        
        public $usernames = [];
        public $images = [];
        public $profiles = [];
*/
        // Merge names
        $result1Names = $result1->extractResultNames();
        $result2Names = $result2->extractResultNames();
        $additionalNames = $this->getNewNames($result2Names['names'], $result1Names['names']);
        if (!empty($additionalNames)) {
            $result1->other_names = array_merge($result1->other_names, $additionalNames);
            $addRank = true;
        }

        // Merge phones
        $result1Phones = $result1->extractPhones();
        $result2Phones = $result2->extractPhones();
        $additionalPhones = array_diff($result2Phones, $result1Phones);
        if (!empty($additionalPhones)) {
            $result1->phones = array_merge($result1->phones, $additionalPhones);
            $addRank = true;
        }

        // Merge emails
        $result1Emails = $result1->extractEmails();
        $result2Emails = $result2->extractEmails();
        $additionalEmails = array_diff($result2Emails, $result1Emails);
        if (!empty($additionalEmails)) {
            $result1->emails = array_merge($result1->emails, $additionalEmails);
            $addRank = true;
        }

        // Merge usernames
        $result1Usernames = $result1->extractUsernames();
        $result2Usernames = $result2->extractUsernames();
        $additionalUsernames = array_diff($result2Usernames, $result1Usernames);
        if (!empty($additionalUsernames)) {
            $result1->usernames = array_merge($result1->usernames, $additionalUsernames);
            $addRank = true;
        }

        // Merge images
        $result1Images = $result1->extractImages();
        $result2Images = $result2->extractImages();
        $additionalImages = array_diff($result2Images, $result1Images);
        if (!empty($additionalImages)) {
            $result1->images = array_merge($result1->images, $additionalImages);
            $addRank = true;
        }

        // Merge profiles
        $result1Profiles = $result1->extractProfiles();
        $result2Profiles = $result2->extractProfiles();
        $additionalProfiles = $this->getNewProfiles($result2Profiles, $result1Profiles);
        if (!empty($additionalProfiles)) {
            $result1->profiles = array_merge($result1->profiles, $additionalProfiles);
            $addRank = true;
        }

        // Merge relatives
        $result1RelativeNames = $result1->extractRelativeNames();
        $result2RelativeNames = $result2->extractRelativeNames();
        $additionalRelativeNames = $this->getNewNames($result2RelativeNames['names'], $result1RelativeNames['names']);
        if (!empty($additionalRelativeNames)) {
            $result1->relatives = array_merge($result1->relatives, $additionalRelativeNames);
            $addRank = true;
        }

        // Merge addresses
        if ($result1->source == "pipl" && $result2->source != "pipl") {
            // Ignore pipl addresses
            $result1->location = $result2->location;
            $result1->street = $result2->street;
            $result1->address = $result2->address;
            $result1->city = $result2->city;
            $result1->state = $result2->state;
            $result1->zip = $result2->zip;

            $result1->addresses = $result2->addresses;
            $addRank = true;
        } else if ($result2->source == "pipl" && $result1->source != "pipl") {
            // Ignore pipl addresses
        } else {
            $result1Addresses = $result1->extractAddresses();
            $result2Addresses = $result2->extractAddresses();
            $additionalAddresses = $this->getNewAddresses($result2Addresses, $result1Addresses);
            if (!empty($additionalAddresses)) {
                $result1->addresses = array_merge($result1->addresses, $additionalAddresses);
                $addRank = true;
            }
        }

        $source1 = $result1->link;
        $source2 = $result2->link;

        if ($result1->report_id) {
            $source1 .= ':'.substr($result1->report_id, 0, 20);
        }

        if ($result2->report_id) {
            $source2 .= ':'.substr($result2->report_id, 0, 20);
        }

        $result1->merged_sources[] = $source1;
        $result1->merged_sources[] = $source2;
        $result1->merged_sources = array_unique($result1->merged_sources);
        
        if ($result1->source != $result2->source) {
            $result1->source = "merged";

            if ($addRank) {
                $result1->result_rank += $result2->result_rank;
            }
        }

        $result1->modified = $result1->modified && $result2->modified;
    }

    protected function getNewNames(array $names2, array $names1): array
    {
        if (empty($names1)) {
            return $names2;
        }

        $return = array();

        foreach ($names2 as $name2) {
            foreach ($names1 as $name1) {
                if ($name1['full_name'] == $name2['full_name']) {
                    continue 2;
                }
            }

            $return []= $name2;
        }
        return $return;
    }

    protected function getNewProfiles(array $profiles2, array $profiles1): array
    {
        if (empty($profiles1)) {
            return $profiles2;
        }

        $return = array();

        foreach ($profiles2 as $profile2) {
            foreach ($profiles1 as $profile1) {
                if ($profile1['url'] == $profile2['url']) {
                    continue 2;
                }
            }

            $return []= $profile2;
        }
        return $return;
    }

    protected function getNewAddresses(array $addresses2, array $addresses1): array
    {
        if (empty($addresses1)) {
            return $addresses2;
        }

        $return = array();

        foreach ($addresses2 as $address2) {
            foreach ($addresses1 as $address1) {
                if ($address1['address'] == $address2['address']) {
                    continue 2;
                }
            }

            $return []= $address2;
        }
        return $return;
    }
}
