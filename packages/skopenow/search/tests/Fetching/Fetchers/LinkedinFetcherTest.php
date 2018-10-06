<?php

namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\LinkedinFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\Work;
use App\DataTypes\School;
use App\DataTypes\Email;
use App\DataTypes\Phone;
use App\DataTypes\Age;
use App\DataTypes\Username;

class LinkedinFetcherTest extends \TestCase
{

    public $robDouglasProfileInfo = [];

    public function testFetching()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", file_get_contents(__DIR__ . '/../../data/Linkedin-Search-RobDouglasSkopenow.html'));

        setUrlMock("https://www.linkedin.com/in/robdouglas?__sid=automation_sessions_linkedin", file_get_contents(__DIR__ . '/../../data/Linkedin-Profile-RobDouglas.html'));

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $result = new SearchResult('https://www.linkedin.com/in/robdouglas');
        $result->setIsProfile(true);
        $result->username = 'robdouglas';
        $result->resultsCount = 1;
        $result->screenshotUrl = 'https://www.linkedin.com/in/robdouglas';
        $result->orderInList = 0;
        $result->image = 'https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg';

        $fetcher->loadProfileInfo($result);
        $expectedList->addResult($result);
        $this->assertEquals($expectedList, $actualList);
    }

    public function testMakeQueryWithSchool()
    {
        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->school = "Oyster Bay High School";
        $fetcher = new LinkedinFetcher($criteria);
        $Actualequest = $fetcher->prepareRequest();

        $expectedRequest = ['url' => 'https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+High+School&locationType=I&countryCode=us'];

        $this->assertEquals($expectedRequest, $Actualequest);
    }

    public function testMakeQueryWithZipCode()
    {
        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->zipcode = 125847;
        $fetcher = new LinkedinFetcher($criteria);
        $Actualequest = $fetcher->prepareRequest();

        $expectedRequest = ['url' => 'https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22&postalCode%5B0%5D=125847&locationType=I&countryCode=us'];

        $this->assertEquals($expectedRequest, $Actualequest);
    }

    public function testMakeRequestWithMultipleSchoolsAndCompanies()
    {
        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->school = "Oyster Bay High School|Vanderbilt University";
        $criteria->company = "skopenow|Inertia";
        $fetcher = new LinkedinFetcher($criteria);
        $Actualequest = $fetcher->prepareRequest();

        $expectedRequest = ['url' => 'https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+skopenow+AND+Inertia+AND+Oyster+Bay+High+School+AND+Vanderbilt+University&locationType=I&countryCode=us'];

        $this->assertEquals($expectedRequest, $Actualequest);
    }

    public function testExceedLimit()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", file_get_contents(__DIR__ . '/../../data/Linkedin-Search-RobDouglasSkopenow.html'));

        setUrlMock("https://www.linkedin.com/in/robdouglas?__sid=automation_sessions_linkedin", file_get_contents(__DIR__ . '/../../data/Linkedin-Profile-RobDouglas.html'));

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->maxResults = 0;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyIdentifier()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", file_get_contents(__DIR__ . '/../../data/Linkedin-Search-RobDouglasSkopenow-EmptyID.html'));

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testErrorResponse()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", "<html>invalid_postal_code</html>");

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testInvalidResponse()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", "", "HTTP/1.1 404 Not Found");

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us", "<html></html>");

        $criteria = new Criteria();
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";
        $criteria->company = "Skopenow";
        $criteria->city = "Oyster Bay";
        $criteria->state = "New York";

        $fetcher = new LinkedinFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('linkedin');
        $expectedList->setUrl('https://www.linkedin.com/search/results/index/?keywords=firstname%3A%22Rob%22+lastname%3A%22Douglas%22+AND+Oyster+Bay+New+York+AND+Skopenow&locationType=I&countryCode=us');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
