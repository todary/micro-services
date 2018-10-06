<?php
/**
 * Linkedin Fetcher
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 * @package Search
 * @subpackage Fetching
 */

namespace Skopenow\Search\Fetching\Fetchers;

use Skopenow\Search\Fetching\AbstractFetcher;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchListInterface;
use Skopenow\Search\Models\DataPoint;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;

class LinkedinFetcher extends AbstractFetcher
{

    /**
     * [$availableProfileInfo The available data should be extracted]
     * @var [array]
     */
    public $availableProfileInfo = ["name", "location", "image", "experiences","educations", "insite_links"];

    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "linkedin";

    public function prepareRequest()
    {
        $query['keywords'] = [];
        if (!empty($this->criteria->first_name) && !empty($this->criteria->last_name)) {
            if ($this->criteria->search_type == 'with_name_flags') {
                $query['keywords']['firstname:"'.$this->criteria->first_name.'" lastname:"'.$this->criteria->last_name.'"'] = "";
            } else {
                $query['keywords'][$this->criteria->first_name . ' ' . $this->criteria->last_name] = "";
            }
        }
        if (!empty($this->criteria->city) && !empty($this->criteria->state)) {
            $query['keywords'][$this->criteria->city.' '.$this->getStateName($this->criteria->state)] = 'and';
        } elseif(!empty($this->criteria->city)) {
            $query['keywords'][$this->getStateName($this->criteria->city)] = 'and';
        } elseif(!empty($this->criteria->state)) {
            $query['keywords'][$this->getStateName($this->criteria->state)] = 'and';
        }

        if (!empty($this->criteria->company)) {
            if (stripos($this->criteria->company, '|')) {
                $this->criteria->company = str_ireplace('|', $this->checkOperator('|'), $this->criteria->company);
            }
            $query['keywords'][$this->criteria->company] = 'and';
        }

        if (!empty($this->criteria->school)) {
            if (stripos($this->criteria->school, '|')) {
                $this->criteria->school = str_ireplace('|', $this->checkOperator('|'), $this->criteria->school);
            }
            $query['keywords'][$this->criteria->school] = 'and';
        }

        if (!empty($this->criteria->zipcode)) {
            $query['postalCode'][] = $this->criteria->zipcode;
        }

        $query['locationType'] = 'I';

        if($this->criteria->country)
            $query['countryCode'] = strtolower($this->criteria->country);


        $query['keywords'] = $this->buildQuery($query['keywords']);
        $query = 'https://www.linkedin.com/search/results/index/?'.http_build_query($query);
        $request = ["url" => $query];
        return $request;
    }

    public function makeRequest()
    {
        try {
            $httpRequest = loadService('HttpRequestsService');
            $response = $httpRequest->fetch($this->request['url'],'GET');
            $response->getBody()->rewind();
            return ['body' => $response->getBody()->getContents()];
        } catch (\Exception $e) {
            return ['body' => ''];
        }
    }

    public function processResponse($response): SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $list->setUrl($this->request['url']);

        if (!$this->checkForErrors($response['body'])) {
            $this->fetchBody($response['body'], $list);
        }

        return $list;
    }

    public function fetchBody($body, SearchListInterface $list)
    {
        $matched = preg_match('#<code\s+id\s*=\s*["\']voltron_srp_main-content["\']\s+style\s*=\s*["\']display:none;["\']\s*>\s*<!--\s*(.*?)\s*-->#is', $body, $match);

        if (empty($match)) {
            $re = '/<code[^>]*?>\s*(.*?com\.linkedin\.voyager\.search\.VerticalGuide.*?)\s*<\/code>/';
            preg_match($re, $body, $matches);

            $content = '';
            if(isset($matches[1]))
                $content = $matches[1];
            preg_match('/,&quot;total&quot;:([0-9]+),&/i', $content, $countProfiles);
            preg_match_all('/(\{&quot;firstName.*?\}),/', $content, $matchedProfiles);
            $countProfiles = (int) $countProfiles;

            $count = 0 ;
            foreach ($matchedProfiles[1] as $key => $profile) {

                if($count >= $this->maxResults) break;

                $jsonDescoded = @json_decode(html_entity_decode($profile),true);
                if (!$jsonDescoded) {
                   $jsonDescoded = json_decode(html_entity_decode($profile.'}'),true);
                }

                $profile = $jsonDescoded;
                if (empty($profile['publicIdentifier']) || $profile['publicIdentifier'] == 'UNKNOWN') {
                    continue;
                }
                $profile['orderInList'] = $count;
                $result = $this->getResult($profile);
                $result->resultsCount = count($matchedProfiles[1]);
                if ($this->onResultFound($result)) {
                    $list->addResult($result);
                    $count++;
                }
            }
        }
        return $list;
    }

    public function getResult(array $profile): SearchResult
    {
        $link = 'https://www.linkedin.com/in/'.$profile['publicIdentifier'];
        $result = new SearchResult($link, true);
        $result->username = $profile['publicIdentifier'];
        $result->addName(Name::create(['full_name' => $profile['firstName'].' '.$profile['lastName']], $result->mainSource));
        $result->orderInList = $profile['orderInList'];
        return $result;
    }

    public function checkForErrors(string $body)
    {
        $status = false;

        ## check for invalid postal code.
        $errorPostalCode = stripos($body, 'invalid_postal_code');
        if($errorPostalCode){
            $status = true;
        }

        return $status;
    }

    public function buildQuery(array $keyWords)
    {
        $query = "";
        foreach ($keyWords as $key => $value) {
            $query .= $this->checkOperator($value).$key;
        }
        return $query;
    }

    public function checkOperator(string $type)
    {

        switch ($type) {
            case 'and':
                $operator = " AND ";
                break;
            case '|':
                $operator = " AND ";
                break;
            // case 'or':
            //  $operator = " OR ";
            //  break;
            // case ' ':
            //  $operator = " ";
            //  break;

            default:
                $operator = "";
                break;
        }
        return $operator;
    }

    public function getStateName(string $stateCode): string
    {
        $locationService = loadService("location");
        $stateName = $locationService->getStateName(new \ArrayIterator([$stateCode]));

        return $stateName[$stateCode];
    }

  //   public function inUS()
  //   {
  //    $locationService = loadService("location");
        // $states = [$this->criteria->state];

        // $output = $locationService->isLocatedInUS($states)[$this->criteria->state];

        // return $output;
  //   }


}
