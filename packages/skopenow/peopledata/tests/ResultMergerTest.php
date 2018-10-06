<?php

namespace Skopenow\PeopleDataTest;

use Skopenow\PeopleData\ResultMerger;
use Skopenow\PeopleData\ResultMatcher;
use Skopenow\PeopleData\OutputModel;

class ResultMergerTest extends \TestCase
{
    public function testEmptyResults()
    {
        $results = [];
        $merger = new ResultMerger();
        $merged = $merger->mergeAll($results, new ResultMatcher);
        $this->assertEquals($results, $merged);
    }

    public function testOneResult()
    {
        $results = [$this->getRobDouglasResult()];
        $merger = new ResultMerger();
        $merged = $merger->mergeAll($results, new ResultMatcher);
        $this->assertEquals($results, $merged);
    }

    public function testDublicateResult()
    {
        $results = [$this->getRobDouglasResult(), $this->getRobDouglasResult()];
        $merger = new ResultMerger();
        $merged = $merger->mergeAll($results, new ResultMatcher);
        $this->assertEquals([$this->getRobDouglasResult()], $merged);
    }

    public function testPiplWithTloxpResults()
    {
        $results = [$this->getRobDouglasResult(), $this->getRobDouglasResult2()];
        $merger = new ResultMerger();
        $merged = $merger->mergeAll($results, new ResultMatcher);
        $this->assertEquals([$this->getRobDouglasMerge1With2()], $merged);
    }

    public function testTloxpWithPiplResults()
    {
        $results = [$this->getRobDouglasResult2(), $this->getRobDouglasResult()];
        $merger = new ResultMerger();
        $merged = $merger->mergeAll($results, new ResultMatcher);
        $this->assertEquals([$this->getRobDouglasMerge2With1()], $merged);
    }

    protected function getRobDouglasResult()
    {
        $result = new OutputModel;
        $result->report_id = "1";
        $result->gender = "male";
        $result->result_rank = 2;
        $result->link = "Pipl.api";
        $result->source = "pipl";
        $result->first_name = "Rob";
        $result->last_name = "Douglas";
        $result->full_name = "Rob Douglas";
        $result->location = "Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Lane";
        $result->address = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = '11771';
        $result->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robby",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robby Douglas"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/98c10b6dad911f953a4d9d730a55d8ee"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/Oyster_bay_NY/9a3776a071f15162087eda24ea60f65b"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/_/0506dc58b83b451b8840fc554ff41b83"
            ],
            [
                "domain" =>"whitepages.plus",
                "url" =>"https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/764e197a47c529302a5097174b307965"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/323a15a2b3ba939404cd09e499ffd3e2"
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

        return $result;
    }

    protected function getRobDouglasResult2()
    {
        $result = new OutputModel;
        $result ->report_id = "HPRG-MY3D";
        $result->link = "Tloxp.api";
        $result->source = "tloxp";
        $result->result_rank = 3;
        $result->first_name = "Robert";
        $result->last_name = "Douglas";
        $result->full_name = "Robert Douglas";
        $result->location = "Oyster Bay, NY";
        $result->address = "92 Sunken Orchard Ln, Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Ln";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = "11771";
        $result->addresses = [
            [
                "street" => "333 E 49th St Apt 2r",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "10017",
                "address" => "333 E 49th St Apt 2r, New York, NY"
            ]
        ];
        $result->age = "";
        $result->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ];
        $result->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
        ];
        $result->usernames =[
            "robdouglas",
        ];
        $result->phones = ["5162205847"];
        $result->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
           "roamdo12187@aol.com"
        ] ;
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
        $result->profiles = [
            [
                "domain" => "linkedin.com",
                "url" => 'http://www.linkedin.com/in/robdouglas'
            ],
        ];
        return $result;
    }

    protected function getRobDouglasMerge1With2()
    {
        $result = new OutputModel;
        $result->report_id = "1";
        $result->gender = "male";
        $result->result_rank = 5;
        $result->link = "Pipl.api";
        $result->source = "merged";
        $result->first_name = "Rob";
        $result->last_name = "Douglas";
        $result->full_name = "Rob Douglas";
        $result->location = "Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Ln";
        $result->address = "92 Sunken Orchard Ln, Oyster Bay, NY";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = '11771';
        $result->merged_sources = [
            'pipl:1',
            'tloxp:HPRG-MY3D',
        ];
        $result->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robby",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robby Douglas"
            ],
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
                "street" => "333 E 49th St Apt 2r",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "address" => "333 E 49th St Apt 2r, New York, NY",
                "zip" => "10017"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/98c10b6dad911f953a4d9d730a55d8ee"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/Oyster_bay_NY/9a3776a071f15162087eda24ea60f65b"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/_/0506dc58b83b451b8840fc554ff41b83"
            ],
            [
                "domain" =>"whitepages.plus",
                "url" =>"https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/764e197a47c529302a5097174b307965"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/323a15a2b3ba939404cd09e499ffd3e2"
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

        return $result;
    }

    protected function getRobDouglasMerge2With1()
    {
        $result = new OutputModel;
        $result->report_id = "HPRG-MY3D";
        $result->gender = "male";
        $result->result_rank = 5;
        $result->link = "Tloxp.api";
        $result->source = "merged";
        $result->first_name = "Robert";
        $result->last_name = "Douglas";
        $result->full_name = "Robert Douglas";
        $result->location = "Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Ln";
        $result->address = "92 Sunken Orchard Ln, Oyster Bay, NY";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = '11771';
        $result->merged_sources = [
            'tloxp:HPRG-MY3D',
            'pipl:1',
        ];
        $result->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robby",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robby Douglas"
            ],
        ];
        $result->phones = [
            "5162205847",
            "8179256254",
            "5169220464",
            "5169220465",
            "5088681597",
            "7132008155",
        ];
        $result->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "roamdo12187@aol.com",
            "robert.m.douglas@vanderbilt.edu",
            "rob.douglas@teamsalessupport.com",
            "robertmdouglas@gmail.com",
        ];
        $result->addresses =[
            [
                "street" => "333 E 49th St Apt 2r",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "address" => "333 E 49th St Apt 2r, New York, NY",
                "zip" => "10017"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/98c10b6dad911f953a4d9d730a55d8ee"
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
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/Oyster_bay_NY/9a3776a071f15162087eda24ea60f65b"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Rob_Douglas/_/0506dc58b83b451b8840fc554ff41b83"
            ],
            [
                "domain" =>"whitepages.plus",
                "url" =>"https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/764e197a47c529302a5097174b307965"
            ],
            [
                "domain" => "whitepages.plus",
                "url" => "https://whitepages.plus/n/Robert_Douglas/Oyster_bay_NY/323a15a2b3ba939404cd09e499ffd3e2"
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

        return $result;
    }
}
