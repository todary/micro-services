<?php
namespace Skopenow\PeopleData;

use Skopenow\PeopleData\OutputModel;

class ResultMatcher implements ResultMatcherInterface
{
    protected $searchInputs = [];
    protected $groupedResults = [];

    public function __construct(array $searchInputs = [], array $groupedResults = [])
    {
        $this->searchInputs = $searchInputs;
        $this->groupedResults = $groupedResults;
    }

    public function doMatch(OutputModel $result1, OutputModel $result2): bool
    {
        /* Merge if
         - FML matches FML or FLM (Exactly or part of)
         - FL+2Relative Names(Or there is only one relative) (FL or FM)
         - FL+(at least one phone or email or full address)

         * Note: Name match applies to names or other names
         * Note: Always consider age range of 5 years to merge
        */

        $result1Names = $result1->extractResultNames();
        $result2Names = $result2->extractResultNames();
        $id1 = substr($result1->report_id, 0, 20);
        $id2 = substr($result2->report_id, 0, 20);
        \Log::info('matching start', [$id1, $id2, $result1Names, $result2Names]);
        // \Log::debug('matching result 1', [$result1]);
        // \Log::debug('matching result 2', [$result2]);

        $result1Batch = $this->groupedResults[$result1->group][$result1->trial][$result1->source]??[];
        $result2Batch = $this->groupedResults[$result2->group][$result2->trial][$result2->source]??[];
        if ($result1Batch && $result2Batch && count($result1Batch)==1 && count($result2Batch)==1) {
            if ($result1->group == $result2->group && $result1->trial == $result2->trial && in_array($result1->source, ['-', 'pipl']) && in_array($result2->source, ['-', 'pipl']) && $result1->source!=$result2->source) {
                $result1Usernames = $result1->extractUsernames();
                $result2Usernames = $result2->extractUsernames();

                if ($result1->source == "pipl" && $result1Usernames || $result2->source == "pipl" && $result2Usernames) {
                    \Log::info('MATCH: Same level results from Pipl/Tloxp rule detected. Merging...');
                    return true;
                }
            }
        }



        if (!empty($result1->age) && !empty($result2->age) && abs($result1->age-$result2->age)>5) {
            \Log::info('not matched age');
            return false;
        } else {
            \Log::info('Ignored age');
        }

        // - FML matches FML or FLM or FM or FL (Exactly or part of)
        if ($isNamesMatched = $this->isNamesMatched($result1Names, $result2Names)) {
            \Log::info('FML matched, checking other factors...');

            // - FL+2Relative Names(Or there is only one relative) (FL or FM)
            $result1RelativesNames = $result1->extractRelativeNames();
            $result2RelativesNames = $result2->extractRelativeNames();
            if ($result1RelativesNames['names'] && $result2RelativesNames['names']) {
                if ($this->isRelativesMatched($result1RelativesNames, $result2RelativesNames)) {
                    \Log::info('MATCH: FML Names & relatives matched');
                    return true;
                } else {
                    \Log::info('Names & relatives not matched');
                }
            } else {
                \Log::info('MATCH: Names matched and no relatives in both');
                return true;
            }
        }

        if ($this->isFLNamesMatched($result1Names, $result2Names)) {
            \Log::info('FL matched, checking other factors...');

            // - FL+2Relative Names(Or there is only one relative) (FL or FM)
            $result1RelativesNames = $result1->extractRelativeNames();
            $result2RelativesNames = $result2->extractRelativeNames();
            if ($this->isRelativesMatched($result1RelativesNames, $result2RelativesNames)) {
                \Log::info('MATCH: Names & relatives matched');
                return true;
            } else {
                \Log::info('Names & relatives not matched');
            }

            // - FL+(at least one phone)
            $result1Phones = $result1->extractPhones();
            $result2Phones = $result2->extractPhones();
            if ($this->isPhonesMatched($result1Phones, $result2Phones)) {
                \Log::info('MATCH: Names & phones matched');
                return true;
            } else {
                \Log::info('Names & phones not matched');
            }

            // - FL+(at least one email)
            $result1Email = $result1->extractEmails();
            $result2Email = $result2->extractEmails();
            if ($this->isEmailsMatched($result1Email, $result2Email)) {
                \Log::info('MATCH: Names & emails matched');
                return true;
            } else {
                \Log::info('Names & emails not matched');
            }

            // - FL+(at least one addresses)
            $result1Addresses = $result1->extractAddresses();
            $result2Addresses = $result2->extractAddresses();
            if ($this->isAddressesMatched($result1Addresses, $result2Addresses)) {
                \Log::info('MATCH: Names & addresses matched');
                return true;
            } else {
                \Log::info('Names & addresses not matched');
            }
        } else {
            \Log::info('FL not matched');
        }

        return false;
    }

    public function isPartOfEachOther(string $string1, string $string2): bool
    {
        if ($string1==$string2) {
            return true;
        }

        if (empty($string1) || empty($string2)) {
            return false;
        }

        if (mb_strlen($string1)>mb_strlen($string2) && mb_stripos($string1, $string2) === 0) {
            return true;
        }

        if (mb_strlen($string2)>mb_strlen($string1) && mb_stripos($string2, $string1) === 0) {
            return true;
        }

        return false;
    }

