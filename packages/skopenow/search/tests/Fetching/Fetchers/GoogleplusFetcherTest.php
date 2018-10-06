<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\GoogleplusFetcher;
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

class GoogleplusFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=102538261020709081239", file_get_contents(__DIR__ . '/../../data/Googleplus-About-102538261020709081239.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=110752933937045501933", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110752933937045501933.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=118384634136034439609", file_get_contents(__DIR__ . '/../../data/Googleplus-About-118384634136034439609.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=114621836901208891303", file_get_contents(__DIR__ . '/../../data/Googleplus-About-114621836901208891303.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=110766775168069116539", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110766775168069116539.html'));

        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
dd($actualList);
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $result = new SearchResult('https://plus.google.com/102538261020709081239');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '102538261020709081239';
        $result->orderInList = 0;
        $result->image = 'https://lh6.googleusercontent.com/-kxFhN-qTuGY/AAAAAAAAAAI/AAAAAAAAAIw/aN2nEA2Pb4Q/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "IdentityTheft.info",
            "image" => "",
            "title" => "Conference Speaker & Pundit",
            "start" => 2007,
            "end" => 2016,
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addEmail(Email::create(['email' => "Rob@RobDouglas.com"], $result->mainSource));
        $result->addLink(['url'=>"http://www.IdentityTheft.info",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/robdouglas3",'reason'=>2]);
        $result->addLink(['url'=>"http://www.linkedin.com/in/identitytheftconsultant",'reason'=>2]);
        $result->addLink(['url'=>"http://www.steamboattoday.com/",'reason'=>2]);
        $result->addLink(['url'=>"http://www.consumeraffairs.com/",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/102538261020709081239",'reason'=>2]);
        $expectedList->addResult($result);

        $result = new SearchResult('https://plus.google.com/110752933937045501933');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110752933937045501933';
        $result->orderInList = 1;
        $result->image = 'https://lh5.googleusercontent.com/-hyKfEVqcGao/AAAAAAAAAAI/AAAAAAAAGew/sDkBfJoRDS8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Infinite Radius Group",
            "image" => "",
            "title" => "Founder",
            "start" => 2009,
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://instagram.com/rob_douglas",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/MrRobDouglas",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110752933937045501933",'reason'=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://plus.google.com/118384634136034439609');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '118384634136034439609';
        $result->orderInList = 2;
        $result->image = 'https://lh6.googleusercontent.com/-qnQsdlKQPtk/AAAAAAAAAAI/AAAAAAAAAHc/7p3lFeYlVZ8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Australia'], $result->mainSource));
        $workArray = [
            "company" => "Pellet Fires Tasmania",
            "image" => "",
            "title" => "Managing Director",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/118384634136034439609",'reason'=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://plus.google.com/114621836901208891303');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '114621836901208891303';
        $result->orderInList = 3;
        $result->image = 'https://lh6.googleusercontent.com/-gGXJjmaT6lY/AAAAAAAAAAI/AAAAAAAAAIE/9kwrxI15eYM/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Christchurch, New Zealand'], $result->mainSource));
        $workArray = [
            "company" => "SQL Services",
            "image" => "",
            "title" => "Database Consultant",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/114621836901208891303",'reason'=>2]);
        $expectedList->addResult($result);
    }

        /*$result = new SearchResult('https://plus.google.com/110766775168069116539');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110766775168069116539';
        $result->orderInList = 4;
        $result->image = 'https://lh6.googleusercontent.com/-3Eh9jf--5Uc/AAAAAAAAAAI/AAAAAAAAALU/2yu1i8OSoks/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Verizon Enterprise Solutions",
            "image" => "",
            "title" => "Federal Account Manager, Army",
            "start" => "",
            "end" => 2012,
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Verizon Wireless",
            "image" => "",
            "title" => "Federal Account Manager, Department of Defense",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Manager, Business Sales",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Business Sales Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Major Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Henderson Combined Group, Inc.",
            "image" => "",
            "title" => "Sales and Operations Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Newell Rubbermaid",
            "image" => "",
            "title" => "Field Marketing Representative",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://www.linkedin.com/pub/rob-douglas/7/629/404",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110766775168069116539",'reason'=>2]);
        $expectedList->addResult($result);

        $this->assertEquals($expectedList, $actualList);
    }
/*
    public function testExceedingLimit()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas.html'));
        
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=102538261020709081239", file_get_contents(__DIR__ . '/../../data/Googleplus-About-102538261020709081239.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->maxResults = 1;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $result = new SearchResult('https://plus.google.com/102538261020709081239');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '102538261020709081239';
        $result->orderInList = 0;
        $result->image = 'https://lh6.googleusercontent.com/-kxFhN-qTuGY/AAAAAAAAAAI/AAAAAAAAAIw/aN2nEA2Pb4Q/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "IdentityTheft.info",
            "image" => "",
            "title" => "Conference Speaker & Pundit",
            "start" => 2007,
            "end" => 2016,
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addEmail(Email::create(['email' => "Rob@RobDouglas.com"], $result->mainSource));
        $result->addLink(['url'=>"http://www.IdentityTheft.info",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/robdouglas3",'reason'=>2]);
        $result->addLink(['url'=>"http://www.linkedin.com/in/identitytheftconsultant",'reason'=>2]);
        $result->addLink(['url'=>"http://www.steamboattoday.com/",'reason'=>2]);
        $result->addLink(['url'=>"http://www.consumeraffairs.com/",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/102538261020709081239",'reason'=>2]);
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testBadResponse()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", "HTTP/1.1 404 Not Found");
        
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
                
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testEmptyResponse()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", "");
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testMissingIndex0()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas-MissingIndex0.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $this->assertEquals($expectedList, $actualList);
    }

    public function testMissingIndex3()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas-MissingIndex3.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";

        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $this->assertEquals($expectedList, $actualList);
    }

/*
    public function testMissingItem()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas-MissingItem.html'));
        
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=102538261020709081239", file_get_contents(__DIR__ . '/../../data/Googleplus-About-102538261020709081239.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=110752933937045501933", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110752933937045501933.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=118384634136034439609", file_get_contents(__DIR__ . '/../../data/Googleplus-About-118384634136034439609.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=114621836901208891303", file_get_contents(__DIR__ . '/../../data/Googleplus-About-114621836901208891303.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=110766775168069116539", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110766775168069116539.html'));

        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->maxResults = 4;
        $fetcher->execute();
        $actualList = $fetcher->getOutput();
        
        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $result = new SearchResult('https://plus.google.com/110752933937045501933');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110752933937045501933';
        $result->orderInList = 0;
        $result->image = 'https://lh5.googleusercontent.com/-hyKfEVqcGao/AAAAAAAAAAI/AAAAAAAAGew/sDkBfJoRDS8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Infinite Radius Group",
            "image" => "",
            "title" => "Founder",
            "start" => 2009,
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://instagram.com/rob_douglas",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/MrRobDouglas",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110752933937045501933",'reason'=>2]);
        $expectedList->addResult($result);
                
        $result = new SearchResult('https://plus.google.com/118384634136034439609');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '118384634136034439609';
        $result->orderInList = 1;
        $result->image = 'https://lh6.googleusercontent.com/-qnQsdlKQPtk/AAAAAAAAAAI/AAAAAAAAAHc/7p3lFeYlVZ8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Australia'], $result->mainSource));
        $workArray = [
            "company" => "Pellet Fires Tasmania",
            "image" => "",
            "title" => "Managing Director",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/118384634136034439609",'reason'=>2]);
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://plus.google.com/114621836901208891303');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '114621836901208891303';
        $result->orderInList = 2;
        $result->image = 'https://lh6.googleusercontent.com/-gGXJjmaT6lY/AAAAAAAAAAI/AAAAAAAAAIE/9kwrxI15eYM/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Christchurch, New Zealand'], $result->mainSource));
        $workArray = [
            "company" => "SQL Services",
            "image" => "",
            "title" => "Database Consultant",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/114621836901208891303",'reason'=>2]);
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://plus.google.com/110766775168069116539');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110766775168069116539';
        $result->orderInList = 3;
        $result->image = 'https://lh6.googleusercontent.com/-3Eh9jf--5Uc/AAAAAAAAAAI/AAAAAAAAALU/2yu1i8OSoks/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Verizon Enterprise Solutions",
            "image" => "",
            "title" => "Federal Account Manager, Army",
            "start" => "",
            "end" => 2012,
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Verizon Wireless",
            "image" => "",
            "title" => "Federal Account Manager, Department of Defense",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Manager, Business Sales",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Business Sales Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Major Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Henderson Combined Group, Inc.",
            "image" => "",
            "title" => "Sales and Operations Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Newell Rubbermaid",
            "image" => "",
            "title" => "Field Marketing Representative",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://www.linkedin.com/pub/rob-douglas/7/629/404",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110766775168069116539",'reason'=>2]);
        //dd($result);
        $expectedList->addResult($result);
        
        $this->assertEquals($expectedList, $actualList);
    }

  
    public function testFetchingNonNumeric()
    {
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?hash=79bd5a2838e139800b058e0e0e02e5cb", file_get_contents(__DIR__ . '/../../data/Googleplus-Search-RobDouglas-Nonnum-Username.html'));

        setUrlMock("https://plus.google.com/app/basic/+RobDouglas?hl=en", file_get_contents(__DIR__ . '/../../data/Googleplus-basic-RobDouglas.html'));
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=app/basic/+RobDouglas", file_get_contents(__DIR__ . '/../../data/Googleplus-basic-Username-RobDouglas.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=110752933937045501933", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110752933937045501933.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=118384634136034439609", file_get_contents(__DIR__ . '/../../data/Googleplus-About-118384634136034439609.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=114621836901208891303", file_get_contents(__DIR__ . '/../../data/Googleplus-About-114621836901208891303.html'));

        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=102538261020709081239", file_get_contents(__DIR__ . '/../../data/Googleplus-About-110766775168069116539.html'));
        
        $criteria = new Criteria;
        $criteria->full_name = "rob douglas";
        
        $fetcher = new GoogleplusFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('googleplus');
        $expectedList->setUrl('https://plus.google.com/s/rob%20douglas/people');
        
        $result = new SearchResult('https://plus.google.com/app/basic/+RobDouglas?hl=en');
        $result->setIsProfile(true);
        $result->username = '+RobDouglas';
        $result->orderInList = 0;
        $result->image = 'https://lh6.googleusercontent.com/-kxFhN-qTuGY/AAAAAAAAAAI/AAAAAAAAAIw/aN2nEA2Pb4Q/photo.jpg';
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://plus.google.com/110752933937045501933');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110752933937045501933';
        $result->orderInList = 1;
        $result->image = 'https://lh5.googleusercontent.com/-hyKfEVqcGao/AAAAAAAAAAI/AAAAAAAAGew/sDkBfJoRDS8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Infinite Radius Group",
            "image" => "",
            "title" => "Founder",
            "start" => 2009,
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://instagram.com/rob_douglas",'reason'=>2]);
        $result->addLink(['url'=>"http://twitter.com/MrRobDouglas",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110752933937045501933",'reason'=>2]);
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/118384634136034439609');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '118384634136034439609';
        $result->orderInList = 2;
        $result->image = 'https://lh6.googleusercontent.com/-qnQsdlKQPtk/AAAAAAAAAAI/AAAAAAAAAHc/7p3lFeYlVZ8/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Australia'], $result->mainSource));
        $workArray = [
            "company" => "Pellet Fires Tasmania",
            "image" => "",
            "title" => "Managing Director",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/118384634136034439609",'reason'=>2]);
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/114621836901208891303');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '114621836901208891303';
        $result->orderInList = 3;
        $result->image = 'https://lh6.googleusercontent.com/-gGXJjmaT6lY/AAAAAAAAAAI/AAAAAAAAAIE/9kwrxI15eYM/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $result->addLocation(Address::create(['full_address' => 'Christchurch, New Zealand'], $result->mainSource));
        $workArray = [
            "company" => "SQL Services",
            "image" => "",
            "title" => "Database Consultant",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"https://picasaweb.google.com/114621836901208891303",'reason'=>2]);
        $expectedList->addResult($result);    
        
        
        $result = new SearchResult('https://plus.google.com/110766775168069116539');
        $result->setIsProfile(true);
        $result->source = 'googleplus';
        $result->mainSource = 'googleplus';
        $result->username = '110766775168069116539';
        $result->orderInList = 4;
        $result->image = 'https://lh6.googleusercontent.com/-3Eh9jf--5Uc/AAAAAAAAAAI/AAAAAAAAALU/2yu1i8OSoks/photo.jpg';
        $result->addName(Name::create(['full_name' => 'Rob Douglas'], $result->mainSource));
        $workArray = [
            "company" => "Verizon Enterprise Solutions",
            "image" => "",
            "title" => "Federal Account Manager, Army",
            "start" => "",
            "end" => 2012,
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Verizon Wireless",
            "image" => "",
            "title" => "Federal Account Manager, Department of Defense",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Manager, Business Sales",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Business Sales Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Major Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Senior Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "T-Mobile",
            "image" => "",
            "title" => "Account Executive",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Henderson Combined Group, Inc.",
            "image" => "",
            "title" => "Sales and Operations Manager",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $workArray = [
            "company" => "Newell Rubbermaid",
            "image" => "",
            "title" => "Field Marketing Representative",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($workArray, $result->mainSource));
        $result->addLink(['url'=>"http://www.linkedin.com/pub/rob-douglas/7/629/404",'reason'=>2]);
        $result->addLink(['url'=>"https://picasaweb.google.com/110766775168069116539",'reason'=>2]);
        //dd($result);
        $expectedList->addResult($result);   
        
        $this->assertEquals($expectedList, $actualList);
    }
    */
}
