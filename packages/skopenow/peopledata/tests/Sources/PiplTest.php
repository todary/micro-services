<?php

namespace Skopenow\PeopleDataTest\Sources;

use Skopenow\PeopleData\Sources\Pipl;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Skopenow\PeopleData\Clients\CurlClient;

/**
 *
 */
class PiplTest extends \TestCase
{
    public function testSearch()
    {
        \Cache::delete('Pipl-94efdf5fbe6794401fdeb770877c5699');
        \Cache::delete('Pipl-465b426d62160de5d032ed6110b193ed');
        \Cache::delete('Pipl-1aa30a541c1fa456d7196014bd88fd9f');

        // inputs
        $one_pipl_tinput = $this->onePiplTestInput();

        // accounts
        $pipl_account = $this->piplAccount();

        // responses
        $pipl_response = $this->piplResponse();
        // mocks
        // pipl
        setUrlMock("http://api.pipl.com/search/?key=&raw_name=Rob+Douglas&raw_address=Oyster+Bay%2C+NY", file_get_contents(__DIR__.'/../Data/PIPL-RobDouglas.json'));
        setUrlMock("http://api.pipl.com/search/?key=&raw_name=Rob+Douglas&email=robert.m.douglas%40vanderbilt.edu&age=27-37&raw_address=92+Sunken+Orchard+Lane%2C+Oyster+Bay%2C+NY%2C+Oyster+Bay%2C+NY", file_get_contents(__DIR__.'/../Data/PIPL-RobDouglas.json'));

        $search = new Pipl($one_pipl_tinput, $pipl_account, new CurlClient);
        $search->search();
        $actual = $search->getResults();

        $this->assertEquals($pipl_response, $actual);
    }

    public function onePiplTestInput()
    {
        $criteria = [
            "apis"=>["pipl"],
            "name" => "Rob Douglas",
            "city" => "Oyster Bay",
            "state" => "NY",
            "address" => "92 Sunken Orchard Lane, Oyster Bay, NY",
            "email" => "robert.m.douglas@vanderbilt.edu",
            "username" => "robdouglas",
            "age" => 32,
            "company" => "Co-Founder and CTO - Skopenow",
            "school" => "Obhs",
            "report_id" => "1",
        ];
        return new Criteria($criteria);
    }

    public function piplResponse()
    {
        $expected = new OutputModel;
        $expected->report_id = "1";
        $expected->result_rank = 2;
        $expected->gender = "male";
        $expected->link = "Pipl.api";
        $expected->source = "pipl";
        $expected->first_name = "Rob";
        $expected->last_name = "Douglas";
        $expected->full_name = "Rob Douglas";
        $expected->location = "Oyster Bay, NY";
        $expected->address = "92 Sunken Orchard Lane, Oyster Bay, NY";
        $expected->street = "92 Sunken Orchard Lane";
        $expected->city = "Oyster Bay";
        $expected->state = "NY";
        $expected->zip = '11771';
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
        $expected = [$expected];
        return $expected;
    }

    public function piplAccount()
    {
        $account = new \App\Models\ApiAccount();

        return $account;
    }
}
