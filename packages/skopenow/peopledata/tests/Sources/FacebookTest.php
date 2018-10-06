<?php

namespace Skopenow\PeopleDataTest\Sources;

use Skopenow\PeopleData\Sources\Facebook;
use Skopenow\PeopleData\Criteria;
use Skopenow\PeopleData\OutputModel;
use Skopenow\PeopleData\Clients\CurlClient;

/**
 *
 */
class FacebookTest extends \TestCase
{
    public function testSearch()
    {
        \Cache::delete("Fetcher_facebook-c2874be40a8b8fe32204d57275153956");

        // inputs
        $one_facebook_tinput = $this->oneFacebookTestInput();

        // accounts
        $facebook_account = $this->facebookAccount();

        // responses
        $facebook_response = $this->facebookResponse();
        // mocks
        // facebook

        setUrlMock("https://www.facebook.com/search/people/?q=bart%40fullcontact.com", file_get_contents(__DIR__ . '/../Data/Facebook-bart_AT_fullcontact.com.html'));
        setUrlMock("https://m.facebook.com/bart.lorang/about", file_get_contents(__DIR__ . '/../Data/Facebook-bart.lorang.html'));

        $search = new Facebook($one_facebook_tinput, $facebook_account, new CurlClient);
        $search->search();
        $actual = $search->getResults();

        $this->assertEquals($facebook_response, $actual);
    }

    public function oneFacebookTestInput()
    {
        $criteria = [
            "apis"=>["facebook"],
            "email" => "bart@fullcontact.com",
        ];
        return new Criteria($criteria);
    }

    public function facebookResponse()
    {
        $expected = new OutputModel;
        $expected->link = "facebook_people_search";
        $expected->source = "facebook";
        $expected->first_name = "Bart";
        $expected->last_name = "Lorang";
        $expected->full_name = "Bart Lorang";
        $expected->location = "Boulder, Colorado";
        $expected->street = "";
        $expected->address = "Boulder, Colorado";
        $expected->city = "Boulder";
        $expected->state = "Colorado";
        $expected->usernames = ['bart.lorang'];
        $expected->addresses =[
            [
                "street" => "",
                "city" => "Denver",
                "state" => "Colorado",
                "location" => "Denver, Colorado",
                "address" => "Denver, Colorado",
                "zip" => ""
            ]
        ];
        $expected->emails = [
            "bart@fullcontact.com",
        ];
        $expected->companies = [
            "CEO & Co-Founder - FullContact",
            "Techstars",
        ];
        $expected->images = [
            "https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/c6.6.74.74/p86x86/3699_10152131417300442_692913076_n.jpg?oh=c3efd6c073f865b90e3955de129a87b0&oe=5A73A1C2",
        ];
        $expected->profiles = [
            [
                "url"=>"https://www.facebook.com/bart.lorang",
                "domain"=>"facebook.com",
            ]
        ];

        $expected = [$expected];
        return $expected;
    }

    public function facebookAccount()
    {
        $account = new \App\Models\ApiAccount();

        return $account;
    }
}
