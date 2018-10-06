<?php

namespace App\Models;

use Skopenow\Search\Models\SearchResultInterface;
use App\Models\Result;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Work;
use App\DataTypes\School;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Age;
use App\DataTypes\Username;
use App\DataTypes\Website;

class ResultData implements SearchResultInterface
{
    public $id = null;
    public $type = "result";
    public $url = "";
    public $unique_url = "";
    public $mainSource = "";
    public $source = "";
    public $screenshotUrl = null;
    public $searchList = null;
    public $resultsCount = 0;
    public $orderInList = 0;
    public $image = null;
    public $accountUsed = null;
    public $title = null;
    public $description = null;
    public $social_profile_id = null;

    protected $names = null;
    protected $username = null;
    protected $locations = null;
    protected $experiences = null;
    protected $educations = null;
    protected $age    = null;
    protected $emails = null;
    protected $phones = null;
    protected $websites = null;

    protected $isProfile = null;
    protected $isRelative = false;
    protected $isPrimary = null;

    protected $score = null;
    protected $scoreIdentities = array();
    protected $flags = 0;
    protected $matching_flags = 0;
    protected $input_flags = 0;
    protected $extra_flags = 0;

    protected $matchStatus = [];

    protected $isManual = 0;
    protected $isInput = 0;
    protected $isPeopleData = 0;
    protected $relatedTo = "";
    protected $rawType = "result";
    protected $childRank = "";
    protected $html = "";
    protected $tags = [];
    protected $alternativeUrl = "";
    protected $spidered = 0;
    protected $isDelete = 0;
    protected $invisible = 0;
    protected $links = null;
    public $Run_Main_Result_Event = false;
    protected $saveStatus;
    protected $isPending = null;
    protected $identitiesShouldHave = null;
    protected $additionalIdentifiers = null;

    public $subResultsNeedProfileInfo = [];

    public function __construct(string $url, $isProfile = null)
    {
        $url = rtrim($url, '/ ');

        if ($url) {
            \Log::info('Init ResultData for:' . $url);
            $urlInfo = loadService('urlInfo');
            $url = $urlInfo->normalizeURL($url, true);
            \Log::info('Init ResultData normalized as:' . $url);

            $this->url = $url;
            $this->screenshotUrl = $url;

            $this->setUniqueUrl();
            $this->setSourceFromUrl();

            if ($isProfile === null) {
                \Log::info('Check is profile:' . $url);
                $this->setIsProfile($urlInfo->isProfile($url, [], true));
                \Log::info('Done check is profile:' . $url);
            } else {
                $this->setIsProfile($isProfile);
            }

            if ($this->isProfile) {
                \Log::info('Get username for:' . $url);
                $username = $urlInfo->getUsername($url, true);
                \Log::info('Done get username for:' . $url);
                if ($username) {
                    $this->setUsername(Username::create(['username' => $username], $this->mainSource));
                }
            }
        }

        $this->names = new \ArrayIterator();
        $this->locations = new \ArrayIterator();
        $this->experiences = new \ArrayIterator();
        $this->educations = new \ArrayIterator();
        $this->age = null;
        $this->emails = new \ArrayIterator();
        $this->phones = new \ArrayIterator();
        $this->websites = new \ArrayIterator();
        $this->links = new \ArrayIterator();
        $this->subResultsNeedProfileInfo = [];
        $this->identitiesShouldHave = new \ArrayIterator();
        $this->additionalIdentifiers = new \ArrayIterator();
    }

    public function __get($property)
    {
        $methodName = 'get' . ucfirst($property);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return isset($this->$property) ? $this->$property : null;
    }

    public function __set(string $property, $value)
    {
        if ($property == "username" && is_string($value)) {
            $value = Username::create(['username' => $value], $this->mainSource);
        }

        $methodName = 'set' . ucfirst($property);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($value);
        }

        $this->$property = $value;

