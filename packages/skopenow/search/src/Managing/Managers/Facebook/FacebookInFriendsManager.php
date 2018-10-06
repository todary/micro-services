<?php

namespace Skopenow\Search\Managing\Managers\Facebook ;

/**
 * Description of the facebook main manager .
 *
 * @author ahmedsamir
 */
use Skopenow\Search\Managing\AbstractManager;
use Skopenow\Search\Fetching\FetcherInterface;
use Skopenow\Search\Models\DataPointInterface;
use Skopenow\Search\Models\SearchResultInterface;
use App\DataTypes\DataType;

class FacebookInFriendsManager extends AbstractManager
{
	/**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "facebook";

    /**
     * @const bool decide to run On Result Save event.
     */
    const Run_Main_Result_Event = true;

    protected $is_relative = false;


    protected function checkResult(SearchResultInterface $result)
    {
        $this->is_relative = $result->getIsRelative();
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
        if(!empty($result->getAge())) {
            $ageIterator = new \ArrayIterator();
            $ageIterator->append($result->getAge());
            $profileInfo['age'] = iterator_to_array(DataType::getMainValues($ageIterator), true);
        }
        if(!empty($result->getUsername())) {
            $usernameIterator = new \ArrayIterator();
            $usernameIterator->append($result->getUsername());
            $profileInfo['username'] = iterator_to_array(DataType::getMainValues($usernameIterator), true);
        }

        $matchStatus = $matchingService->check($profileInfo,[],$this->is_relative);
        if (!$matchStatus['name']['status']) {
            $this->is_relative = !$this->is_relative;
            $matchStatus = $matchingService->check($profileInfo,[],$this->is_relative, true);
            $result->setIsRelative($this->is_relative);
            if (!$this->is_relative) {
                $result->addIdentityShouldHave('rltvWithMain');
            }
        }
        $result->setMatchStatus($matchStatus);
        return true;
    }
}
