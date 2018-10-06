<?php
namespace Skopenow\SearchTest\Fetching\Fetchers;

use Skopenow\Search\Fetching\Fetchers\AngelFetcher;
use Skopenow\Search\Models\Criteria;
use Skopenow\Search\Models\SearchList;
use Skopenow\Search\Models\SearchResult;
use App\DataTypes\Name;
use App\DataTypes\Address;
use App\DataTypes\School;
use App\DataTypes\Work;

class AngelFetcherTest extends \TestCase
{
    public function testFetching()
    {
        setUrlMock("https://angel.co/search?q=Rob+Douglas", file_get_contents(__DIR__ . '/../../data/Angel-Search-RobDouglas.html'));
        setUrlMock("https://angel.co/rob-douglas-1", file_get_contents(__DIR__ . '/../../data/Angel-Profile-RobDouglas.html'));
        setUrlMock("https://angel.co/douglas-roberts-2", file_get_contents(__DIR__ . '/../../data/Angel-Profile-DouglasRoberts.html'));
        setUrlMock("https://angel.co/rob-douglas-2", file_get_contents(__DIR__ . '/../../data/Angel-Profile-RobDouglas2.html'));
        setUrlMock("https://angel.co/robert-douglas", file_get_contents(__DIR__ . '/../../data/Angel-Profile-RobertDouglas.html'));
        setUrlMock("https://angel.co/douglas-roberts", file_get_contents(__DIR__ . '/../../data/Angel-Profile-DouglasRoberts2.html'));

        $criteria = new Criteria;
        $criteria->full_name = "Rob Douglas";
        
        $fetcher = new AngelFetcher($criteria);
        $fetcher->execute();
        $actualList = $fetcher->getOutput();

        $expectedList = new SearchList('angel');
        $expectedList->setUrl('https://angel.co/search?q=Rob+Douglas');

        $result = new SearchResult('https://angel.co/rob-douglas-1');
        $result->orderInList = 0;
        $result->image = "https://d1qb2nb5cznatu.cloudfront.net/users/609706-large?1468531345";
        $result->addName(Name::create(["full_name"=>"Rob Douglas"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"New York City"], $result->mainSource));
        $work = [
            "company" => "Peaked",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/402448-674e640d4d3e66e28e2d5a2517c1faa2-thumb_jpg.jpg?buster=1400644066",
            "title" => "",
            "start" => 2014,
            "end" => ""
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Inertia Lab",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/520786-d3e4fd5a1581cbc9b4eac46176204005-thumb_jpg.jpg?buster=1414460153",
            "title" => "",
            "start" => 2011,
            "end" => 2014
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Microsoft",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/33135-02343af615138d30a3b66634daeaded6-thumb_jpg.jpg?buster=1430940945",
            "title" => "",
            "start" => 2009,
            "end" => 2010,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Griffin Technology",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2008,
            "end" => 2008,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Sony Pictures Entertainment",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/37785-aff5f2d779d2207b24639797668e2d8b-thumb_jpg.jpg?buster=1326846975",
            "title" => "",
            "start" => 2007,
            "end" => 2007,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Peaked",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/402448-674e640d4d3e66e28e2d5a2517c1faa2-thumb_jpg.jpg?buster=1400644066",
            "title" => "",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Skopenow",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/606555-fd612119af0fe7aecb54e60f1e059fcb-thumb_jpg.jpg?buster=1440556263",
            "title" => "",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Skopenow",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/606555-fd612119af0fe7aecb54e60f1e059fcb-thumb_jpg.jpg?buster=1440556263",
            "title" => "",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $school = [
            "name" => "Vanderbilt University",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71805-2a754387447da2867b4a667eb1e71572-medium.png?buster=1440132757",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $result->addLink(["url" => "https://www.facebook.com/skopenow","reason"=>2]);
        $result->addLink(["url" => "http://skopenow.com","reason"=>2]);
        $expectedList->addResult($result);
        
        $result = new SearchResult('https://angel.co/douglas-roberts-2');
        $result->orderInList = 0;
        $result->image = "https://d1qb2nb5cznatu.cloudfront.net/users/3975850-large?1474575450";
        $result->addName(Name::create(["full_name"=>"Douglas Roberts"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Chicago"], $result->mainSource));
        $work = [
            "company" => "American Astronomical SocietyNorthwestern U.",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2015,
            "end" => ""
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Microsoft ResearchNorthwestern University",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2013,
            "end" => 2015,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "AdlerNorthwestern University",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2010,
            "end" => 2013,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Northwestern UniversityAdler",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2006,
            "end" => 2008,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "National Center for Supercomputing Applications",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 1996,
            "end" => 2000,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "University of Illinois at Champaign-Urbana",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 1992,
            "end" => 1996,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $school = [
           "name" => "University of Oklahoma",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71837-3ad31ef05329acf93c44f5bf2beecd35-medium.png?buster=1440132765",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
           "name" => "-",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://angel.co/images/shared/nopic_college.png",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
           "name" => "Iau",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://angel.co/images/shared/nopic_college.png",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
           "name" => "Uergs",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://angel.co/images/shared/nopic_college.png",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
           "name" => "Rensselaer Polytechnic Institute",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71563-9f61e2d2097c6801bc11853e0708b5c1-medium.png?buster=1440132709",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
            "name" => "Northwestern University",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71564-11d74b9a86c44fd79b09b0bb679b80d8-medium.png?buster=1440132713",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
            "name" => "Louisiana State University",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/73312-71d90f0968a8ee9e5418b4a2b811f78f-medium.png?buster=1440133002",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $result->addLink(["url" => "https://www.facebook.com/doug.roberts.18007","reason"=>2]);
        $expectedList->addResult($result);

        $result = new SearchResult('https://angel.co/rob-douglas-2');
        $result->orderInList = 0;
        $result->image = "https://angel.co/images/shared/nopic.png";
        $result->addName(Name::create(["full_name"=>"Rob Douglas"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"New York City"], $result->mainSource));
        $school = [
            "name" => "Vanderbilt University",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/71805-2a754387447da2867b4a667eb1e71572-medium.png?buster=1440132757",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $expectedList->addResult($result);

        $result = new SearchResult('https://angel.co/robert-douglas');
        $result->orderInList = 0;
        $result->image = "https://d1qb2nb5cznatu.cloudfront.net/users/1894111-large?1453574767";
        $result->addName(Name::create(["full_name"=>"Robert Douglas"], $result->mainSource));
        $result->addLocation(Address::create(["full_address"=>"Washington DC"], $result->mainSource));
        $work = [
            "company" => "Jellyfish US",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2014,
            "end" => 2015,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Jos A Bank Clothiers",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2012,
            "end" => 2014,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $work = [
            "company" => "Baltimore Research",
            "image" => "https://angel.co/images/shared/nopic_startup.png",
            "title" => "",
            "start" => 2011,
            "end" => 2012,
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $school = [
            "name" => "Boston College",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/73389-9f85baf02a16733b4840afdb8249e49a-medium.png?buster=1440133006",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $school = [
            "name" => "Boston College",
            "degree" => "",
            "start" => "",
            "end" => "",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/new_tags/i/73389-9f85baf02a16733b4840afdb8249e49a-medium.png?buster=1440133006",
        ];
        $result->addEducation(School::create($school, $result->mainSource));
        $expectedList->addResult($result);

        $result = new SearchResult('https://angel.co/douglas-roberts');
        $result->orderInList = 0;
        $result->image = "https://d1qb2nb5cznatu.cloudfront.net/users/126469-large?1463034226";
        $result->addName(Name::create(["full_name"=>"Douglas Roberts"], $result->mainSource));
        $work = [
            "company" => "Branching Minds",
            "image" => "https://d1qb2nb5cznatu.cloudfront.net/startups/i/356923-69a91812af0c35d89fcd35c63fbec1be-thumb_jpg.jpg?buster=1425408238",
            "title" => "",
            "start" => "",
            "end" => "",
        ];
        $result->addExperience(Work::create($work, $result->mainSource));
        $result->addLink(["url"=>"http://www.edusolutionsllc.com", "reason"=>2]);
        $expectedList->addResult($result);




        $this->assertEquals($expectedList, $actualList);

    }
}
