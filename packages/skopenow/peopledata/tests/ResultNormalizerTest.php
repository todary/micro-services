<?php

namespace Skopenow\PeopleDataTest;

use Skopenow\PeopleData\ResultNormalizer;
use Skopenow\PeopleData\OutputModel;

class ResultNormalizerTest extends \TestCase
{
    public function testResults()
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
        $result->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $result->address = "92 Sunken Orchard Lane";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->zip = '11771';
        $result->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Robert M Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Rob M Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "K",
                "last_name" => "Douglas",
                 "full_name" => "Rob K Douglas"
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
        ];
        $result->relatives = [
            [
                "first_name" => "Barry",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Barry K Douglas",
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Rob K Douglas",
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Robert Douglas",
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
                "domain" => "linkedin.com",
                "url" => "http://www.linkedin.com/in/robdouglas"
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

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->report_id = "1";
        $normalizedResult->gender = "male";
        $normalizedResult->link = "Pipl.api";
        $normalizedResult->source = "pipl";
        $normalizedResult->first_name = "Rob";
        $normalizedResult->last_name = "Douglas";
        $normalizedResult->full_name = "Rob Douglas";
        $normalizedResult->location = "Oyster Bay, NY";
        $normalizedResult->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $normalizedResult->address = "92 Sunken Orchard Lane";
        $normalizedResult->city = "Oyster Bay";
        $normalizedResult->state = "NY";
        $normalizedResult->zip = '11771';
        $normalizedResult->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Robert M Douglas"
            ],
        ];
        $normalizedResult->phones = [
            "8179256254",
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $normalizedResult->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "romado12187@aol.com",
            "rob.douglas@teamsalessupport.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "robertmdouglas@gmail.com",
        ];
        $normalizedResult->relatives = [
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
        $normalizedResult->addresses =[
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
        $normalizedResult->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        /*
        $normalizedResult->companies = [
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
        $normalizedResult->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        */
        $normalizedResult->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
            "http://www.stickam.com/images/ver1/asset/media/default_live.jpg",
        ];
        $normalizedResult->profiles = [
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
        $this->assertEquals($normalizedResult, $result);
    }

    public function testMiddleNameRelativeWithNoMiddleNameRelative()
    {
        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);


        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);
    }

    public function testMultipleMiddleNameRelative()
    {
        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "M",
                "last_name" => "Douglas",
                "full_name" => "Kim M Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);

        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "M",
                "last_name" => "Douglas",
                "full_name" => "Kim M Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);

        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "M",
                "last_name" => "Douglas",
                "full_name" => "Kim M Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);

        $result = new OutputModel;
        $result->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "K",
                "last_name" => "Douglas",
                "full_name" => "Kim K Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "M",
                "last_name" => "Douglas",
                "full_name" => "Kim M Douglas",
            ],
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->relatives = [
            [
                "first_name" => "Kim",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Kim Douglas",
            ],
        ];
        $this->assertEquals($normalizedResult, $result);
    }

    public function testOtherNames()
    {
        $result = new OutputModel;
        $result->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Robert Mark Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Rob Mark Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Robert M Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Rob M Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "K",
                "last_name" => "Douglas",
                 "full_name" => "Rob K Douglas"
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Robert Mark Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Rob Mark Douglas"
            ],
        ];
        $this->assertEquals($normalizedResult, $result);
    }

    public function testOtherNames2()
    {
        $result = new OutputModel;
        $result->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Robert M Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "M",
                "last_name" => "Douglas",
                 "full_name" => "Rob M Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Robert Mark Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Rob Mark Douglas"
            ],
        ];

        $normalizer = new ResultNormalizer();
        $normalizer->normalize($result);

        $normalizedResult = new OutputModel;
        $normalizedResult->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Rob Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ],
            [
                "first_name" => "Robert",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Robert Mark Douglas"
            ],
            [
                "first_name" => "Rob",
                "middle_name" => "Mark",
                "last_name" => "Douglas",
                 "full_name" => "Rob Mark Douglas"
            ],
        ];
        $this->assertEquals($normalizedResult, $result);
    }
}
