<?php
namespace Skopenow\SearchTest\Managing\Managers;

use Skopenow\Search\Fetching\Fetchers\GoogleFetcher;
use Skopenow\Search\Managing\Managers\GoogleManager;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;

class GoogleManagerTest extends \TestCase
{
    public function testExecution()
    {
        setUrlMock("https://www.google.com/search?client=ubuntu&channel=fs&q=tom+cruise&ie=utf-8&oe=utf-8", file_get_contents(__DIR__ . '/../../data/Google-Search-TomCruise.html'));

        $criteria = new Criteria;
        $criteria->first_name = "Rob";
        $criteria->last_name = "Douglas";

        $fetcher = new GoogleFetcher($criteria);

        $manager = new GoogleManager($fetcher);
        $actualList = $manager->execute();

        $expectedList = new SearchList('google');
        $expectedList->setUrl("https://www.google.com/search?client=ubuntu&channel=fs&q=tom+cruise&ie=utf-8&oe=utf-8");

        $result = new SearchResult('https://de.wikipedia.org/wiki/Tom_Cruise');
        
        $result->title = "Tom Cruise – Wikipedia";
        $result->description = "Tom Cruise (* 3. Juli 1962 in Syracuse, New York; eigentlich Thomas Cruise 
Mapother IV) ist ein US-amerikanischer Schauspieler und Filmproduzent.‎Barry Seal - ‎Edge of Tomorrow - ‎Die Mumie - ‎Mimi Rogers";
        $result->orderInList = 0;
        
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('https://en.wikipedia.org/wiki/Tom_Cruise');
        
        $result->title = "";
        $result->description = "Thomas Cruise Mapother IV (born July 3, 1962), known professionally as Tom \nCruise, is an American actor and producer. He has been nominated for three ...";
        $result->orderInList = 1;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.imdb.com/name/nm0000129/');
        
        $result->title = "";
        $result->description = "Thomas Cruise Mapother IV (born July 3, 1962), known professionally as Tom 
Cruise, is an American actor and producer. He has been nominated for three ...";
        $result->orderInList = 2;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.gala.de/stars/news/tom-cruise--mitschuld-am-tod-zweier-piloten--21446006.html');
        
        $result->title = "Tom Cruise: Mitschuld am Tod zweier Piloten? | GALA.de";
        $result->description = "vor 3 Tagen ... Tom Cruise hat mit \"Barry Seal: Only in America\" erneut einen Kinohit gelandet. 
Jetzt muss er sich jedoch mit schweren Anschuldigungen ...";
        $result->orderInList = 3;
        $expectedList->addResult($result);
        
        
        $result = new SearchResult('http://www.gala.de/stars/starportraets/tom-cruise-20511762.html');
        
        $result->title = "Tom Cruise - Steckbrief, News, Bilder | GALA.de";
        $result->description = "Tom Cruise, Scientology-Vorbeter vom Dienst, ist privat nicht unumstritten. 
Beruflich kann er dafür auf eine beeindruckende Bilanz blicken.";
        $result->orderInList = 4;
        $expectedList->addResult($result);

        $this->assertEquals(count($expectedList->getResults()), count($actualList->getResults()));
    }
    
    public function testNoResponse()
    {
        setUrlMock("https://www.google.com/search?client=ubuntu&channel=fs&q=tom+cruise&ie=utf-8&oe=utf-8", file_get_contents(__DIR__ . '/../../data/Google-Search-TomCruise.html'), "HTTP/1.1 404 Not Found");
        
        $criteria = new Criteria;
        $criteria->first_name = "tom";
        $criteria->last_name = "cruise";
        
        $fetcher = new GoogleFetcher($criteria);

        $manager = new GoogleManager($fetcher);
        $actualList = $manager->execute();
        
        
        
        
        $expectedList = new SearchList('google');
        
        $this->assertEquals($expectedList, $actualList);
    }
}
