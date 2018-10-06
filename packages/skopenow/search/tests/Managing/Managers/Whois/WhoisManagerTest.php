<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\Whois\WhoisFetcher;
use Skopenow\Search\Managing\Managers\Whois\WhoisManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class WhoisManagerTest extends \TestCase
{
    public function testExecution()
    {
        config(['state.report_id'=>61337]);
        
        $criteria = new Criteria;
        $criteria->domain = "skopenow.com";

        $fetcher = new WhoisFetcher($criteria);

        $manager = new WhoisManager($fetcher);
        $actualList = $manager->execute();

        dd($actualList);
        
        $this->assertEquals($expectedList, $actualList);
    }

   
}
