<?php
/**
 * Flickr search
 * @author Khaled & Mostafa
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
use App\DataTypes\Address;
use App\DataTypes\Work;

class AngelFetcher extends AbstractFetcher
{
    /**
     * @const string The selected account for search
     */
    const MAIN_SOURCE_NAME = "angel";

    public $availableProfileInfo = ["name", "location", "image", "experiences", "educations"];

    protected function prepareRequest()
    {
        $query = ["q"=>$this->criteria->full_name];

        $url = "https://angel.co/search?".http_build_query($query);
        $request = ['url'=>$url];
        return $request;
    }

    protected function makeRequest()
    {
        try {
            $entry = loadService('HttpRequestsService');
            $response = $entry->fetch($this->request['url'], 'GET');

            return ['body' => $response->getBody()->getContents()];
        } catch (\Exception $ex) {
            return ['body' => ''];
        }
    }

    protected function processResponse($response) : SearchListInterface
    {
        $list = new SearchList(self::MAIN_SOURCE_NAME);
        $list->setUrl($this->request['url']);
        $searchdata = [];

        $pattern = "/result\-pic\'[^*]*?href\=\"(.*?)\"[^*]*?src=\"(.*?)\"[^*]*?title[^*]*?href[^*]*?>(.*?)<[^*]*?type\'\>([^*]*?)\</";

        if (preg_match_all($pattern, $response['body'], $matches)) {
            $userData = [];
            foreach ($matches as $key => $value) {
                foreach ($value as $k => $v) {
                    if (stripos($matches[$key+1][$k], "captcha")) {
                        continue;
                    }

                    $userData[$k]['url']    = $matches[$key+1][$k];
                    ## check if url in search page is a redirect url get the real profile url from it.
                    $re = '/url=([^;]+)/';
                    if (preg_match($re, $userData[$k]['url'], $match)) {
                        $userData[$k]['url'] = urldecode($match[1]);
                    }

                    $userData[$k]['image']  = $matches[$key+2][$k];
                    $userData[$k]['name']   = trim($matches[$key+3][$k]);
                    $dataTrimmed = trim($matches[$key+4][$k]);
                    if (!empty($dataTrimmed)) {
                        $work_location = explode('&middot;', $dataTrimmed);
                        $userData[$k]['extra']['work']     = (isset($work_location[1]) )? $work_location[0] : "";
                        $userData[$k]['extra']['location'] = (isset($work_location[1]) )? $work_location[1] : $work_location[0];
                    } else {
                        $userData[$k]['extra'] = array();
                    }
                }
                break;
            }

            $resultsCount = 0;

            foreach ($userData as $key => $res) {
                if ($resultsCount >= $this->maxResults) {
                    break;
                }
                $url = $res['url'];

                $result = new SearchResult($url, true);

                if (isset($res['image']) && !empty($res['image'])) {
                    $result->image = $res['image'];
                }

                if (isset($res['name']) && !empty($res['name'])) {
                    $result->addName(Name::create(["full_name"=>$res['name']], $result->mainSource));
                }
                
                if ($this->onResultFound($result)) {
                    $list->addResult($result);
                }

                if ($this->onDataPointFound(new DataPoint())) {
                }
                
                $resultsCount++;
            }
        }
        
        if (!$searchdata) {
            return $list;
        }
    }
}