    public function isNamesMatched(array $names1, array $names2): bool
    {
        // - FML matches FML or FLM (Exactly or part of)
        foreach ($names1['names'] as $name1) {
            foreach ($names2['names'] as $name2) {
                // Ignore empty names
                if (empty($name1['full_name']) || empty($name2['full_name'])) {
                    continue;
                }

                if ($this->isFullNameMatched($name1, $name2, $names1['has_middle_name'], $names2['has_middle_name'])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isFullNameMatched(array $name1, array $name2, bool $names1_has_middle_name = false, bool $names2_has_middle_name = false): bool
    {
        if (!$name1['middle_name'] || !$name2['middle_name']) {
            return false;
        }
        // Full
        if ($this->isPartOfEachOther($name1['full_name'], $name2['full_name'])) {
            \Log::info('full matched', [$name1, $name2]);
            return true;
        }

        // FML = FML
        if ($this->isPartOfEachOther($name1['first_name'], $name2['first_name']) &&
            $this->isPartOfEachOther($name1['middle_name'], $name2['middle_name']) &&
            $this->isPartOfEachOther($name1['last_name'], $name2['last_name'])) {
            \Log::info('fml matched', [$name1, $name2]);
            return true;
        }

        // FML = FLM
        if ($this->isPartOfEachOther($name1['first_name'], $name2['first_name']) &&
            $this->isPartOfEachOther($name1['middle_name'], $name2['last_name']) &&
            $this->isPartOfEachOther($name1['last_name'], $name2['middle_name'])) {
            \Log::info('flm matched', [$name1, $name2]);
            return true;
        }

        return false;
    }

    public function isFLNamesMatched(array $names1, array $names2): bool
    {
        // - FL+2Relative Names(Or there is only one relative) (FL or FM)
        foreach ($names1['names'] as $name1) {
            foreach ($names2['names'] as $name2) {
                // Ignore empty names
                if (empty($name1['full_name']) || empty($name2['full_name'])) {
                    continue;
                }

                if ($this->isPartialNameMatched($name1, $name2, $names1['has_middle_name'], $names2['has_middle_name'])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isPartialNameMatched(array $name1, array $name2, bool $name1_has_middle_name = false, bool $name2_has_middle_name = false): bool
    {
        // FL = FL
        if ($this->isPartOfEachOther($name1['first_name'], $name2['first_name']) &&
            $this->isPartOfEachOther($name1['last_name'], $name2['last_name']) &&
            !($name1_has_middle_name && $name2_has_middle_name)) {
            return true;
        }

        // FM = FL
        if ($this->isPartOfEachOther($name1['first_name'], $name2['first_name']) &&
            $this->isPartOfEachOther($name1['middle_name'], $name2['last_name']) &&
            !$name2_has_middle_name) {
            return true;
        }

        return false;
    }

    public function isRelativesMatched(array $names1, array $names2): bool
    {
        // - FL+2Relative Names(Or there is only one relative) (FL or FM)

        if (empty($names1['names']) || empty($names2['names'])) {
            return false;
        }

        $namesMatched = 0;
        foreach ($names1['names'] as $name1) {
            foreach ($names2['names'] as $name2) {
                // Ignore empty names
                if (empty($name1['full_name']) || empty($name2['full_name'])) {
                    continue;
                }

                if ($this->isFullNameMatched($name1, $name2, $names1['has_middle_name'], $names2['has_middle_name'])) {
                    $namesMatched++;
                    continue;
                }

                if ($this->isPartialNameMatched($name1, $name2, $names1['has_middle_name'], $names2['has_middle_name'])) {
                    $namesMatched++;
                    continue;
                }
            }
        }

        /*
        if ($namesMatched >= 2) {
            return true;
        }
        */

        $matchPercent = 100*$namesMatched/min(count($names1['names']), count($names2['names']));

        return $matchPercent>=35;
    }

    public function isPhonesMatched(array $phones1, array $phones2): bool
    {
        foreach ($phones1 as $phone1) {
            foreach ($phones2 as $phone2) {
                // Ignore empty phones
                if (empty($phone1) || empty($phone2)) {
                    continue;
                }

                if ($phone1 == $phone2) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isEmailsMatched(array $emails1, array $emails2): bool
    {
        foreach ($emails1 as $email1) {
            foreach ($emails2 as $email2) {
                // Ignore empty emails
                if (empty($email1) || empty($email2)) {
                    continue;
                }

                if ($email1 == $email2) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isAddressesMatched(array $addresses1, array $addresses2): bool
    {
        foreach ($addresses1 as $address1) {
            foreach ($addresses2 as $address2) {
                // Ignore empty addresses
                if (empty($address1['address']) || empty($address2['address'])) {
                    continue;
                }

                if (mb_stripos($address1['address'], $address2['address'])!==false || mb_stripos($address2['address'], $address1['address'])!==false) {
                    return true;
                }
            }
        }

        return false;
    }
}
