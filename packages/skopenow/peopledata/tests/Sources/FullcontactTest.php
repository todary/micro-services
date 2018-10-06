<?php

namespace Skopenow\PeopleDataTest\Sources;

use Skopenow\PeopleData\Sources\Fullcontact;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Skopenow\PeopleData\Clients\CurlClient;

/**
 *
 */
class FullcontactTest extends \TestCase
{
    public function testSearch()
    {
        $cache_id = 'Fetcher_fullcontact-c2874be40a8b8fe32204d57275153956';
        \Cache::delete($cache_id);

        // inputs
        $one_fullcontact_tinput = $this->oneFullcontactTestInput();

        // accounts
        $fullcontact_account = $this->fullcontactAccount();

        // responses
        $fullcontact_response = $this->fullcontactResponse();
        // mocks
        // fullcontact

        config(['settings.fullcontact_key'=>'-']);
        setUrlMock("https://api.fullcontact.com/v2/person.json?email=bart%40fullcontact.com&method=email&apiKey=-", file_get_contents(__DIR__ . '/../Data/Fullcontact-Search-Bart_AT_fullcontact.com.html'));
        setUrlMock("https://plus.google.com/_/PlusAppUi/data?username=111748526539078793602", file_get_contents(__DIR__ . '/../Data/GooglePlus-bartlorang.html'));

        $search = new Fullcontact($one_fullcontact_tinput, $fullcontact_account, new CurlClient);
        $search->search();
        $actual = $search->getResults();

        $this->assertEquals($fullcontact_response, $actual);
    }

    public function oneFullcontactTestInput()
    {
        $criteria = [
        "apis"=>["fullcontact"],
            "email" => "bart@fullcontact.com",
        ];
        return new Criteria($criteria);
    }

    public function fullcontactResponse()
    {
        $expected = new OutputModel;
        $expected->link = "fullcontact";
        $expected->source = "fullcontact";
        $expected->first_name = "Bart";
        $expected->last_name = "Lorang";
        $expected->full_name = "Bart Lorang";
        $expected->location = "Boulder, Colorado";
        $expected->street = "";
        $expected->address = "Boulder, Colorado";
        $expected->city = "Boulder";
        $expected->state = "Colorado";
        $expected->companies = [
            "Co-Founder & CEO - FullContact",
            "Co-Founder & Managing Director - V1.vc",
        ];

        $expected->profiles = [
            [
                "domain" => "about.me",
                "url" => "https://about.me/lorangb",
            ],
            [
                "domain" => "angel.co",
                "url" => "https://angel.co/bartlorang",
            ],
            [
                "domain" => "facebook.com",
                "url" => "https://www.facebook.com/bart.lorang",
            ],
            [
                "domain" => "flickr.com",
                "url" => "https://www.flickr.com/people/39267654@N00",
            ],
            [
                "domain" => "github.com",
                "url" => "https://github.com/lorangb",
            ],
            [
                "domain" => "plus.google.com",
                "url" => "https://plus.google.com/111748526539078793602",
            ],
            [
                "domain" => "gravatar.com",
                "url" => "https://gravatar.com/blorang",
            ],
            [
                "domain" => "news.ycombinator.com",
                "url" => "http://news.ycombinator.com/user?id=lorangb",
            ],
            [
                "domain" => "instagram.com",
                "url" => "https://instagram.com/bartlorang",
            ],
            [
                "domain" => "keybase.io",
                "url" => "https://keybase.io/bartlorang",
            ],
            [
                "domain" => "linkedin.com",
                "url" => "https://www.linkedin.com/in/bartlorang",
            ],
            [
                "domain" => "pinterest.com",
                "url" => "https://www.pinterest.com/lorangb",
            ],
            [
                "domain" => "plancast.com",
                "url" => "http://www.plancast.com/lorangb",
            ],
            [
                "domain" => "quora.com",
                "url" => "http://www.quora.com/bart-lorang",
            ],
            [
                "domain" => "twitter.com",
                "url" => "http://twitter.com/bartlorang",
            ],
            [
                "domain" => "xing.com",
                "url" => "https://www.xing.com/profile/bart_lorang2",
            ],
            [
                "domain" => "youtube.com",
                "url" => "https://youtube.com/user/lorangb",
            ],
        ];
        $expected = [$expected];
        return $expected;
    }

    public function fullcontactAccount()
    {
        $account = new \App\Models\ApiAccount();

        return $account;
    }
}
