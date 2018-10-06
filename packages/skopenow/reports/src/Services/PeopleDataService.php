<?php
namespace Skopenow\Reports\Services;

use App\Models\PeopleDataResult;

/**
*
*/
class PeopleDataService
{
    public function fetchFromPipl($report)
    {
        $person = \Persons::model()->findByPk( $report->id);

        //use piple
        $pipl = new \Automation\Pipl($person);
        $searchApisResults = $pipl->result;
        //map results

        return $searchApisResults;
    }

    public function fetchFromTloxp($report)
    {
        $person = \Persons::model()->findByPk( $report->id);
        $tloxp = new \Tloxp();
        $tloxp->person = $person;
        $tloxp->active = $tloxp->test();

        if ($tloxp->active) {
            //use tloxp
            $searchApisResults = $tloxp->result;
        }
        //map results

        return $searchApisResults;
    }

    public function search(array $criteria)
    {
        $peopledata = loadService('PeopleData');
        $return = $peopledata->search($criteria);
        // dd($return);
        return $return;
        //
        //
        $result = new PeopleDataResult;
        $result->report_id = "1";
        $result->gender = "male";
        $result->link = "Pipl.api";
        $result->source = "-";
        $result->first_name = "Rob";
        $result->last_name = "Douglas";
        $result->full_name = "Rob Douglas";
        $result->location = "Oyster Bay, NY";
        $result->address = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $result->street = "92 Sunken Orchard Lane";
        $result->city = "Oyster Bay";
        $result->state = "NY";
        $result->age = "25";
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
                "address" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "street" => "",
                "zip" => ""
            ],[
                "address" => "29 Dunham Avenue",
                "city" => "Vineyard Haven",
                "state" => "MA",
                "location" => "Vineyard Haven, MA",
                "street" => "",
                "zip" =>"02568"
            ],[
                "address" => "1700 Pacific Avenue",
                "city" => "Dallas",
                "state" => "TX",
                "location" => "Dallas, TX",
                "street" => "1700 Pacific Avenue, Dallas, TX",
                "zip" => "75201"
            ],
            [
                "address" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "11771",
                "street" => "92 Sunken Orchard Ln, New York, NY"
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
        $result->companies = [
            "Co-Founder and CTO-Skopenow",
            "CEO and Co-Founder-Skopenow",
            "Co-Founder-Inertia LLC",
            "Co-Fouder-Inertia LLC",
            "Technical Representative-Microsoft",
            "CEO and Founder-OOSTABOO",
            "Product Development / Video Production-Griffin Technology",
            "Director/Editor-Sony Pictures Entertainment",
            "Video Production-Sony Pictures Entertainment",
            "Buffett Senior Healthcare Corp.",
            "vanderbilt university",
        ];
        $result->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
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

        $result2 = new PeopleDataResult;
        $result2->report_id = "1";
        $result2->gender = "male";
        $result2->link = "Pipl.api";
        $result2->source = "-";
        $result2->first_name = "Rob";
        $result2->last_name = "Douglas";
        $result2->full_name = "Rob Douglas";
        $result2->location = "Oyster Bay, NY";
        $result2->street = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $result2->address = "92 Sunken Orchard Lane";
        $result2->city = "Oyster Bay";
        $result2->age = "25";
        $result2->state = "NY";
        $result2->zip = '11771';
        $result2->other_names = [
            [
                "first_name" => "Robert",
                "middle_name" => "",
                "last_name" => "Douglas",
                 "full_name" => "Robert Douglas"
            ]
        ];
        $result2->phones = [
            "8179256254",
            "5169220464",
            "5169220465",
            "5162205847",
            "5088681597",
            "7132008155",
        ];
        $result2->emails = [
            "robert.m.douglas@vanderbilt.edu",
            "romado12187@aol.com",
            "rob.douglas@teamsalessupport.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
            "robertmdouglas@gmail.com",
            "roamdo12187@aol.com"
        ];
        $result2->addresses =[
            [
                "address" => "",
                "city" => "New York",
                "state" => "NY",
                "location" => "New York, NY",
                "street" => "New York, NY",
                "zip" => ""
            ],[
                "address" => "29 Dunham Avenue",
                "city" => "Vineyard Haven",
                "state" => "MA",
                "location" => "Vineyard Haven, MA",
                "street" => "29 Dunham Avenue, Vineyard Haven, MA",
                "zip" =>"02568"
            ],[
                "address" => "1700 Pacific Avenue",
                "city" => "Dallas",
                "state" => "TX",
                "location" => "Dallas, TX",
                "street" => "1700 Pacific Avenue, Dallas, TX",
                "zip" => "75201"
            ],
            [
                "address" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "New York, NY",
                "zip" => "11771",
                "street" => "92 Sunken Orchard Ln, New York, NY"
            ]
        ];

        $result2->relatives = [
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
        $result2->usernames =[
            "robdouglas",
            "rob.douglas.7923",
            "romado12187"

        ];
        $result2->companies = [
            "Co-Founder and CTO-Skopenow",
            "CEO and Co-Founder-Skopenow",
            "Co-Founder-Inertia LLC",
            "Co-Fouder-Inertia LLC",
            "Technical Representative-Microsoft",
            "CEO and Founder-OOSTABOO",
            "Product Development / Video Production-Griffin Technology",
            "Director/Editor-Sony Pictures Entertainment",
            "Video Production-Sony Pictures Entertainment",
            "Buffett Senior Healthcare Corp.",
            "vanderbilt university",
        ];
        $result2->educations = [
            "Obhs",
            "Vanderbilt University",
            "Oyster Bay High School"
        ];
        $result2->images = [
            "https://media.licdn.com/mpr/mpr/shrinknp_200_200/AAEAAQAAAAAAAAhyAAAAJDliMGU0ZDcxLWU5ODYtNGI2Yi05NjFkLWUzYjdjMDlhZDVkNg.jpg",
            "http://graph.facebook.com/4713141/picture?type=large",
            "https://s-media-cache-ak0.pinimg.com/avatars/robdouglas7923_1391109566_140.jpg",
            "http://www.stickam.com/images/ver1/asset/media/default_live.jpg",
        ];
        $result2->profiles = [
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

        /*if ($temp === 'multi') {
            return [$result, $result2];
        } elseif ($temp === 'one') {
            return [$result];
        }
        return [new PeopleDataResult];*/
        return ["results" => [$result, $result2]];
    }
}
