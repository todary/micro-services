<?php

namespace Skopenow\PeopleDataTest;

use Skopenow\PeopleData\SearchInvoker;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Skopenow\PeopleData\Clients\SoapClient;
use Skopenow\PeopleData\Clients\CurlClient;
use Skopenow\PeopleData\ResultMerger;
use Skopenow\PeopleData\ResultNormalizer;
use Skopenow\PeopleData\Workers\SyncWorker;

class SearchInvokerTest extends \TestCase
{
    protected function generateInvoker($criteria)
    {
        setUrlMock("http://api.pipl.com/search/?key=&raw_name=Rob+Douglas&raw_address=Oyster+Bay%2C+NY", file_get_contents(__DIR__.'/Data/PIPL-RobDouglas.json'));
        setUrlMock("http://api.pipl.com/search/?key=&raw_name=Rob+Douglas&email=robert.m.douglas%40vanderbilt.edu&age=27-37&raw_address=92+Sunken+Orchard+Lane%2C+Oyster+Bay%2C+NY%2C+Oyster+Bay%2C+NY", file_get_contents(__DIR__.'/Data/PIPL-RobDouglas.json'));

        \Cache::delete('Tloxp-257888ec5e9eeab8d70e55a7caac8485');
        \Cache::delete('Tloxp-767ae21ddebe8a3ba676266368e2dcca');
        \Cache::delete('Pipl-94efdf5fbe6794401fdeb770877c5699');
        \Cache::delete('Pipl-465b426d62160de5d032ed6110b193ed');
        \Cache::delete('Fetcher_facebook-631d4cda47636a8f15d6fae25d5306ca');
        \Cache::delete('Fetcher_fullcontact-16471d73069b0ab921db131ba6c9da8f');
        \Cache::delete('Pipl-daae89921ef9273cdf8bd7f44ec04ef7');

        $curlSoap = $this->getMockBuilder(SoapClient::class)
            ->setConstructorArgs([__DIR__."/../src/Resources/tloxp.wsdl"])
            ->setMethods(['call'])
            ->getMock();
        $curlSoap->method('call')->willReturn(file_get_contents(__DIR__.'/Data/TLOXP-RobDouglas.xml'));

        $invoker = \Mockery::mock(SearchInvoker::class, [$criteria])->makePartial();
        $invoker->shouldReceive('getApiAccount')->andReturn(new \App\Models\ApiAccount);
        $invoker->shouldReceive('getApiClient')->with('pipl', \Mockery::any())->andReturn(new CurlClient);
        $invoker->shouldReceive('getApiClient')->with('tloxp', \Mockery::any())->andReturn($curlSoap);

        return $invoker;
    }

    /*
    public function testDirect()
    {
        $criteria = [
            'pipltlo' => [
                'try3'=> [
                    'tryname0' => [
                        ["name"=>"agadgdag dfgdfgdf","city"=>"agdfgdfg dfg","state"=>"dfgdfggg","apis"=>["tloxp"]],
                        ["name"=>"Rob Douglas","city"=>"Oyster Bay","state"=>"NY","apis"=>["tloxp"]],
                        ["name"=>"Rob Douglas","state"=>"NY","apis"=>["tloxp"]],
                        ["name"=>"Rob Douglas","apis"=>["tloxp"]],
                        ["name"=>"Rob Douglas","city"=>"Oyster Bay","state"=>"NY","apis"=>["pipl"]],
                        ["name"=>"Rob Douglas","state"=>"NY","apis"=>["pipl"]],
                        ["name"=>"Rob Douglas","apis"=>["pipl"]],
                    ]
                ]
            ]
        ];


        $invoker = new SearchInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);

        $this->assertEquals($this->tloxpResponse(), $results['results']);
    }
    */