        //throw new \Exception('Property Setter Not Found !.');
    }

    public function clearProfileInfo(string $type = "")
    {
        switch ($type) {
            case 'names':
                $this->names = new \ArrayIterator();
                break;
            case 'locations':
                $this->locations = new \ArrayIterator();
                break;
            case 'experiences':
                $this->experiences = new \ArrayIterator();
                break;
            case 'educations':
                $this->educations = new \ArrayIterator();
                break;
            case 'age':
                $this->age = null;
                break;
            case 'emails':
                $this->emails = new \ArrayIterator();
                break;
            case 'phones':
                $this->phones = new \ArrayIterator();
                break;
            case 'websites':
                $this->websites = new \ArrayIterator();
                break;
            case 'links':
                $this->websites = new \ArrayIterator();
                break;
            case '':
                $this->names = new \ArrayIterator();
                $this->locations = new \ArrayIterator();
                $this->experiences = new \ArrayIterator();
                $this->educations = new \ArrayIterator();
                $this->age = null;
                $this->emails = new \ArrayIterator();
                $this->phones = new \ArrayIterator();
                $this->websites = new \ArrayIterator();
                break;
        }
    }

    public function setUniqueUrl(string $url = "")
    {
        if (!$url) {
            $url = $this->url;
        }
        $urlInfo = loadService('urlInfo');
        $this->unique_url = $urlInfo->prepareContent($url);
    }

    public function setSourceFromUrl($url = "")
    {
        if (!$url) {
            $url = $this->url;
        }
        $urlInfo = loadService('urlInfo');
        $sourceInfo = $urlInfo->determineSourceAssoc($url);
        $this->mainSource = $sourceInfo['mainSource'];
        $this->source = $sourceInfo['source'];
    }

    public function addName(Name $name): SearchResultInterface
    {
        if (empty($name)) {
            return $this;
        }

        if (is_string($name)) {
            $name = str_replace("  ", " ", $name);
            $name = trim($name);

            $explodedName = explode(' ', $name, 3);
            $first = $explodedName[0];
            $middle = "";
            if (!empty($explodedName[2])) {
                $middle = $explodedName[1] ?? '';
            }
            $last = $explodedName[2] ?? $explodedName[1];

            $name = ['first_name'=>$first, 'middle_name'=>$middle, 'last_name'=>$last, 'full_name'=>$name];
        }

        $this->names->append($name);

        return $this;
    }

    public function addLocation(Address $location): SearchResultInterface
    {
        if (empty($location)) {
            return $this;
        }

        $this->locations->append($location);

        return $this;
    }

    public function addEducation(School $education): SearchResultInterface
    {
        if (empty($education)) {
            return $this;
        }

        $this->educations->append($education);

        return $this;
    }

    public function getExperiences(): \Iterator
    {
        $this->experiences->rewind();
        return $this->experiences;
    }

    public function addExperience(Work $experience): SearchResultInterface
    {
        if (empty($experience)) {
            return $this;
        }

        $this->experiences->append($experience);

        return $this;
    }

    public function addAge(Age $age): SearchResultInterface
    {
        if (empty($age)) {
            return $this;
        }

        $this->age = $age ;

        return $this;
    }

    public function addEmail(Email $email): SearchResultInterface
    {
        if (empty($email)) {
            return $this;
        }
        $this->emails->append($email);

        return $this;
    }

    public function addPhone(Phone $phone): SearchResultInterface
    {
        if (empty($phone)) {
            return $this;
        }

        $this->phones->append($phone);

        return $this;
    }

    public function addWebsite(Website $website): SearchResultInterface
    {
        if (empty($website)) {
            return $this;
        }

        $this->websites->append($website);

        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScreenshotUrl()
    {
        return $this->screenshotUrl;
    }

    /**
     * @param mixed $screenshotUrl
     *
     * @return self
     */
    public function setScreenshotUrl($screenshotUrl)
    {
        $this->screenshotUrl = $screenshotUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSearchList()
    {
        return $this->searchList;
    }

    /**
     * @param mixed $searchList
     *
     * @return self
     */
    public function setSearchList($searchList)
    {
        $this->searchList = $searchList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderInList()
    {
        return $this->orderInList;
    }

    /**
     * @param mixed $orderInList
     *
     * @return self
     */
    public function setOrderInList($orderInList)
    {
        $this->orderInList = $orderInList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername(Username $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountUsed()
    {
        return $this->accountUsed;
    }

    /**
     * @param mixed $accountUsed
     *
     * @return self
     */
    public function setAccountUsed($accountUsed)
    {
        $this->accountUsed = $accountUsed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNames(): \Iterator
    {
        $this->names->rewind();
        return $this->names;
    }

    /**
     * @param mixed $names
     *
     * @return self
     */
    public function setNames($names)
    {
        $this->names = $names;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocations(): \Iterator
    {
        $this->locations->rewind();
        return $this->locations;
    }

    /**
     * @param mixed $locations
     *
     * @return self
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * @return Age
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return \Iterator
     */
    public function getEmails(): \Iterator
    {
        $this->emails->rewind();
        return $this->emails;
    }

    /**
     * @return \Iterator
     */
    public function getPhones(): \Iterator
    {
        $this->phones->rewind();
        return $this->phones;
    }

    /**
     * @return \Iterator
     */
    public function getWebsites(): \Iterator
    {
        $this->websites->rewind();
        return $this->websites;
    }

    /**
     * @return bool
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEducations(): \Iterator
    {
        $this->educations->rewind();
        return $this->educations;
    }

    /**
     * @param mixed $educations
     *
     * @return self
     */
    public function setEducation($educations)
    {
        $this->educations = $educations;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsProfile()
    {
        return $this->isProfile;
    }

    /**
     * @param mixed $isProfile
     *
     * @return self
     */
    public function setIsProfile($isProfile): SearchResultInterface
    {
        $this->isProfile = $isProfile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRelative()
    {
        return $this->isRelative;
    }

    /**
     * @return mixed
     */
    public function getSaveStatus()
    {
        return $this->saveStatus;
    }

    /**
     * @param mixed $isRelative
     *
     * @return self
     */
    public function setIsRelative($isRelative): SearchResultInterface
    {
        $this->isRelative = $isRelative;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * @param mixed $isPrimary
     *
     * @return self
     */
    public function setIsPrimary($isPrimary): SearchResultInterface
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     *
     * @return self
     */
    public function setScore($score): SearchResultInterface
    {
        $this->score = $score;

        return $this;
    }

    /**
     * [getScoreIdentities return score identities]
     * @return \Iterator
     */
    public function getScoreIdentities(): array
    {
        if (is_string($this->scoreIdentities)) {
            return json_decode($this->scoreIdentities, true);
        }
        return $this->scoreIdentities;
    }

    public function setScoreIdentities($scoreIdentities): SearchResultInterface
    {
        $this->scoreIdentities = $scoreIdentities;

        return $this;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function getMatchingFlags(): int
    {
        return $this->matching_flags;
    }

    public function getInputFlags(): int
    {
        return $this->input_flags;
    }

    public function getExtraFlags(): int
    {
        return $this->extra_flags;
    }

    public function setFlags($flags): SearchResultInterface
    {
        $this->flags = $flags;
        return $this;
    }

    public function setMatchingFlags($flags): SearchResultInterface
    {
        $this->matching_flags = $flags;
        return $this;
    }

    public function setInputFlags($flags): SearchResultInterface
    {
        $this->input_flags = $flags;
        return $this;
    }

    public function setExtraFlags($flags): SearchResultInterface
    {
        $this->extra_flags = $flags;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMatchStatus()
    {
        $status = [
            'matchingData'=>$this->matchStatus,
            'link'=>$this->url,
            'resultsCount'=>$this->resultsCount,
            'main_source'=>$this->mainSource,
            'source'=>$this->source,
            'type'  => $this->type,
            'isProfile'=>$this->getIsProfile(),
            'isRelative'=>$this->getIsRelative(),
            'isInput'   =>  $this->getIsInput(),
            'isPeopleData'   =>  $this->getIsPeopleData(),
            'identitiesShouldHave' => iterator_to_array($this->getIdentitiesShouldHave()),
            'additionalIdentifiers' => iterator_to_array($this->getAdditionalIdentifiers()),
        ];

        return $status;
    }

    /**
     * @param mixed $matchStatus
     *
     * @return self
     */
    public function setMatchStatus($matchStatus)
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsManual()
    {
        return $this->isManual;
    }

    /**
     * @param mixed $isManual
     *
     * @return self
     */
    public function setIsManual($isManual)
    {
        $this->isManual = $isManual;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsInput()
    {
        return $this->isInput;
    }

    /**
     * @param mixed $isInput
     *
     * @return self
     */
    public function setIsInput($isInput)
    {
        $this->isInput = $isInput;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsPeopleData()
    {
        return $this->isPeopleData;
    }

    /**
     * @param mixed $isPeopleData
     *
     * @return self
     */
    public function setIsPeopleData($isPeopleData)
    {
        $this->isPeopleData = $isPeopleData;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelatedTo()
    {
        return $this->relatedTo;
    }

    /**
     * @param mixed $relatedTo
     *
     * @return self
     */
    public function setRelatedTo($relatedTo)
    {
        $this->relatedTo = $relatedTo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawType()
    {
        return $this->rawType;
    }

    /**
     * @param mixed $rawType
     *
     * @return self
     */
    public function setRawType($rawType)
    {
        $this->rawType = $rawType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildRank()
    {
        return $this->childRank;
    }

    /**
     * @param mixed $childRank
     *
     * @return self
     */
    public function setChildRank($childRank)
    {
        $this->childRank = $childRank;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     *
     * @return self
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     *
     * @return self
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlternativeUrl()
    {
        return $this->alternativeUrl;
    }

    /**
     * @param mixed $alternativeUrl
     *
     * @return self
     */
    public function setAlternativeUrl($alternativeUrl)
    {
        $this->alternativeUrl = $alternativeUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpidered()
    {
        return $this->spidered;
    }

    /**
     * @param mixed $spidered
     *
     * @return self
     */
    public function setSpidered($spidered)
    {
        $this->spidered = $spidered;

        return $this;
    }

    public function getResultInfo(): array
    {
        return array(
            "names"         =>  $this->getNames(),
            "location"      =>  $this->getLocations(),
            "experiences"   =>  $this->getExperiences(),
            "educations"    =>  $this->getEducations(),
            "age"           =>  $this->getAge(),
            "emails"        =>  $this->getEmails(),
            "phones"        =>  $this->getPhones(),
        ) ;
    }

    public function save(): bool
    {
        $resultService = loadService("result");
        $status = $resultService->save($this);
        $this->saveStatus = $status;

        // echo "Result saved!\n";
        return !empty($status);
    }

    /**
     * @return mixed
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * @param mixed $isDelete
     *
     * @return self
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvisible()
    {
        return $this->invisible;
    }

    /**
     * @param mixed $invisible
     *
     * @return self
     */
    public function setInvisible($invisible)
    {
        $this->invisible = $invisible;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsPending()
    {
        return $this->isPending;
    }

    /**
     * @param mixed $isPending
     *
     * @return self
     */
    public function setIsPending($isPending)
    {
        $this->isPending = $isPending;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        $this->links->rewind();
        $this->processSubLinksProfileInfo();
        return $this->links;
    }

    protected function processSubLinksProfileInfo()
    {
        if (!$this->subResultsNeedProfileInfo) {
            return;
        }

        \Log::info('Start processSubLinksProfileInfo for:' . $this->url, [$this->subResultsNeedProfileInfo]);
        $urlInfo = loadService('UrlInfo');
        foreach ($this->subResultsNeedProfileInfo as $resultData) {
            \Log::info('Getting profile info for sub result:' . $resultData->url);
            $info = $urlInfo->getProfileInfo($resultData->url, $resultData->mainSource);
            \Log::info('Done getting profile info for sub result:' . $resultData->url);

            if ((isset($info['name']) && empty($info['name'])) && (isset($info['location']) &&empty($info['location']))) {
                return $this;
            }

            $logInfo = $info;
            unset($logInfo['body']);
            unset($logInfo['profile']);

            \Log::info("Setting profile info for sub result:" . $resultData->url, [$logInfo]);

            if (!empty($link['is_relative'])) {
                $resultData->setIsRelative($link['is_relative']);
            }
            $resultData->setProfileInfo($info, true);
            \Log::info('End setting profile info for sub result:' . $resultData->url);
        }
        \Log::info('End processSubLinksProfileInfo for:' . $this->url);
    }

    /**
     * [$links description]
     * [
     *     [
     *         "url"    => "facebook.com",
     *         "reason" =>  int ,
     *         "id"     =>  int ,
     *     ],
     * ]
     * @var [array]
     */

    /**
     * @param mixed $link
     *
     * @return self
     */
    public function addLink($link): SearchResultInterface
    {
        if (empty($link)) {
            return $this;
        }

        if ((empty($link['url']) && empty($link['id'])) || empty($link['reason'])) {
            throw new \Exception("URL or result_id and Reason can not be empty!");
        }

        if (!isset($link['id'])) {
            $link['id'] = null;
        }
        if (!isset($link['url'])) {
            $link['url'] = null;
        }
        if (!isset($link['is_profile'])) {
            $link['is_profile'] = null;
        }

        $resultData = new self($link['url'], $link['is_profile']);

        if (isset($link['is_relative'])) {
            $resultData->setIsRelative($link['is_relative']);
        }

        if ($resultData->getIsProfile()) {
            $this->subResultsNeedProfileInfo[] = $resultData;

            if (!empty($link['identitiesShouldHave'])) {
                foreach ($link['identitiesShouldHave'] as $identityShouldHave) {
                    $resultData->addIdentityShouldHave($identityShouldHave);
                }
            }
        }

        $link['result'] = $resultData;

        $this->links->append($link);

        return $this;
    }

    /**
     * @param mixed $links
     *
     * @return self
     */
    public function setLinks($links): SearchResultInterface
    {
        $this->links = new \ArrayIterator();
        $this->subResultsNeedProfileInfo = [];

        if ($links) {
            foreach ($links as $link) {
                $this->addLink($link);
            }
        }

        return $this;
    }

    public function addIdentityShouldHave(string $identity): self
    {
        if (empty($identity)) {
            return $this;
        }

        $this->identitiesShouldHave->append($identity);

        return $this;
    }

    public function getIdentitiesShouldHave(): \Iterator
    {
        return $this->identitiesShouldHave;
    }

    public function addAdditionalIdentifier(string $identifier): self
    {
        if (empty($identifier)) {
            return $this;
        }

        $this->additionalIdentifiers->append($identifier);

        return $this;
    }

    public function getAdditionalIdentifiers(): \Iterator
    {
        return $this->additionalIdentifiers;
    }

    public static function fromModel(Result $result)
    {
        $resultData = new self($result->url);
        $resultData->id = $result->id;
        $resultData->mainSource = $result->main_source;
        $resultData->source = $result->source;
        $resultData->image = $result->profile_image;
        $resultData->setFlags($result->flags);
        $resultData->setFlags($result->flags);
        $resultData->setScore($result->score);
        $resultData->setScoreIdentities(json_decode($result->score_identity));
        $resultData->setIsDelete($result->is_deleted);
        $resultData->setInvisible($result->invisible);

        return $resultData;
    }

    public function setProfileInfo(array $info, bool $is_sub_link = false)
    {
        $this->clearProfileInfo();

        if (!empty($info['image'])) {
            $this->image = $info['image'];
        }

        if (!empty($info['profileUrl'])) {
            $urlInfo = loadService('urlInfo');

            $url = $info['profileUrl'];

            if ($this->screenshotUrl == $this->url) {
                $this->screenshotUrl = $url;
            }

            $this->url = $url;

            $this->setUniqueUrl();
            $this->setSourceFromUrl();

            // $this->setIsProfile($urlInfo->isProfile($url));

            if ($this->isProfile) {
                $username = $urlInfo->getUsername($url, true);
                if ($username) {
                    $this->setUsername(Username::create(['username' => $username], $this->mainSource));
                }
            }
        }

        if (!empty($info['name'])) {
            $this->addName(Name::create(['full_name' => $info['name']], $this->mainSource));
        }
        if (!empty($info['location'])) {
            $info['location'] = array_filter($info['location']);
            foreach ($info['location'] as $location) {
                $this->addLocation(Address::create(['full_address' =>$location], $this->mainSource));
            }
        }
        if (!empty($info['school']) && is_array($info['school'])) {
            foreach ($info['school'] as $education) {
                $eduArray = [
                    "name"  =>  $education['school']??"",
                    'degree' =>  $education['degree']??"",
                    "start" =>  $education['start_date']??"",
                    "end"   =>  $education['end_date']??"",
                    "image" =>  $education['image']??"",
                ];
                $this->addEducation(School::create($eduArray, $this->mainSource));
            }
        }

        if (!empty($info['work']) && is_array($info['work'])) {
            foreach ($info['work'] as $work) {
                $workArray = [
                    "company"   =>  $work['company']??"",
                    "image"     =>  $work['image']??"",
                    "title"     =>  $work['position']??"",
                    "start"     =>  $work['start_date']??"",
                    "end"       =>  $work['end_date']??"",
                ];
                $this->addExperience(Work::create($workArray, $this->mainSource));
            }
        }

        if (!empty($info['age'])) {
            $this->addAge(Age::create(['age' => $info['age']], $this->mainSource));
        }

        if (!empty($info['emails'])) {
            foreach ($info['emails'] as $email) {
                $this->addEmail(Email::create(['email' => $email], $this->mainSource));
            }
        }

        if (!empty($info['phones'])) {
            foreach ($info['phones'] as $phone) {
                $this->addPhone(Phone::create(['phone' => $phone], $this->mainSource));
            }
        }

        if (!$is_sub_link && !empty($info['links'])) {
            $relationsFlags = loadData('relationsFlags');
            foreach ($info['links'] as $link) {
                $this->addLink(['url'=>$link, 'reason'=>$relationsFlags['insite']['value']], $is_sub_link);
            }
        }

        if (!empty($info['profile_id'])) {
            $this->social_profile_id = $info['profile_id'];
        }

        // $this->addExperience($info['work']??[]);
        // $this->addExperience($info['positions']??[]);
    }
}
