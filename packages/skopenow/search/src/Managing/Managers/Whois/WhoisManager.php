<?php

namespace Skopenow\Search\Managing\Managers\Whois;

use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;
use App\DataTypes\DataType;

class WhoisManager extends AbstractManager
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "whois";
    
    /**
     * @var FetcherInterface Source fetcher
     */
    protected $fetcher;

    protected function checkResult(SearchResultInterface $result)
    {
        #load match service.
        $matchingService = loadService("matching");
        $profileInfo = array(
            "name"      =>  iterator_to_array(DataType::getMainValues($result->getNames()), true),
            "location"  =>  iterator_to_array(DataType::getMainValues($result->getLocations()), true),
            "work"      =>  iterator_to_array(DataType::getMainValues($result->getExperiences()), true),
            "school"    =>  iterator_to_array(DataType::getMainValues($result->getEducations()), true),
            "email"     =>  iterator_to_array(DataType::getMainValues($result->getEmails()), true),
            "phone"     =>  iterator_to_array(DataType::getMainValues($result->getPhones()), true),
            // "username"  =>  iterator_to_array(DataType::getMainValues(new \ArrayIterator($result->getUsername())), true),
            // "age"       =>  iterator_to_array(DataType::getMainValues(new \ArrayIterator($result->getAge())), true),
        );
        if (!empty($result->getAge())) {
            $ageIterator = new \ArrayIterator();
            $ageIterator->append($result->getAge());
            $profileInfo['age'] = iterator_to_array(DataType::getMainValues($ageIterator), true);
        }
        if (!empty($result->getUsername())) {
            $usernameIterator = new \ArrayIterator();
            $usernameIterator->append($result->getUsername());
            $profileInfo['username'] = iterator_to_array(DataType::getMainValues($usernameIterator), true);
        }

        $matchStatus = $matchingService->check($profileInfo, [], $result->getIsRelative());
        $result->setMatchStatus($matchStatus);

        if ($matchStatus["name"]["status"]
            && $matchStatus["location"]["status"]
            && ( !empty($matchStatus["name"]['identities']['unq_name'])
            || !empty($matchStatus["location"]['identities']['exct-sm']) )
        ) {
            return true;
        }
        return false;
    }
}
