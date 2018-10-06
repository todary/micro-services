<?php
namespace Skopenow\PeopleData\Sources;

abstract class AbstractSource implements SourceInterface
{
    
    /**
     * @var Boolean|TRUE specify if using TLOxp or not .
     */
    public $active = true;
    /**
     * @var Boolean|FALSE specify if There account or not .
     */
    protected $accounts_status = false;
    /**
     * @var Object|Account that carry the api account details such as password , username , ... .
     */
    public $account ;
    /**
     * @var Array the json that returned from the api
     */
    protected $search_json_result;
    /**
     * @var flag that detects if the search is modified
     */
    protected $search_modified = false ;
    /**
     * @var the result that returned from api as array
     */
    protected $search_result = [];
    /**
     * @var Array that carry client that represents the soap client, curl client, ...
     */
    protected $client;

    protected $inputCriteria;
    /**
     * @var presents the final result that has been set
     */
    protected $results = [];
    /**
     * @var Integer Store the code if the api returns error
     */
    protected $search_error_code;

    protected $result_rank = 1;

    public function search()
    {
        $search_criteria = $this->prepareCriteria();

        if (!$this->doSearch($search_criteria)) {
            return false;
        }

        $this->results = $this->processResponse();

        return true;
    }

    public function getResults()
    {
        return $this->results;
    }

    abstract protected function doSearch($search_criteria);

    abstract protected function processResponse(): array;

    abstract protected function prepareCriteria();

    protected function getCached(array $inputs, string $api)
    {
        $cache_id = $api."-".md5(json_encode($inputs));
        $cache_value = \Cache::get($cache_id);
        if (!$cache_value) {
            return false;
        }

        if (env('APP_ENV') == "testing") {
            throw new \Exception("Warning: Getting api request: $cache_id from cache in testing environment!\n");
        }

        return $cache_value;
    }

    protected function cacheResult(array $input, $output, string $api)
    {
        $cache_id =  $api."-".md5(json_encode($input));
        $cache_value = $output;
        \Cache::put($cache_id, $cache_value, 2592000);
    }
}