    public function testOneAPI()
    {

        $criteria = [
            'people'=>[
                'trial1'=>[
                    'tloxp'=>[
                        ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
        ];

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);

        $this->assertEquals($this->tloxpResponse(), $results['results']);
    }

    public function testTwoAPIsParallel()
    {

        $criteria = [
            'people'=>[
                'trial1'=>[
                    'tloxp,pipl'=>[
                        ['apis'=>['pipl', 'tloxp'], 'strategy'=>'parallel', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
        ];

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);

        $this->assertEquals($this->getRobDouglasMergePiplWithTloxp(), $results['results']);



        $criteria = [
            'people'=>[
                'trial1'=>[
                    'pipl'=>[
                        ['apis'=>['pipl'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
            'people2'=>[
                'trial1'=>[
                    'tloxp'=>[
                        ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
        ];

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);

        $this->assertEquals($this->getRobDouglasMergePiplWithTloxp(), $results['results']);
    }

    public function testTwoAPIsSerial()
    {

        $criteria = [
            'people'=>[
                'trial1'=>[
                    'tloxp>pipl'=>[
                        ['apis'=>['tloxp', 'pipl'], 'strategy'=>'serial', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
                'stateonly'=>[
                    'tloxp>pipl'=>[
                        ['apis'=>['tloxp'], 'strategy'=>'serial', 'name'=>'Rob Douglas', 'city'=>'', 'state'=>'NY'],
                    ],
                ],
            ],
        ];

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(array_merge($this->tloxpResponse()), $results['results']);

        $criteria = [
            'people'=>[
                'trial1'=>[
                    'tloxp'=>[
                        ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
                'trial2'=>[
                    'pipl'=>[
                        ['apis'=>['pipl'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
        ];

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(array_merge($this->tloxpResponse()), $results['results']);
    }

    public function testTwoAPIsSerialFirstEmpty()
    {
        $criteria = [
            'people'=>[
                'trial1'=>[
                    [],
                    'tloxp'=>[
                        ['apis'=>['tloxp'], 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                    ],
                ],
            ],
        ];


        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(array_merge($this->tloxpResponse()), $results['results']);
    }

    /*
    public function testTwoAPIsConcurrentThenOneAPI()
    {
        $criteria = [
            [
                ['api'=>'tloxp', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
            [
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
        ];

        $invoker = new SearchInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(['trial'=>0, 'results'=>[]], $results);
    }

    public function testOneAPIThenTwoAPIsConcurrent()
    {
        $criteria = [
            [
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
            [
                ['api'=>'tloxp', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
        ];

        $invoker = new SearchInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(['trial'=>0, 'results'=>[]], $results);
    }

    public function testTwoAPIsConcurrentThenTwoAPIsConcurrentAfter()
    {
        $criteria = [
            [
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['api'=>'tloxp', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
            [
                ['api'=>'tloxp', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
                ['api'=>'pipl', 'name'=>'Rob Douglas', 'city'=>'Oyster Bay', 'state'=>'NY'],
            ],
        ];

        $invoker = new SearchInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertEquals(['trial'=>0, 'results'=>[]], $results);
    }
    */

    public function tloxpResponse()
    {
        $expected = new OutputModel;
        $expected ->report_id = "HPRG-MY3D";
        $expected->link = "Tloxp.api";
        $expected->source = "-";
        $expected->first_name = "Robert";
        $expected->last_name = "Douglas";
        $expected->full_name = "Robert Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Ln, Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Ln";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = "11771";
        $expected->addresses = [
            [
                "street" => "333 E 49th St Apt 2r",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "10017",
                "address" => "333 E 49th St Apt 2r, New York, NY"
            ]
        ];
        $expected->age = "";
        $expected->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ];
        $expected->phones = ['5162205847'];
        $expected->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
           "roamdo12187@aol.com"
        ] ;
        $expected->relatives = [
            [
                "first_name" => "Barry",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Barry K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Lauren",
                "middle_name" => "B",
                "last_name" => "Douglas",
                "full_name" => "Lauren B Douglas",
            ],
            [
                "first_name" => "Marc",
                "middle_name" => "Franklin",
                "last_name" => "Douglas",
                "full_name" => "Marc Franklin Douglas",
            ]
        ];

        $expected = [$expected];
        return $expected;
    }

    public function piplResponse()
    {
        $expected = new OutputModel;
        $expected->report_id = "1";
        $expected->gender = "male";
        $expected->link = "Pipl.api";
        $expected->source = "pipl";
        $expected->first_name = "Rob";
        $expected->last_name = "Douglas";
        $expected->full_name = "Rob Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Lane";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = '11771';
        $expected->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ]
        ];
        $expected->phones = [
            "8179256254",
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $expected->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "romado12187@aol.com",
            "rob.douglas@teamsalessupport.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "robertmdouglas@gmail.com",
        ];
        $expected->addresses =[
            [
                "street" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "address" => "New York, NY",
                "zip" => ""
            ],[
                "street" => "29 Dunham Avenue",
                "city" => "Vineyard Haven",
                "state" => "MA",
                "location" => "Vineyard Haven, MA",
                "address" => "29 Dunham Avenue, Vineyard Haven, MA",
                "zip" =>"02568"
            ],[
                "street" => "1700 Pacific Avenue",
                "city" => "Dallas",
                "state" => "TX",
                "location" => "Dallas, TX",
                "address" => "1700 Pacific Avenue, Dallas, TX",
                "zip" => "75201"
            ]
        ];
        $expected->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        /*
        $expected->companies = [
            "Co-Founder and CTO - Skopenow",
            "CEO and Co-Founder - Skopenow",
            "Co-Founder - Inertia LLC",
            "Co-Fouder - Inertia LLC",
            "Technical Representative - Microsoft",
            "CEO and Founder - OOSTABOO",
            "Product Development / Video Production - Griffin Technology",
            "Director/Editor - Sony Pictures Entertainment",
            "Video Production - Sony Pictures Entertainment",
            "Buffett Senior Healthcare Corp.",
            "vanderbilt university",
        ];
        $expected->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        */
        $expected->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
            "http://www.stickam.com/images/ver1/asset/media/default_live.jpg",
        ];
        $expected->profiles = [
            [
                "domain" => "linkedin.com",
                "url" => 'http://www.linkedin.com/in/robdouglas'
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/people/_/4713141"
            ],
            [
                "domain" => "pinterest.com",
                "url" => "http://pinterest.com/robdouglas7923/"
            ],
            [
                "domain" => "twitter.com",
                "url" => "http://www.twitter.com/romado12187",
            ],
            [
                "domain"=> "cqcounter.com",
                "url" => "http://cqcounter.com/whois/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "dawhois.com",
                "url" => "http://dawhois.com/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/rob.douglas.7923"
            ],
            [
                "domain" => "skopenow.com",
                "url" => "http://skopenow.com",
            ],
        ];
        $expected = [$expected];
        return $expected;
    }


    protected function getRobDouglasMergePiplWithTloxp()
    {
        $result = new OutputModel;
        $result->report_id = "1";
        $result->gender = "male";
        $result->link = "Pipl.api";
        $result->source = "pipl";
        $result->first_name = "Rob";
        $result->last_name = "Douglas";
        $result->full_name = "Rob Douglas";
        $result->location = "Oyster Bay, NY";
        $result->address = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Lane";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = '11771';
        $result->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ]
        ];
        $result->phones = [
            "8179256254",
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $result->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "romado12187@aol.com",
            "rob.douglas@teamsalessupport.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "robertmdouglas@gmail.com",
            "roamdo12187@aol.com"
        ];
        $result->addresses =[
            [
                "street" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "address" => "New York, NY",
                "zip" => ""
            ],[
                "street" => "29 Dunham Avenue",
                "city" => "Vineyard Haven",
                "state" => "MA",
                "location" => "Vineyard Haven, MA",
                "address" => "29 Dunham Avenue, Vineyard Haven, MA",
                "zip" =>"02568"
            ],[
                "street" => "1700 Pacific Avenue",
                "city" => "Dallas",
                "state" => "TX",
                "location" => "Dallas, TX",
                "address" => "1700 Pacific Avenue, Dallas, TX",
                "zip" => "75201"
            ],
            [
                "street" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "Oyster Bay, NY",
                "zip" => "11771",
                "address" => "92 Sunken Orchard Ln, Oyster Bay, NY"
            ]
        ];

        $result->relatives = [
            [
                "first_name" => "Barry",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Barry K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Lauren",
                "middle_name" => "B",
                "last_name" => "Douglas",
                "full_name" => "Lauren B Douglas",
            ],
            [
                "first_name" => "Marc",
                "middle_name" => "Franklin",
                "last_name" => "Douglas",
                "full_name" => "Marc Franklin Douglas",
            ]
        ];
        $result->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        /*
        $result->companies = [
            "Co-Founder and CTO - Skopenow",
            "CEO and Co-Founder - Skopenow",
            "Co-Founder - Inertia LLC",
            "Co-Fouder - Inertia LLC",
            "Technical Representative - Microsoft",
            "CEO and Founder - OOSTABOO",
            "Product Development / Video Production - Griffin Technology",
            "Director/Editor - Sony Pictures Entertainment",
            "Video Production - Sony Pictures Entertainment",
            "Buffett Senior Healthcare Corp.",
            "vanderbilt university",
        ];
        $result->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        */
        $result->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
            "http://www.stickam.com/images/ver1/asset/media/default_live.jpg",
        ];
        $result->profiles = [
            [
                "domain" => "linkedin.com",
                "url" => 'http://www.linkedin.com/in/robdouglas'
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/people/_/4713141"
            ],
            [
                "domain" => "pinterest.com",
                "url" => "http://pinterest.com/robdouglas7923/"
            ],
            [
                "domain" => "twitter.com",
                "url" => "http://www.twitter.com/romado12187",
            ],
            [
                "domain"=> "cqcounter.com",
                "url" => "http://cqcounter.com/whois/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "dawhois.com",
                "url" => "http://dawhois.com/domain/robmdouglas.com.html"
            ],
            [
                "domain" => "facebook.com",
                "url" => "http://www.facebook.com/rob.douglas.7923"
            ],
            [
                "domain" => "skopenow.com",
                "url" => "http://skopenow.com",
            ],
        ];

        return [$result];
    }

    /*
    public function testAllSourcesWithEmptyCriteria()
    {

        $criteria = [[]];

        foreach (new \DirectoryIterator(__DIR__ . '/../src/Sources') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if (stripos($fileInfo->getFilename(), 'abstract') !== false) {
                continue;
            }
            if (stripos($fileInfo->getFilename(), 'interface') !== false) {
                continue;
            }

            $sourceName = strtolower(pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME));

            $criteria[0] ['test']['sources'][]= ['apis'=>[$sourceName]];
        }

        $invoker = $this->generateInvoker($criteria);
        $results = $invoker->run(new ResultMerger, new ResultNormalizer, SyncWorker::class);
        
        $this->assertTrue(true);
    }
    */
}
