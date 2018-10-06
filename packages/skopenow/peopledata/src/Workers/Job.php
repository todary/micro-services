<?php
namespace Skopenow\PeopleData\Workers;

use Skopenow\PeopleData\SearchInvoker;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;

class Job implements JobInterface
{
    const STATUS_WAITING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_PAUSED = 2;
    const STATUS_FINISHED = 3;
    const STATUS_CANCELLED = 4;

    public $id = "";
    public $key;
    public $api;
    public $input;
    public $handler;
    public $stream;
    public $status = self::STATUS_WAITING;
    public $results = [];
    public $list;
    public $invoker;

    public function __construct(string $key, string $api, array $input, SearchInvoker $invoker)
    {
        $this->key = $key;
        $this->id = uniqid();
        $this->api = $api;
        $this->input = $input;
        $this->invoker = $invoker;
    }

    public function start()
    {
        $this->status = Job::STATUS_RUNNING;

        $jobID = $this->id;


        \Log::info("Starting Job", [$this->id, $this->api, $this->input]);

        $this->status = Job::STATUS_FINISHED;

        $criteriaOptions = $this->input;
        if (!empty($criteriaOptions['api_options'])) {
            $firstValue = reset($criteriaOptions['api_options']);
            if (is_array($firstValue)) {
                $criteriaOptions['api_options'] = $criteriaOptions['api_options'][$this->api]??[];
            }
        }
        $criteria = new Criteria($criteriaOptions);
        $account = $this->invoker->getApiAccount($this->api);
        $client = $this->invoker->getApiClient($this->api, $account);

        $apiClass = "Skopenow\\PeopleData\\Sources\\" . ucfirst($this->api);
        $searchApi = new $apiClass($criteria, $account, $client);

        /*
        // TEST
        $this->results = [$jobID];
        if ($this->list) {
            $this->list->onJobFinished($this);
        }
        \Log::info("Finished Job", [$this->id, $this->api, $this->input]);
        return;
        // End Test
        */

        if (/*0 && */!empty($this->input['sandbox'])) {
            $results = $this->getSandboxData();
        } else {
            $searchApi->search();
            $results = $searchApi->getResults();
        }

        foreach ($results as $result) {
            $result->key = $this->key;
        }


        \Log::info("Finished Job", [$this->id, $this->api, $this->input]);

        $this->status = Job::STATUS_FINISHED;
        $this->results = $results;

        if ($this->list) {
            $this->list->onJobFinished($this);
        }
    }

    public function ping()
    {
        if ($this->list) {
            $this->list->worker->pingJob($this);
        }
    }

    public function pause()
    {
    }

    public function terminate()
    {
    }


    public function getSandboxData()
    {
        $expected = new OutputModel;
        $expected ->report_id = "HPRG-MY3D";
        $expected->link = "Tloxp.api";
        $expected->source = "-";
        $expected->first_name = "Robert";
        $expected->last_name = "Douglas";
        $expected->full_name = "Robert Douglas";
        $expected->location = "New York, NY";
        $expected->address = "333 E 49th St Apt 2r, New York, NY";
        $expected->street = "333 E 49th St Apt 2r";
        $expected->city = "New York";
        $expected->state = "NY";
        $expected->zip = "10017";
        $expected->age = "";
        $expected->other_names = [
            [
                "first_name" => "Rob",
                "middle_name" => "",
                "last_name" => "Douglas",
                "full_name" => "Rob Douglas"
            ]
        ];
        $expected->phones = ["5162205847"];
        $expected->emails = [
            "romado12187@aol.com",
            "rdouglas2@yahoo.com",
            "rob.douglas@hotmail.com",
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

        $expected->addresses = [
            [
                "street" => "92 Sunken Orchard Ln",
                "city" => "Oyster Bay",
                "state" => "NY",
                "location" => "Oyster Bay, NY",
                "zip" => "11771",
                "address" => "92 Sunken Orchard Ln, Oyster Bay, NY"
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
}
