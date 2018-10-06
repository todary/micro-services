<?php
namespace Skopenow\PeopleData;

use Skopenow\PeopleData\OutputModel;

interface ResultMatcherInterface
{
    public function doMatch(OutputModel $result1, OutputModel $result2): bool;

    public function isPartOfEachOther(string $string1, string $string2): bool;

    public function isNamesMatched(array $names1, array $names2): bool;

    public function isFullNameMatched(array $name1, array $name2, bool $name1_has_middle_name = false, bool $name2_has_middle_name = false): bool;

    public function isFLNamesMatched(array $names1, array $names2): bool;

    public function isPartialNameMatched(array $name1, array $name2, bool $name1_has_middle_name = false, bool $name2_has_middle_name = false): bool;

    public function isRelativesMatched(array $names1, array $names2): bool;

    public function isPhonesMatched(array $phones1, array $phones2): bool;

    public function isEmailsMatched(array $emails1, array $emails2): bool;

    public function isAddressesMatched(array $addresses1, array $addresses2): bool;
}
